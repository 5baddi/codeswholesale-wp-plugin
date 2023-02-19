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
 * Class Order.
 *
 * @category PHP
 *
 * @author   5baddi <project@baddi.info>
 *
 * @link     http://baddi.info
 */

if (! class_exists('WC_Order')) {
    class Order {}
} else {
    class Order extends \WC_Order
    {
        public const CWS_ORDER_META_DATA = 'cws_order';
    }
}