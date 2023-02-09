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

use Timber\Timber;
use BaddiServices\CodesWholesale\Constants;
use BaddiServices\CodesWholesale\CodesWholesaleBy5baddi;

/**
 * Trait AdminMenu.
 *
 * @category PHP
 *
 * @author   5baddi <project@baddi.info>
 *
 * @link     http://baddi.info
 */
trait AdminMenu
{
    public function registerSettingsPage(): void
    {
        add_menu_page(
            cws5baddiTranslation('CodesWholesale'),
            cws5baddiTranslation('CodesWholesale'),
            'publish_posts',
            CodesWholesaleBy5baddi::SLUG,
            [$this, 'renderSettingsPage'],
            sprintf('%simg/favicon.png', CWS_5BADDI_PLUGIN_ASSETS_URL),
            25
        );

        add_submenu_page(
            CodesWholesaleBy5baddi::SLUG,
            cws5baddiTranslation('Settings'),
            cws5baddiTranslation('Settings'),
            'publish_posts',
            CodesWholesaleBy5baddi::SLUG
        );

        add_submenu_page(
            CodesWholesaleBy5baddi::SLUG,
            cws5baddiTranslation('Account'),
            cws5baddiTranslation('Account'),
            'publish_posts',
            sprintf('%s-account-details', CodesWholesaleBy5baddi::SLUG),
            [$this, 'renderAccountPage']
        );
    }

    public function registerSettingsPageOptions(): void
    {
        register_setting(
            $this->getGroupName(),
            Constants::API_CLIENT_ID_OPTION,
            [
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field'
            ]
        );

        register_setting(
            $this->getGroupName(),
            Constants::API_CLIENT_SECRET_OPTION,
            [
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field'
            ]
        );

        register_setting(
            $this->getGroupName(),
            Constants::API_CLIENT_SIGNATURE_OPTION,
            [
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field'
            ]
        );

        register_setting(
            $this->getGroupName(),
            Constants::PROFIT_MARGIN_TYPE_OPTION,
            [
                'type'              => 'integer',
                'sanitize_callback' => 'intval'
            ]
        );

        register_setting(
            $this->getGroupName(),
            Constants::CURRENCY_OPTION,
            [
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field'
            ]
        );

        register_setting(
            $this->getGroupName(),
            Constants::AUTO_COMPLETE_ORDERS_OPTION,
            [
                'type'              => 'integer',
                'sanitize_callback' => 'intval'
            ]
        );

        register_setting(
            $this->getGroupName(),
            Constants::PRE_ORDER_PRODUCTS_OPTION,
            [
                'type'              => 'integer',
                'sanitize_callback' => 'intval'
            ]
        );

        register_setting(
            $this->getGroupName(),
            Constants::AUTOMATIC_PRODUCT_IMPORT_OPTION,
            [
                'type'              => 'integer',
                'sanitize_callback' => 'intval'
            ]
        );

        register_setting(
            $this->getGroupName(),
            Constants::LOW_BALANCE_NOTIFICATION_OPTION,
            [
                'type'              => 'integer',
                'sanitize_callback' => 'intval'
            ]
        );

        register_setting(
            $this->getGroupName(),
            Constants::RISK_SCORE_VALUE_OPTION,
            [
                'type'              => 'integer',
                'sanitize_callback' => 'intval'
            ]
        );

        register_setting(
            $this->getGroupName(),
            Constants::DOUBLE_CHECK_PRICE_OPTION,
            [
                'type'              => 'integer',
                'sanitize_callback' => 'intval'
            ]
        );

        register_setting(
            $this->getGroupName(),
            Constants::HIDE_PRODUCTS_OPTION,
            [
                'type'              => 'integer',
                'sanitize_callback' => 'intval'
            ]
        );

        register_setting(
            $this->getGroupName(),
            Constants::PRODUCT_DESCRIPTION_LANGUAGE_OPTION,
            [
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field'
            ]
        );

        register_setting(
            $this->getGroupName(),
            Constants::CHARM_PRICING_OPTION,
            [
                'type'              => 'integer',
                'sanitize_callback' => 'intval'
            ]
        );

        // Add settings link to plugin menu
        add_filter(
            'plugin_action_links_codeswholesale-by-5baddi/codeswholesale-by-5baddi.php',
            function ($links) {
                array_push(
                    $links,
                    sprintf(
                        '<a href="%s">%s</a>',
                        admin_url(sprintf('admin.php?page=%s', CodesWholesaleBy5baddi::SLUG)),
                        cws5baddiTranslation('Settings')
                    )
                );

                return $links;
            }
        );
    }

    public function renderSettingsPage(): void
    {
        Timber::render(
            'admin/settings.twig',
            [
                'groupName'     => $this->getGroupName(),
                'values'        => $this->settingsPageValues(),
                'currencies'    => Constants::SUPPORTED_CURRENCIES,
                'logo'          => sprintf('%simg/logo.svg', CWS_5BADDI_PLUGIN_ASSETS_URL),
            ]
        );
    }

    public function renderAccountPage(): void
    {
        Timber::render(
            'admin/account.twig',
            [
                'groupName'     => $this->getGroupName(),
                'values'        => $this->settingsPageValues(),
                'currencies'    => Constants::SUPPORTED_CURRENCIES,
                'logo'          => sprintf('%simg/logo.svg', CWS_5BADDI_PLUGIN_ASSETS_URL),
            ]
        );
    }

    private function settingsPageValues(): array
    {
        return [
            Constants::API_CLIENT_ID_OPTION
            => get_option(Constants::API_CLIENT_ID_OPTION, ''),
            Constants::API_CLIENT_SECRET_OPTION
            => get_option(Constants::API_CLIENT_SECRET_OPTION, ''),
            Constants::API_CLIENT_SIGNATURE_OPTION
            => get_option(Constants::API_CLIENT_SIGNATURE_OPTION, ''),
            Constants::CURRENCY_OPTION
            => get_option(Constants::CURRENCY_OPTION, Constants::DEFAULT_CURRENCY),
            Constants::PROFIT_MARGIN_TYPE_OPTION
            => intval(get_option(Constants::PROFIT_MARGIN_TYPE_OPTION, Constants::DEFAULT_PROFIT_MARGIN_TYPE)),
            Constants::PROFIT_MARGIN_VALUE_OPTION
            => intval(get_option(Constants::PROFIT_MARGIN_VALUE_OPTION, Constants::DEFAULT_PROFIT_MARGIN_VALUE)),
            Constants::AUTO_COMPLETE_ORDERS_OPTION
            => boolval(get_option(Constants::AUTO_COMPLETE_ORDERS_OPTION, 1)),
            Constants::PRE_ORDER_PRODUCTS_OPTION
            => boolval(get_option(Constants::PRE_ORDER_PRODUCTS_OPTION, 1)),
            Constants::AUTOMATIC_PRODUCT_IMPORT_OPTION
            => boolval(get_option(Constants::AUTOMATIC_PRODUCT_IMPORT_OPTION, 0)),
            Constants::LOW_BALANCE_NOTIFICATION_OPTION
            => get_option(Constants::LOW_BALANCE_NOTIFICATION_OPTION, Constants::DEFAULT_LOW_BALANCE_NOTIFICATION_VALUE),
            Constants::RISK_SCORE_VALUE_OPTION
            => get_option(Constants::RISK_SCORE_VALUE_OPTION, Constants::DEFAULT_RISK_SCORE_VALUE),
            Constants::DOUBLE_CHECK_PRICE_OPTION
            => boolval(get_option(Constants::DOUBLE_CHECK_PRICE_OPTION, 1)),
            Constants::HIDE_PRODUCTS_OPTION
            => boolval(get_option(Constants::HIDE_PRODUCTS_OPTION, 1)),
            Constants::PRODUCT_DESCRIPTION_LANGUAGE_OPTION
            => get_option(Constants::HIDE_PRODUCTS_OPTION, Constants::DEFAULT_PRODUCT_DESCRIPTION_LANGUAGE),
            Constants::CHARM_PRICING_OPTION
            => boolval(get_option(Constants::CHARM_PRICING_OPTION, 0)),
        ];
    }

    private function getGroupName(): string
    {
        return sprintf('%s_options', CodesWholesaleBy5baddi::SLUG);
    }
}