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

/**
 * Class Constants.
 *
 * @category PHP
 *
 * @author   5baddi <project@baddi.info>
 *
 * @link     http://baddi.info
 */
class Constants
{
    // Options
    public const API_CLIENT_ID_OPTION = 'cws5baddi_api_client_id';
    public const API_CLIENT_SECRET_OPTION = 'cws5baddi_api_client_secret';
    public const API_CLIENT_SIGNATURE_OPTION = 'cws5baddi_api_client_signature';
    public const PROFIT_MARGIN_TYPE_OPTION = 'cws5baddi_profit_margin_type';
    public const PROFIT_MARGIN_VALUE_OPTION = 'cws5baddi_profit_margin_value';
    public const CURRENCY_OPTION = 'cws5baddi_currency';
    public const AUTO_COMPLETE_ORDERS_OPTION = 'cws5baddi_auto_complete_orders';
    public const PRE_ORDER_PRODUCTS_OPTION = 'cws5baddi_pre_order_products';
    public const AUTOMATIC_PRODUCT_IMPORT_OPTION = 'cws5baddi_automatic_product_import';
    public const LOW_BALANCE_NOTIFICATION_OPTION = 'cws5baddi_low_balance_notification';
    public const RISK_SCORE_VALUE_OPTION = 'cws5baddi_risk_score_value';
    public const DOUBLE_CHECK_PRICE_OPTION = 'cws5baddi_double_check_price';
    public const HIDE_PRODUCTS_OPTION = 'cws5baddi_hide_products';
    public const PRODUCT_DESCRIPTION_LANGUAGE_OPTION = 'cws5baddi_product_description_language';
    public const CHARM_PRICING_OPTION = 'cws5baddi_charm_pricing';
    public const BEARER_TOKEN_OPTION = 'cws5baddi_bearer_token';
    public const BEARER_TOKEN_EXPIRES_IN_OPTION = 'cws5baddi_bearer_token_expires_in';

    public const PROFIT_MARGIN_AMOUNT = 1;
    public const PROFIT_MARGIN_PERCENTAGE = 2;

    public const SUPPORTED_CURRENCIES = [
        'EUR' => 'EUR - EUR'
    ];

    // Default values
    public const DEFAULT_PROFIT_MARGIN_VALUE = 5;
    public const DEFAULT_PROFIT_MARGIN_TYPE = self::PROFIT_MARGIN_PERCENTAGE;
    public const DEFAULT_CURRENCY = 'EUR';
    public const DEFAULT_LOW_BALANCE_NOTIFICATION_VALUE = 100;
    public const DEFAULT_RISK_SCORE_VALUE = 2;
    public const DEFAULT_PRODUCT_DESCRIPTION_LANGUAGE = 'en';
    public const DEFAULT_GRANT_TYPE = 'client_credentials';
}