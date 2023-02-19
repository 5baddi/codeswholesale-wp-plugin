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

namespace BaddiServices\CodesWholesale\Models;

/**
 * Class Product.
 *
 * @category PHP
 *
 * @author   5baddi <project@baddi.info>
 *
 * @link     http://baddi.info
 */

if (! class_exists('WC_Product_Simple')) {
    class Product {}
} else {
    class Product extends \WC_Product_Simple
    {
        public const UUID_META_DATA = 'cws_product_uuid';
        public const PRICE_META_DATA = 'cws_product_price';
    }
}