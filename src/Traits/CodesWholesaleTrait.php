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

    public function fetchSupportedRegions(): void
    {
        $values = $this->settingsValues();
        $now = time();
        $expiresIn = $now + ($values[Constants::BEARER_TOKEN_EXPIRES_IN_OPTION] ?? 0);

        if (
            empty($values[Constants::SUPPORTED_REGIONS_OPTION])
            && ! empty($values[Constants::BEARER_TOKEN_OPTION])
            && $expiresIn > $now
        ) {
            /** @var CodesWholesaleService */
            $codesWholesaleService = Container::get(CodesWholesaleService::class);

            $regions = $codesWholesaleService->getSupportedRegions($values[Constants::BEARER_TOKEN_OPTION]);
            if (empty($regions)) {
                $this->createToken();

                $regions = $codesWholesaleService->getSupportedRegions($values[Constants::BEARER_TOKEN_OPTION]);
            }

            update_option(
                Constants::SUPPORTED_REGIONS_OPTION,
                json_encode(array_column($regions['regions'] ?? [], 'name'))
            );
        }
    }

    public function fetchSupportedTerritories(): void
    {
        $values = $this->settingsValues();
        $now = time();
        $expiresIn = $now + ($values[Constants::BEARER_TOKEN_EXPIRES_IN_OPTION] ?? 0);

        if (
            empty($values[Constants::SUPPORTED_TERRITORIES_OPTION])
            && ! empty($values[Constants::BEARER_TOKEN_OPTION])
            && $expiresIn > $now
        ) {
            /** @var CodesWholesaleService */
            $codesWholesaleService = Container::get(CodesWholesaleService::class);

            $territories = $codesWholesaleService->getSupportedTerritories($values[Constants::BEARER_TOKEN_OPTION]);
            if (empty($territories)) {
                $this->createToken();

                $territories = $codesWholesaleService->getSupportedTerritories($values[Constants::BEARER_TOKEN_OPTION]);
            }

            update_option(
                Constants::SUPPORTED_TERRITORIES_OPTION,
                json_encode(array_column($territories['territories'] ?? [], 'territory'))
            );
        }
    }

    public function fetchSupportedPlatforms(): void
    {
        $values = $this->settingsValues();
        $now = time();
        $expiresIn = $now + ($values[Constants::BEARER_TOKEN_EXPIRES_IN_OPTION] ?? 0);

        if (
            empty($values[Constants::SUPPORTED_PLATFORMS_OPTION])
            && ! empty($values[Constants::BEARER_TOKEN_OPTION])
            && $expiresIn > $now
        ) {
            /** @var CodesWholesaleService */
            $codesWholesaleService = Container::get(CodesWholesaleService::class);

            $platforms = $codesWholesaleService->getSupportedPlatforms($values[Constants::BEARER_TOKEN_OPTION]);
            if (empty($platforms)) {
                $this->createToken();

                $platforms = $codesWholesaleService->getSupportedPlatforms($values[Constants::BEARER_TOKEN_OPTION]);
            }

            update_option(
                Constants::SUPPORTED_PLATFORMS_OPTION,
                json_encode(array_column($platforms['platforms'] ?? [], 'name'))
            );
        }
    }

    private function createToken()
    {
        $values = $this->settingsValues();

        if (
            empty($values[Constants::API_CLIENT_ID_OPTION])
            || empty($values[Constants::API_CLIENT_SECRET_OPTION])
        ) {
            update_option(Constants::BEARER_TOKEN_OPTION, '');
            update_option(Constants::BEARER_TOKEN_EXPIRES_IN_OPTION, 0);

            return;
        }

        /** @var CodesWholesaleService */
        $codesWholesaleService = Container::get(CodesWholesaleService::class);

        $token = $codesWholesaleService->authenticate($values[Constants::API_CLIENT_ID_OPTION], $values[Constants::API_CLIENT_SECRET_OPTION]);
        if (! empty($token) && Arr::has($token, 'access_token')) {
            update_option(Constants::BEARER_TOKEN_OPTION, $token['access_token']);
            update_option(Constants::BEARER_TOKEN_EXPIRES_IN_OPTION, $token['expires_in'] ?? 0);

            $accountDetails = $codesWholesaleService->getAccountDetails($token['access_token']);
            if (empty($accountDetails)) {
                return $this->createToken();
            }

            update_option(Constants::ACCOUNT_DETAILS_OPTION, json_encode($accountDetails));
        }

        if (empty($token) || empty($token['access_token'])) {
            update_option(Constants::BEARER_TOKEN_OPTION, '');
            update_option(Constants::BEARER_TOKEN_EXPIRES_IN_OPTION, 0);
        }
    }
}