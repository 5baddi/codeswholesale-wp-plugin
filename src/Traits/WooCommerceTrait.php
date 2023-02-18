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

use Illuminate\Support\Arr;
use BaddiServices\CodesWholesale\Constants;
use BaddiServices\CodesWholesale\Core\Container;
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

        $customerSecurityCheck = $codesWholesaleService->checkCustomerRiskScore($token, $customerEmail, $customerAgent, $customerIp);

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

    public function paymentCompleteOrderStatusCompleted(int $orderId)
    {

    }
}