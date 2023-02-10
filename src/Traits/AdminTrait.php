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
 * Trait AdminTrait.
 *
 * @category PHP
 *
 * @author   5baddi <project@baddi.info>
 *
 * @link     http://baddi.info
 */
trait AdminTrait
{
    public function registerSettingsPage(): void
    {
        add_menu_page(
            cws5baddiTranslation('CodesWholesale'),
            cws5baddiTranslation('CodesWholesale'),
            'publish_posts',
            sprintf('%s-account-details', CodesWholesaleBy5baddi::SLUG),
            [$this, 'renderAccountPage'],
            sprintf('%simg/favicon.png', CWS_5BADDI_PLUGIN_ASSETS_URL),
            25
        );

        add_submenu_page(
            sprintf('%s-account-details', CodesWholesaleBy5baddi::SLUG),
            cws5baddiTranslation('Account details'),
            cws5baddiTranslation('Account details'),
            'publish_posts',
            sprintf('%s-account-details', CodesWholesaleBy5baddi::SLUG)
        );

        add_submenu_page(
            sprintf('%s-account-details', CodesWholesaleBy5baddi::SLUG),
            cws5baddiTranslation('General settings'),
            cws5baddiTranslation('General settings'),
            'publish_posts',
            CodesWholesaleBy5baddi::SLUG,
            [$this, 'renderSettingsPage']
        );

        add_submenu_page(
            sprintf('%s-account-details', CodesWholesaleBy5baddi::SLUG),
            cws5baddiTranslation('Import products'),
            cws5baddiTranslation('Import products'),
            'publish_posts',
            sprintf('%s-import-products', CodesWholesaleBy5baddi::SLUG),
            [$this, 'renderImportProductsPage']
        );
    }

    public function registerSettingsPageOptions(): void
    {
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && check_admin_referer($this->getGroupName())) {
            $this->saveGeneralSettings();
        }

        $this->render(
            'admin/settings.twig',
            []
        );
    }

    public function renderImportProductsPage(): void
    {
        $this->render(
            'admin/import-products.twig',
            []
        );
    }

    public function renderAccountPage(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && check_admin_referer($this->getGroupName())) {
            $this->saveAccountDetails();
        }

        $this->render(
            'admin/account.twig',
            []
        );
    }

    public function render(string $view, array $data = []): void
    {
        $sharedData = [
            'groupName'           => $this->getGroupName(),
            'values'              => $this->settingsValues(),
            'currencies'          => Constants::CURRENCIES_LIST,
            'languages'           => Constants::LANGUAGES_LIST,
            'logo'                => sprintf('%simg/logo.svg', CWS_5BADDI_PLUGIN_ASSETS_URL),
            'isApiConnected'      => $this->isApiConnected(),
            'urls'                => [
                'accountSettings' => admin_url(sprintf('admin.php?page=%s-account-details', CodesWholesaleBy5baddi::SLUG)),
                'generalSettings' => admin_url(sprintf('admin.php?page=%s', CodesWholesaleBy5baddi::SLUG)),
                'importProducts'  => admin_url(sprintf('admin.php?page=%s-import-products', CodesWholesaleBy5baddi::SLUG)),
            ]
        ];

        Timber::render($view, array_merge($sharedData, $data));
    }

    private function isApiConnected(): bool
    {
        return ! empty(get_option(Constants::BEARER_TOKEN_OPTION, ''));
    }

    private function settingsValues(): array
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
            Constants::BEARER_TOKEN_OPTION
            => get_option(Constants::BEARER_TOKEN_OPTION, ''),
            Constants::BEARER_TOKEN_EXPIRES_IN_OPTION
            => get_option(Constants::BEARER_TOKEN_EXPIRES_IN_OPTION, 0),
            Constants::SUPPORTED_PRODUCT_DESCRIPTION_LANGUAGES_OPTION
            => json_decode(get_option(Constants::SUPPORTED_PRODUCT_DESCRIPTION_LANGUAGES_OPTION, '[]'), true),
            Constants::SUPPORTED_REGIONS_OPTION
            => json_decode(get_option(Constants::SUPPORTED_REGIONS_OPTION, '[]'), true),
            Constants::SUPPORTED_TERRITORIES_OPTION
            => json_decode(get_option(Constants::SUPPORTED_TERRITORIES_OPTION, '[]'), true),
            Constants::SUPPORTED_PLATFORMS_OPTION
            => json_decode(get_option(Constants::SUPPORTED_PLATFORMS_OPTION, '[]'), true),
            Constants::ACCOUNT_DETAILS_OPTION
            => json_decode(get_option(Constants::ACCOUNT_DETAILS_OPTION, '[]'), true),
        ];
    }

    private function getGroupName(): string
    {
        return sprintf('%s_options', CodesWholesaleBy5baddi::SLUG);
    }

    private function saveAccountDetails(): void
    {
        update_option(
            Constants::API_CLIENT_ID_OPTION,
            sanitize_text_field($_POST[Constants::API_CLIENT_ID_OPTION] ?? '')
        );

        update_option(
            Constants::API_CLIENT_SECRET_OPTION,
            sanitize_text_field($_POST[Constants::API_CLIENT_SECRET_OPTION] ?? '')
        );

        update_option(
            Constants::API_CLIENT_SIGNATURE_OPTION,
            sanitize_text_field($_POST[Constants::API_CLIENT_SIGNATURE_OPTION] ?? '')
        );

        $this->createToken();
    }

    private function saveGeneralSettings(): void
    {
        update_option(
            Constants::PROFIT_MARGIN_TYPE_OPTION,
            intval($_POST[Constants::PROFIT_MARGIN_TYPE_OPTION] ?? Constants::DEFAULT_PROFIT_MARGIN_TYPE)
        );

        update_option(
            Constants::CURRENCY_OPTION,
            sanitize_text_field($_POST[Constants::CURRENCY_OPTION] ?? Constants::DEFAULT_CURRENCY)
        );

        update_option(
            Constants::AUTO_COMPLETE_ORDERS_OPTION,
            intval($_POST[Constants::AUTO_COMPLETE_ORDERS_OPTION] ?? 1)
        );

        update_option(
            Constants::PRE_ORDER_PRODUCTS_OPTION,
            intval($_POST[Constants::PRE_ORDER_PRODUCTS_OPTION] ?? 1)
        );

        update_option(
            Constants::AUTOMATIC_PRODUCT_IMPORT_OPTION,
            intval($_POST[Constants::AUTOMATIC_PRODUCT_IMPORT_OPTION] ?? 0)
        );

        update_option(
            Constants::LOW_BALANCE_NOTIFICATION_OPTION,
            intval($_POST[Constants::LOW_BALANCE_NOTIFICATION_OPTION] ?? Constants::DEFAULT_LOW_BALANCE_NOTIFICATION_VALUE)
        );

        update_option(
            Constants::RISK_SCORE_VALUE_OPTION,
            intval($_POST[Constants::RISK_SCORE_VALUE_OPTION] ?? Constants::DEFAULT_RISK_SCORE_VALUE)
        );

        update_option(
            Constants::DOUBLE_CHECK_PRICE_OPTION,
            intval($_POST[Constants::DOUBLE_CHECK_PRICE_OPTION] ?? 1)
        );

        update_option(
            Constants::HIDE_PRODUCTS_OPTION,
            intval($_POST[Constants::HIDE_PRODUCTS_OPTION] ?? 1)
        );

        update_option(
            Constants::PRODUCT_DESCRIPTION_LANGUAGE_OPTION,
            sanitize_text_field($_POST[Constants::PRODUCT_DESCRIPTION_LANGUAGE_OPTION] ?? Constants::DEFAULT_PRODUCT_DESCRIPTION_LANGUAGE)
        );

        update_option(
            Constants::CHARM_PRICING_OPTION,
            intval($_POST[Constants::CHARM_PRICING_OPTION] ?? 0)
        );
    }
}