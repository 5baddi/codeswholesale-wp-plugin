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

use Throwable;
use Timber\Timber;
use BaddiServices\CodesWholesale\Constants;
use BaddiServices\CodesWholesale\Core\Container;
use BaddiServices\CodesWholesale\Services\AuthService;
use BaddiServices\CodesWholesale\CodesWholesaleBy5baddi;
use BaddiServices\CodesWholesale\Exceptions\UnauthorizedException;
use BaddiServices\CodesWholesale\Services\Domains\CodesWholesaleService;

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
            [$this, 'renderAccountDetailsPage'],
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
            [$this, 'renderGeneralSettingsPage']
        );

        add_submenu_page(
            sprintf('%s-account-details', CodesWholesaleBy5baddi::SLUG),
            cws5baddiTranslation('Import products'),
            cws5baddiTranslation('Import products'),
            'publish_posts',
            sprintf('%s-import-products', CodesWholesaleBy5baddi::SLUG),
            [$this, 'renderImportProductsPage']
        );

        add_submenu_page(
            sprintf('%s-account-details', CodesWholesaleBy5baddi::SLUG),
            cws5baddiTranslation('Webhook endpoint'),
            cws5baddiTranslation('Webhook endpoint'),
            'publish_posts',
            sprintf('%s-webhook', CodesWholesaleBy5baddi::SLUG),
            [$this, 'renderWebhookPage']
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

    public function renderAccountDetailsPage(): void
    {
        $data = [];
        $accountDetails = [];
        $token = get_option(Constants::BEARER_TOKEN_OPTION, '');
        $lastUpdate = intval(get_option(Constants::LAST_ACCOUNT_DETAILS_UPDATE_OPTION, 0));

        if (! empty($token) || $lastUpdate < strtotime('+15 minutes', time())) {
            try {
                /** @var CodesWholesaleService */
                $codesWholesaleService = Container::get(CodesWholesaleService::class);
    
                $accountDetails = $codesWholesaleService->getAccountDetails($token);

                update_option(Constants::ACCOUNT_DETAILS_OPTION, json_encode($accountDetails ?? '{}'));
                update_option(Constants::LAST_ACCOUNT_DETAILS_UPDATE_OPTION, time());
            } catch (Throwable $e) {
                if ($e instanceof UnauthorizedException) {
                    AuthService::createCodesWholesaleToken();
                }
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && check_admin_referer($this->getGroupName())) {
            $this->saveAccountDetails();

            $token = get_option(Constants::BEARER_TOKEN_OPTION, '');
            $expiresIn = get_option(Constants::BEARER_TOKEN_EXPIRES_IN_OPTION, 0);

            if (empty($token) || AuthService::isTokenExpired($expiresIn)) {
                $data['message'] = cws5baddiTranslation('API connection failed! please check your API credentials...');
                $data['isSuccess'] = false;
            } else {
                $data['message'] = cws5baddiTranslation('Account details saved successfully.');
                $data['isSuccess'] = true;
            }
        }

        $this->render('admin/account.twig', $data);
    }

    public function renderGeneralSettingsPage(): void
    {
        $data = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && check_admin_referer($this->getGroupName())) {
            $profitMargin = intval($_POST[Constants::PROFIT_MARGIN_VALUE_OPTION]);
            if ($profitMargin < 1 || $profitMargin > 60) {
                $data['message'] = cws5baddiTranslation('In stock on the platform for should be between 1 to 60 days!');
                $data['isSuccess'] = false;

                $this->render('admin/settings.twig', $data);

                return;
            }

            $this->saveGeneralSettings();

            $data['message'] = cws5baddiTranslation('General settings saved successfully.');
            $data['isSuccess'] = true;
        }

        $this->render('admin/settings.twig', $data);
    }

    public function renderImportProductsPage(): void
    {
        $data = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && check_admin_referer($this->getGroupName())) {
            $data['productDescriptionLanguage'] = sanitize_text_field($_POST['product_description_language'] ?? '');
            $data['inStockFor'] = intval($_POST['in_stock_for'] ?? 0);
            $data['region'] = sanitize_text_field($_POST['region'] ?? '');
            $data['platform'] = sanitize_text_field($_POST['platform'] ?? '');

            $data['message'] = cws5baddiTranslation('Start importing products...');
            $data['isSuccess'] = true;
            $data['isImporting'] = true;
        }

        $this->render('admin/import-products.twig', $data);
    }

    public function renderWebhookPage(): void
    {
        $data = [];

        $this->render('admin/webhook.twig', $data);
    }

    public function render(string $view, array $data = []): void
    {
        $sharedData = Constants::sharedData();
        $sharedData = array_merge(
            $sharedData,
            [
                'groupName'      => $this->getGroupName(),
                'values'         => $this->settingsValues(),
                'isApiConnected' => $this->isApiConnected(),
            ]
        );

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
            => get_option(Constants::PRODUCT_DESCRIPTION_LANGUAGE_OPTION, Constants::DEFAULT_PRODUCT_DESCRIPTION_LANGUAGE),
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
            Constants::API_MODE_OPTION
            => get_option(Constants::API_MODE_OPTION, Constants::API_SANDBOX_MODE),
        ];
    }

    private function getGroupName(): string
    {
        return sprintf('%s_options', CodesWholesaleBy5baddi::SLUG);
    }

    private function saveAccountDetails(): void
    {
        update_option(
            Constants::API_MODE_OPTION,
            sanitize_text_field($_POST[Constants::API_MODE_OPTION] ?? Constants::API_SANDBOX_MODE)
        );

        if ($_POST[Constants::API_MODE_OPTION] === Constants::API_SANDBOX_MODE) {
            $_POST[Constants::API_CLIENT_ID_OPTION] = CodesWholesaleService::SANDBOX_CLIENT_ID;
            $_POST[Constants::API_CLIENT_SECRET_OPTION] = CodesWholesaleService::SANDBOX_CLIENT_SECRET;
            $_POST[Constants::API_CLIENT_SIGNATURE_OPTION] = '';
        }

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

        AuthService::createCodesWholesaleToken();
    }

    private function saveGeneralSettings(): void
    {
        update_option(
            Constants::PROFIT_MARGIN_TYPE_OPTION,
            intval($_POST[Constants::PROFIT_MARGIN_TYPE_OPTION])
        );

        update_option(
            Constants::PROFIT_MARGIN_VALUE_OPTION,
            intval($_POST[Constants::PROFIT_MARGIN_VALUE_OPTION])
        );

        update_option(
            Constants::PRODUCT_DESCRIPTION_LANGUAGE_OPTION,
            sanitize_text_field($_POST[Constants::PRODUCT_DESCRIPTION_LANGUAGE_OPTION])
        );

        update_option(
            Constants::DOUBLE_CHECK_PRICE_OPTION,
            intval($_POST[Constants::DOUBLE_CHECK_PRICE_OPTION])
        );

        update_option(
            Constants::HIDE_PRODUCTS_OPTION,
            intval($_POST[Constants::HIDE_PRODUCTS_OPTION])
        );

        // update_option(
        //     Constants::CURRENCY_OPTION,
        //     sanitize_text_field($_POST[Constants::CURRENCY_OPTION] ?? Constants::DEFAULT_CURRENCY)
        // );

        // update_option(
        //     Constants::AUTO_COMPLETE_ORDERS_OPTION,
        //     intval($_POST[Constants::AUTO_COMPLETE_ORDERS_OPTION] ?? 1)
        // );

        // update_option(
        //     Constants::PRE_ORDER_PRODUCTS_OPTION,
        //     intval($_POST[Constants::PRE_ORDER_PRODUCTS_OPTION] ?? 1)
        // );

        // update_option(
        //     Constants::AUTOMATIC_PRODUCT_IMPORT_OPTION,
        //     intval($_POST[Constants::AUTOMATIC_PRODUCT_IMPORT_OPTION] ?? 0)
        // );

        // update_option(
        //     Constants::LOW_BALANCE_NOTIFICATION_OPTION,
        //     intval($_POST[Constants::LOW_BALANCE_NOTIFICATION_OPTION] ?? Constants::DEFAULT_LOW_BALANCE_NOTIFICATION_VALUE)
        // );

        // update_option(
        //     Constants::RISK_SCORE_VALUE_OPTION,
        //     intval($_POST[Constants::RISK_SCORE_VALUE_OPTION] ?? Constants::DEFAULT_RISK_SCORE_VALUE)
        // );

        // update_option(
        //     Constants::CHARM_PRICING_OPTION,
        //     intval($_POST[Constants::CHARM_PRICING_OPTION] ?? 0)
        // );
    }
}