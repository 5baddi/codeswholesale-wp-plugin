<?php

/**
 * PHP version 7.
 *
 * @category PHP
 *
 * @author   5baddi <project@baddi.info>
 *
 * @link     http://baddi.info
 */

namespace BaddiServices\CodesWholesale\Services;

/**
 * Class AuthService.
 *
 * @category PHP
 *
 * @author   5baddi <project@baddi.info>
 *
 * @link     http://baddi.info
 */
class AuthService
{
    public static function isTokenExpired(int $expiresIn): bool
    {
        $now = time();
        $expiresIn += $now;

        return ($expiresIn <= $now);
    }
}