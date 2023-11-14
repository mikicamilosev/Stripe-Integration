<?php

/**
 * Advanced Security - PHP Register/Login System
 *
 * @author Milos Stojanovic
 * @link   http://mstojanovic.net
 */

/**
 * User login class.
 *
 */
class ASLogin
{

    /**
     * @var ASDatabase Instance of ASDatabase class
     */
    private $db = null;

    /**
     * Class constructor
     *
     * @param ASDatabase $db
     */
    public function __construct(ASDatabase $db)
    {
        $this->db = $db;
    }

    /**
     * Log in user with provided id.
     */
    public function byId(int $id): bool
    {
        if (!$id) {
            return false;
        }

        $this->startUserSession($id);

        return true;
    }

    /**
     * Check if user is logged in.
     *
     * @return boolean TRUE if user is logged in, FALSE otherwise.
     */
    public function isLoggedIn(): bool
    {
        if (ASSession::get("user_id") == null) {
            return false;
        }

        //if enabled, check fingerprint
        if (LOGIN_FINGERPRINT) {
            $loginString = $this->generateLoginString();
            $currentString = ASSession::get("login_fingerprint");

            if ($currentString != null && $currentString !== $loginString) {
                //destroy session, it is probably stolen by someone
                $this->logout();

                return false;
            }
        }

        // If user is banned by the administrator in meantime, log him out
        $result = $this->db->select(
            "SELECT * FROM `as_users` WHERE `user_id` = :id",
            ["id" => ASSession::get("user_id")]
        );

        if (count($result) !== 1) {
            return false;
        }

        $user = $result[0];

        if ($user['banned'] == "Y") {
            $this->logout();
            return false;
        }

        return true;
    }

    /**
     * Login user with given username and password.
     *
     * @return boolean TRUE if login is successful, FALSE otherwise
     */
    public function userLogin(string $username, string $password): bool
    {
        $errors = $this->validateLoginFields($username, $password);

        if (count($errors) != 0) {
            $this->validationError($errors);
        }

        //protect from brute force attack
        if ($this->isBruteForce()) {
            $this->validationError(['password' => trans('brute_force')]);
        }

        //hash password and get data from db
        $result = $this->db->select(
            "SELECT * FROM `as_users` WHERE `username` = :u",
            ["u" => $username]
        );

        if (count($result) !== 1 || !password_verify($password, $result[0]['password'])) {
            //wrong username/password combination
            $this->increaseLoginAttempts();

            $this->validationError(['password' => trans('wrong_username_password')]);
        }

        $user = $result[0];

        // check if user is confirmed
        if ($user['confirmed'] == "N") {
            $this->validationError(['password' => trans('user_not_confirmed')]);
        }

        // check if user is banned
        if ($user['banned'] == "Y") {
            // increase attempts to prevent touching the DB every time
            $this->increaseLoginAttempts();

            // return message that user is banned
            $this->validationError(['password' => trans('user_banned')]);
        }

        //user exist, log him in
        $this->startUserSession($user['user_id']);;

        respond([
            'status' => 'success',
            'page' => get_redirect_page()
        ]);
    }

    private function validationError(array $errors)
    {
        ASResponse::validationError(
            array_merge(['username' => '', 'password' => ''], $errors)
        );
    }

    private function startUserSession(int $userId): void
    {
        $this->updateLoginDate($userId);

        ASSession::set("user_id", $userId);

        ASSession::regenerate();

        if (LOGIN_FINGERPRINT) {
            ASSession::set("login_fingerprint", $this->generateLoginString());
        }
    }

    /**
     * Increase login attempts from specific IP address to preven brute force attack.
     */
    public function increaseLoginAttempts()
    {
        $date = date("Y-m-d");
        $user_ip = $_SERVER['REMOTE_ADDR'];
        $table = 'as_login_attempts';

        //get current number of attempts from this ip address
        $loginAttempts = $this->getLoginAttempts();

        //if they are greater than 0, update the value
        //if not, insert new row
        if ($loginAttempts > 0) {
            $this->db->update(
                $table,
                ["attempt_number" => $loginAttempts + 1],
                "`ip_addr` = :ip_addr AND `date` = :d",
                ["ip_addr" => $user_ip, "d" => $date]
            );
        } else {
            $this->db->insert($table, ["ip_addr" => $user_ip, "date" => $date]);
        }
    }

    /**
     * Log out user and destroy session.
     */
    public function logout()
    {
        ASSession::destroySession();
    }

    /**
     * Check if someone is trying to break password with brute force attack.
     *
     * @return bool TRUE if number of attempts are greater than allowed, FALSE otherwise.
     */
    public function isBruteForce(): bool
    {
        return $this->getLoginAttempts() > LOGIN_MAX_LOGIN_ATTEMPTS;
    }

    /**
     * Validate login fields
     *
     * @param string $username User's username.
     * @param string $password User's password.
     * @return array Array with errors if there are some, empty array otherwise.
     */
    private function validateLoginFields($username, $password): array
    {
        $errors = [];

        if ($username == "") {
            $errors['username'] = trans('username_required');
        }

        if ($password == "") {
            $errors['password'] = trans('password_required');
        }

        return $errors;
    }

    /**
     * Generate string that will be used as fingerprint.
     * This is actually string created from user's browser name and user's IP
     * address, so if someone steals users session, he won't be able to access.
     *
     * @return string Generated string.
     */
    private function generateLoginString(): string
    {
        $fingerprint = sprintf(
            "%s|%s",
            $_SERVER['REMOTE_ADDR'],
            $_SERVER['HTTP_USER_AGENT']
        );

        return hash('sha512', $fingerprint);
    }

    /**
     * Get number of login attempts from user's IP address.
     *
     * @return int Number of login attempts.
     */
    private function getLoginAttempts(): int
    {
        $date = date("Y-m-d");
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;

        if (!$ipAddress) {
            return PHP_INT_MAX;
        }

        $result = $this->db->select(
            "SELECT `attempt_number` FROM `as_login_attempts` WHERE `ip_addr` = :ip AND `date` = :date",
            ["ip" => $ipAddress, "date" => $date]
        );

        if (count($result) == 0) {
            return 0;
        }

        return (int) $result[0]['attempt_number'];
    }

    /**
     * Update database with login date and time when this user is logged in.
     *
     * @param int $userId The ID of user that is logged in.
     */
    private function updateLoginDate($userId): void
    {
        $this->db->update(
            "as_users",
            ["last_login" => date("Y-m-d H:i:s")],
            "user_id = :u",
            ["u" => $userId]
        );
    }
}
