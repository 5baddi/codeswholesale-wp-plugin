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
 * Trait CodesWholesaleTrait.
 *
 * @category PHP
 *
 * @author   5baddi <project@baddi.info>
 *
 * @link     http://baddi.info
 */
trait CodesWholesaleTrait
{
    public function authenticate(): void
    {
        $values = $this->settingsValues();
        $now = time();
        $expiresIn = $now + ($values[Constants::BEARER_TOKEN_EXPIRES_IN_OPTION] ?? 0);

        if (
            empty($values[Constants::BEARER_TOKEN_OPTION])
            && ! empty($values[Constants::API_CLIENT_ID_OPTION])
            && ! empty($values[Constants::API_CLIENT_SECRET_OPTION])
            && $expiresIn <= $now
        ) {
            $this->createToken();
        }
    }

    public function fetchSupportedProductDescriptionLanguages(): void
    {
        $values = $this->settingsValues();
        $now = time();
        $expiresIn = $now + ($values[Constants::BEARER_TOKEN_EXPIRES_IN_OPTION] ?? 0);

        if (
            empty($values[Constants::SUPPORTED_PRODUCT_DESCRIPTION_LANGUAGES_OPTION])
            && ! empty($values[Constants::BEARER_TOKEN_OPTION])
            && $expiresIn > $now
        ) {
            /** @var CodesWholesaleService */
            $codesWholesaleService = Container::get(CodesWholesaleService::class);

            $languages = $codesWholesaleService->getSupportedProductDescriptionLanguages($values[Constants::BEARER_TOKEN_OPTION]);
            if (empty($languages)) {
                $this->createToken();

                $languages = $codesWholesaleService->getSupportedProductDescriptionLanguages($values[Constants::BEARER_TOKEN_OPTION]);
            }

            update_option(
                Constants::SUPPORTED_PRODUCT_DESCRIPTION_LANGUAGES_OPTION,
                json_encode(array_column($languages['languages'] ?? [], 'name'))
            );
        }
    }

    private function createToken(): void
    {
        $values = $this->settingsValues();

        if (
            empty($values[Constants::API_CLIENT_ID_OPTION])
            || empty($values[Constants::API_CLIENT_SECRET_OPTION])
        ) {
            return;
        }

        /** @var CodesWholesaleService */
        $codesWholesaleService = Container::get(CodesWholesaleService::class);

        $token = $codesWholesaleService->authenticate($values[Constants::API_CLIENT_ID_OPTION], $values[Constants::API_CLIENT_SECRET_OPTION]);
        if (! empty($token) && Arr::has($token, 'access_token')) {
            update_option(Constants::BEARER_TOKEN_OPTION, $token['access_token']);
            update_option(Constants::BEARER_TOKEN_EXPIRES_IN_OPTION, $token['expires_in'] ?? 0);
        }

        if (empty($token) || empty($token['access_token'])) {
            update_option(Constants::BEARER_TOKEN_OPTION, '');
            update_option(Constants::BEARER_TOKEN_EXPIRES_IN_OPTION, 0);
        }
    }
}