<?php

/**
 * Advanced Security - PHP Register/Login System
 *
 * @author Milos Stojanovic
 * @link   http://mstojanovic.net
 */

class ASCsrf
{
    /**
     * CSRF Token name.
     */
    public const TOKEN_NAME = "_as_csrf_token";

    /**
     * (Re-)Generate a token and write it to session
     *
     * @return void
     */
    public static function generateToken(): string
    {
        $token = sha1(password_hash(time() . str_random(20), PASSWORD_BCRYPT));

        ASSession::set(self::TOKEN_NAME, $token);

        return $token;
    }
    /**
     * Get the token.  If it's not defined, this will go ahead and generate one.
     *
     * @return string
     */
    public static function getToken(): string
    {
        if ($token = ASSession::get(self::TOKEN_NAME)) {
            return $token;
        }

        return static::generateToken();
    }

    /**
     * Get CSRF token name.
     *
     * @return string
     */
    public static function getTokenName(): string
    {
        return self::TOKEN_NAME;
    }

    /**
     * Validate the token.  If there's not one yet, it will set one and return false.
     *
     * @param array $requestData - your whole POST/GET array - will index in with the token name to get the token.
     * @return bool
     */
    public static function validate(array $requestData = []): bool
    {
        if (! ASSession::get(self::TOKEN_NAME)) {
            static::generateToken();
            return false;
        }

        if (empty($requestData[self::TOKEN_NAME])) {
            return false;
        }

        return static::compare($requestData[self::TOKEN_NAME], static::getToken());
    }

    /**
     * Constant-time string comparison. This comparison function is timing-attack safe.
     *
     * @param string $hasha
     * @param string $hashb
     * @return bool
     */
    private static function compare(string $hasha = "", string $hashb = ""): bool
    {
        // we want hashes_are_not_equal to be false by the end of this if the strings are identical
        // if the strings are NOT equal length this will return true, else false
        $areNotEqual = strlen($hasha) ^ strlen($hashb);

        // compare the shortest of the two strings (the above line will still
        // kick back a failure if the lengths weren't equal.  this just keeps us
        // from over-flowing our strings when comparing
        $length = min(strlen($hasha), strlen($hashb));
        $hasha = substr($hasha, 0, $length);
        $hashb = substr($hashb, 0, $length);

        // iterate through the hashes comparing them character by character
        // if a character does not match, then return true, so the hashes are not equal
        for ($i = 0; $i < strlen($hasha); $i++) {
            $areNotEqual += ord($hasha[$i]) !== ord($hashb[$i]);
        }
        // if not hashes are not equal, then hashes are equal
        return !$areNotEqual;
    }
}
