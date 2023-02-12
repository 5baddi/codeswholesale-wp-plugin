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

namespace BaddiServices\CodesWholesale;

use Throwable;

/**
 * Class Logger.
 *
 * @category PHP
 *
 * @author   5baddi <project@baddi.info>
 *
 * @link     http://baddi.info
 */
class Logger
{
    public static function trace(Throwable $throwable): void 
    {
        if (! defined('WP_DEBUG') || ! WP_DEBUG) {
            return;
        }

        var_dump($throwable);die(); // FIXME: use logging provider
    }
}