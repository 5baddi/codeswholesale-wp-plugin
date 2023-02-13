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

namespace BaddiServices\CodesWholesale\Traits;

use WP_Error;
use BaddiServices\CodesWholesale\Constants;

/**
 * Trait ApplyFiltersTrait.
 *
 * @category PHP
 *
 * @author   5baddi <project@baddi.info>
 *
 * @link     http://baddi.info
 */
trait ApplyFiltersTrait
{
    public function restAuthenticationErrors($errors)
    {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            return $errors;
        }

        $origin = parse_url($_SERVER['HTTP_ORIGIN'], PHP_URL_HOST);
        $host = parse_url($_SERVER['HTTP_HOST'], PHP_URL_HOST);

        if ($origin !== $host && ! in_array($origin, Constants::ALLOWED_ORIGINS)) {
            return new WP_Error('rest_forbidden', sprintf('Sorry, %s not allowed to do that.', esc_url($origin)), ['status' => 403]);
        }

        return $errors;
    }
}