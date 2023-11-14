<?php

/**
 * Advanced Security - PHP Register/Login System
 *
 * @author Milos Stojanovic
 * @link   http://mstojanovic.net
 */

class ASPasswordHasher
{
    public const ROUNDS = 13;

    /**
     * Hash the provided password.
     *
     * @param $password
     * @return string
     */
    public function hashPassword($password): string
    {
        return password_hash($password, PASSWORD_BCRYPT, [
            'cost' => self::ROUNDS,
        ]);
    }
}
