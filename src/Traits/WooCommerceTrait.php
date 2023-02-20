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

use WC_Order;
use WC_Product_Simple;
use Illuminate\Support\Arr;
use BaddiServices\CodesWholesale\Constants;
use BaddiServices\CodesWholesale\Models\Order;
use BaddiServices\CodesWholesale\Core\Container;
use BaddiServices\CodesWholesale\Models\Product;
use BaddiServices\CodesWholesale\Services\AuthService;
use BaddiServices\CodesWholesale\Exceptions\UnauthorizedException;
use BaddiServices\CodesWholesale\Services\Domains\CodesWholesaleService;

/**
 * Trait WooCommerceTrait.
 *
 * @category PHP
 *
 * @author   5baddi <project@baddi.info>
 *
 * @link     http://baddi.info
 */
trait WooCommerceTrait
{
    public function beforeCheckoutProcess()
    {
        if (
            ! function_exists('WC')
            || ! function_exists('wc_get_user_agent')
            || ! function_exists('wc_add_notice')
            || ! class_exists('WC_Geolocation')
        ) {
            return;
        }

        $customer = WC()->session->get('customer');
        $customerEmail = $customer['email'] ?? null;
        $customerAgent = wc_get_user_agent();
        $customerIp = \WC_Geolocation::get_ip_address();

        if (empty($customerEmail) || empty($customerAgent) || empty($customerIp)) {
            wc_add_notice(
                cws5baddiTranslation('Your order can’t be completed because of an issue with the merchant payment setup! Please contact the support...'),
                'error'
            );
        }

        /** @var CodesWholesaleService */
        $codesWholesaleService = Container::get(CodesWholesaleService::class);

        $token = get_option(Constants::BEARER_TOKEN_OPTION, '');
        $allowedRiskScore = floatval(get_option(Constants::ALLOWED_RISK_SCORE_OPTION, Constants::DEFAULT_ALLOWED_RISK_SCORE));
        $customerSecurityCheck = [];

        try {
            $customerSecurityCheck = $codesWholesaleService->checkCustomerRiskScore($token, $customerEmail, $customerAgent, $customerIp);
        } catch (UnauthorizedException $e) {
            AuthService::createCodesWholesaleToken();

            $customerSecurityCheck = $codesWholesaleService->checkCustomerRiskScore($token, $customerEmail, $customerAgent, $customerIp);
        }

        if (
            empty($token)
            || ! Arr::has($customerSecurityCheck, 'riskScore')
            || floatval($customerSecurityCheck['riskScore']) >= $allowedRiskScore
        ) {
            wc_add_notice(
                cws5baddiTranslation('Unable to process your order!'),
                'error'
            );
        }
    }

    public function orderCompletePayment()
    {
        if (! Arr::has($_GET, 'key')) {
            return;
        }

        $orderId = wc_get_order_id_by_order_key($_GET['key']);

        /** @var WC_Order */
        $order = wc_get_order($orderId);
        $token = get_option(Constants::BEARER_TOKEN_OPTION, '');
        $products = [];

        if (! $order instanceof WC_Order || empty($token)) {
            return;
        }

        $orderCwsMetaData = get_post_meta($orderId, Order::CWS_ORDER_META_DATA, true);
        if (! empty($orderCwsMetaData)) {
            return;
        }

        /** @var CodesWholesaleService */
        $codesWholesaleService = Container::get(CodesWholesaleService::class);

        foreach($order->get_items() as $item) {
            /** @var WC_Product_Simple */
            $product = wc_get_product($item->get_product_id());

            if (! $product instanceof WC_Product_Simple) {
                continue;
            }

            $products[] = [
                'productId' => $product->get_meta(Product::UUID_META_DATA),
                'price'     => floatval($product->get_meta(Product::PRICE_META_DATA)),
                'quantity'  => intval($item->get_quantity()),
            ];
        }

        $preOrderAllowed = boolval(get_option(Constants::ALLOW_PRE_ORDER_OPTION, 1));
        $createdCwsOrder = [];

        try {
            $createdCwsOrder = $codesWholesaleService->createOrder($token, $orderId, $products, $preOrderAllowed);
        } catch (UnauthorizedException $e) {
            AuthService::createCodesWholesaleToken();

            $createdCwsOrder = $codesWholesaleService->createOrder($token, $orderId, $products, $preOrderAllowed);
        }

        if (! empty($createdCwsOrder)) {
            add_post_meta($orderId, Order::CWS_ORDER_META_DATA, json_encode($createdCwsOrder), true);
        }
    }
}