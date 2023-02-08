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

namespace BaddiServices\CodesWholesale\Core;

use InvalidArgumentException;

/**
 * Class CodesWholesaleBy5baddi.
 *
 * @category PHP
 *
 * @author   5baddi <project@baddi.info>
 *
 * @link     http://baddi.info
 */
class Container
{
    /**
     * @var object[]
     */
    private static $classes = [];

    public static function get(string $class, bool $forceNewInstance = false): ?object
    {
        if (empty($class) || ! class_exists($class)) {
            throw new InvalidArgumentException('Class not found!');
        }

        if (! $forceNewInstance && ! empty(self::$classes[$class])) {
            return self::$classes[$class];
        }

        $instance = new $class;
        self::setConfiguration($instance);

        return (self::$classes[$class] = $instance);
    }

    private static function setConfiguration(object $instance): void
    {
        switch (get_class($instance)) {
            // TODO: handle set config for specifc classes
        }
    }
}