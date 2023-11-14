<?php

/**
 * Advanced Security - PHP Register/Login System
 *
 * @author Milos Stojanovic
 * @link   http://mstojanovic.net
 */

class ASRegister
{
    /**
     * @var ASEmail Instance of ASEmail class
     */
    private $mailer;

    /**
     * @var ASDatabase Instance of ASDatabase class
     */
    private $db = null;

    /**
     * @var ASValidator
     */
    private $validator;

    /**
     * @var ASLogin
     */
    private $login;

    /**
     * @var ASPasswordHasher
     */
    private $hasher;

    /**
     * Class constructor
     *
     * @param ASDatabase $db
     * @param ASEmail $mailer
     * @param ASValidator $validator
     * @param ASLogin $login
     * @param ASPasswordHasher $hasher
     */
    public function __construct(
        ASDatabase $db,
        ASEmail $mailer,
        ASValidator $validator,
        ASLogin $login,
        ASPasswordHasher $hasher
    ) {
        $this->db = $db;
        $this->mailer = $mailer;
        $this->validator = $validator;
        $this->login = $login;
        $this->hasher = $hasher;
    }

    /**
     * Register user.
     *
     * @param array $data User details provided during the registration process.
     * @throws Exception
     */
    public function register(array $data)
    {
        //validate provided data
        if ($errors = $this->validateUser($data)) {
            ASResponse::validationError($errors);
        }

        //generate email confirmation key
        $key = $this->generateKey();

        MAIL_CONFIRMATION_REQUIRED ? $confirmed = 'N' : $confirmed = 'Y';

        //insert new user to database
        $this->db->insert('as_users', [
            "email" => $data['email'],
            "username" => strip_tags($data['username']),
            "password" => $this->hashPassword($data['password']),
            "confirmed" => $confirmed,
            "confirmation_key" => $key,
            "register_date" => date("Y-m-d")
        ]);

        $this->db->insert('as_user_details', ['user_id' => $this->db->lastInsertId()]);

        //send confirmation email if needed
        if (MAIL_CONFIRMATION_REQUIRED) {
            $this->mailer->confirmationEmail($data['email'], $key);
            $msg = trans('success_registration_with_confirm');
        } else {
            $msg = trans('success_registration_no_confirm');
        }

        ASResponse::success(['message' => $msg]);
    }

    /**
     * Get user by email.
     *
     * @param $email string User's email
     * @return mixed User info if user with provided email exist, empty array otherwise.
     */
    public function getByEmail($email)
    {
        $result = $this->db->select(
            "SELECT * FROM `as_users` WHERE `email` = :e",
            ['e' => $email]
        );

        if ($result !== []) {
            return $result[0];
        }

        return $result;
    }


    /**
     * Check if user has already logged in via specific provider and return user's data if he does.
     *
     * @param $provider string oAuth provider (Facebook, Twitter or Gmail)
     * @param $id string Identifier provided by provider
     * @return array|mixed User info if user has already logged in via specific provider, empty array otherwise.
     */
    public function getBySocial(string $provider, string $id): array
    {
        $result = $this->db->select(
            'SELECT as_users.*
            FROM as_social_logins, as_users 
            WHERE as_social_logins.provider = :p AND as_social_logins.provider_id = :id
            AND as_users.user_id = as_social_logins.user_id',
            ['p' => $provider, 'id' => $id]
        );

        if ($result !== []) {
            return $result[0];
        }

        return $result;
    }

    /**
     * Check if user is already registered via some social network.
     *
     * @param $provider string Name of the provider ( twitter, facebook or google )
     * @param $id string Provider identifier
     * @return bool TRUE if user exist in database (already registred), FALSE otherwise
     */
    public function registeredViaSocial(string $provider, string $id): bool
    {
        $result = $this->getBySocial($provider, $id);

        return count($result) !== 0;
    }

    /**
     * Connect user's social account with his account at this system.
     *
     * @param $userId int User ID on this system
     * @param $provider string oAuth provider (Facebook, Twitter or Gmail)
     * @param $providerId string Identifier provided by provider.
     */
    public function addSocialAccount(int $userId, string $provider, string $providerId)
    {
        $this->db->insert('as_social_logins', [
            'user_id' => $userId,
            'provider' => $provider,
            'provider_id' => $providerId,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Send forgot password email.
     *
     * @param string $email Provided email.
     * @throws Exception
     */
    public function forgotPassword(string $email)
    {
        if ($error = $this->validateForgotPasswordEmail($email)) {
            ASResponse::validationError(['email' => $error]);
        }

        //ok, no validation errors, we can proceed

        //generate password reset key
        $key = $this->generateKey();

        //write key to db
        $this->db->update(
            'as_users',
            [
                "password_reset_key" => $key,
                "password_reset_confirmed" => 'N',
                "password_reset_timestamp" => date('Y-m-d H:i:s')
            ],
            "`email` = :email",
            ["email" => $email]
        );

        $this->login->increaseLoginAttempts();

        //send email
        $this->mailer->passwordResetEmail($email, $key);

        ASResponse::success();
    }

    /**
     * @param $email
     * @return mixed|null|string
     */
    private function validateForgotPasswordEmail($email)
    {
        if ($email == "") {
            return trans('email_required');
        }

        if (!$this->validator->emailValid($email)) {
            return trans('email_wrong_format');
        }

        if (!$this->validator->emailExist($email)) {
            return trans('email_not_exist');
        }

        if ($this->login->isBruteForce()) {
            return trans('brute_force');
        }

        return null;
    }


    /**
     * Reset user's password if password reset request has been made.
     *
     * @param string $newPass New password.
     * @param string $passwordResetKey Password reset key sent to user
     * in password reset email.
     */
    public function resetPassword(string $newPass, string $passwordResetKey)
    {
        if ($error = $this->validatePasswordReset($newPass, $passwordResetKey)) {
            ASResponse::validationError(['new_password' => $error]);
        }

        $pass = $this->hashPassword($newPass);

        $this->db->update(
            'as_users',
            ["password" => $pass, 'password_reset_confirmed' => 'Y', 'password_reset_key' => ''],
            "`password_reset_key` = :prk ",
            ["prk" => $passwordResetKey]
        );

        ASResponse::success();
    }

    /**
     * @param $newPassword
     * @param $passwordResetKey
     * @return mixed|null|string
     */
    private function validatePasswordReset($newPassword, $passwordResetKey)
    {
        if ($this->validator->isEmpty($newPassword)) {
            return trans('field_required');
        }

        if (!$this->validator->prKeyValid($passwordResetKey)) {
            return trans('invalid_password_reset_key');
        }

        return null;
    }

    /**
     * Hash a given password.
     *
     * @param string $password Un-hashed password.
     * @return string Hashed password.
     */
    public function hashPassword($password): string
    {
        return $this->hasher->hashPassword($password);
    }

    /**
     * Generate two random numbers and store them into the session.
     * Numbers are used during the registration to prevent bots to create fake accounts.
     *
     * @throws \Exception
     */
    public function botProtection()
    {
        ASSession::set("bot_first_number", random_int(1, 9));
        ASSession::set("bot_second_number", random_int(1, 9));
    }

    /**
     * Validate user provided fields.
     *
     * @param $data array User provided fields and id's of those fields that will be
     * used for displaying error messages on client side.
     * @param bool $botProtection Should bot protection be validated or not
     * @return array Array with errors if there are some, empty array otherwise.
     */
    public function validateUser(array $data, bool $botProtection = true): array
    {
        $errors = [];

        //check if email is not empty
        if ($this->validator->isEmpty($data['email'])) {
            $errors['email'] = trans('email_required');
        }

        //check if username is not empty
        if ($this->validator->isEmpty($data['username'])) {
            $errors['username'] = trans('username_required');
        }

        // Check if password is not empty.
        // We cannot check the password length since it is SHA 512 hashed
        // before it is even sent to the server.
        if ($this->validator->isEmpty($data['password'])) {
            $errors['password'] = trans('password_required');
        }

        //check if password and confirm password are the same
        if ($data['password'] !== $data['password_confirmation']) {
            $errors['password_confirmation'] = trans('passwords_dont_match');
        }

        //check if email format is correct
        if (!isset($errors['email']) && !$this->validator->emailValid($data['email'])) {
            $errors['email'] = trans('email_wrong_format');
        }

        //check if email is available
        if (!isset($errors['email']) && $this->validator->emailExist($data['email'])) {
            $errors['email'] = trans('email_taken');
        }

        //check if username is available
        if (!isset($errors['username']) && $this->validator->usernameExist($data['username'])) {
            $errors['username'] = trans('username_taken');
        }

        if ($botProtection) {
            $validSum = ASSession::get("bot_first_number") + ASSession::get("bot_second_number");

            if ($this->validator->isEmpty($data['bot_protection']) || $validSum != (int)$data['bot_protection']) {
                $errors['bot_protection'] = trans('wrong_sum');
            }
        }

        return $errors;
    }

    /**
     * Generates random password
     *
     * @param int $length Length of generated password
     * @return string Generated password
     * @throws \Exception
     */
    public function randomPassword(int $length = 7): string
    {
        return str_random($length);
    }

    /**
     * Generate random token that will be used for social authentication
     *
     * @return string Generated token.
     * @throws \Exception
     */
    public function socialToken(): string
    {
        return str_random(40);
    }

    /**
     * Generate key used for confirmation and password reset.
     *
     * @return string Generated key.
     * @throws \Exception
     */
    private function generateKey(): string
    {
        return md5(time() . str_random(40) . time());
    }
}
