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

namespace CodesWholesaleBy5baddi;

/**
 * Class CodesWholesaleBy5baddi.
 *
 * @category PHP
 *
 * @author   5baddi <project@baddi.info>
 *
 * @link     http://baddi.info
 */

class CodesWholesaleBy5baddi
{
    /**
     * @var CodesWholesaleBy5baddi
     */
    private static $instance;

    public static function getInstance(): CodesWholesaleBy5baddi
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}