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
use BaddiServices\CodesWholesale\Models\Order;
use BaddiServices\CodesWholesale\Models\Product;

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
        $host = $_SERVER['HTTP_HOST'];

        if ($origin !== $host && ! in_array($origin, Constants::ALLOWED_ORIGINS)) {
            return new WP_Error('rest_forbidden', sprintf('Sorry, %s not allowed to do that.', esc_url($origin)), ['status' => 403]);
        }

        return $errors;
    }

    public function protectHiddenCustomMetaFields(bool $isProtected, string $metaKey): bool
    {
        return in_array($metaKey, [Product::PRICE_META_DATA, Product::UUID_META_DATA, Order::CWS_ORDER_META_DATA])
            ? true
            : $isProtected;
    }
}