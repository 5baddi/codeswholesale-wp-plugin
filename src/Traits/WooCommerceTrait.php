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
                cws5baddiTranslation('Your order can’t be completed because of an issue with the merchant payment setup! Please contact us about this issue...'),
                'error'
            );
        }

        wc_add_notice(
            cws5baddiTranslation('Unable to process payment!'),
            'error'
        );
    }
}