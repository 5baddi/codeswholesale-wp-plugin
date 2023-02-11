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
use BaddiServices\CodesWholesale\Services\AuthService;
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
        $expiresIn = $values[Constants::BEARER_TOKEN_EXPIRES_IN_OPTION] ?? 0;

        if (
            empty($values[Constants::BEARER_TOKEN_OPTION])
            && ! empty($values[Constants::API_CLIENT_ID_OPTION])
            && ! empty($values[Constants::API_CLIENT_SECRET_OPTION])
            || AuthService::isTokenExpired($expiresIn)
        ) {
            AuthService::createCodesWholesaleToken();
        }
    }

    public function fetchSupportedProductDescriptionLanguages(): void
    {
        $values = $this->settingsValues();
        $expiresIn = $values[Constants::BEARER_TOKEN_EXPIRES_IN_OPTION] ?? 0;

        if (
            empty($values[Constants::SUPPORTED_PRODUCT_DESCRIPTION_LANGUAGES_OPTION])
            && ! empty($values[Constants::BEARER_TOKEN_OPTION])
            && ! AuthService::isTokenExpired($expiresIn)
        ) {
            /** @var CodesWholesaleService */
            $codesWholesaleService = Container::get(CodesWholesaleService::class);

            $languages = $codesWholesaleService->getSupportedProductDescriptionLanguages($values[Constants::BEARER_TOKEN_OPTION]);
            if (empty($languages)) {
                AuthService::createCodesWholesaleToken();

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
        $expiresIn = $values[Constants::BEARER_TOKEN_EXPIRES_IN_OPTION] ?? 0;

        if (
            empty($values[Constants::SUPPORTED_REGIONS_OPTION])
            && ! empty($values[Constants::BEARER_TOKEN_OPTION])
            && ! AuthService::isTokenExpired($expiresIn)
        ) {
            /** @var CodesWholesaleService */
            $codesWholesaleService = Container::get(CodesWholesaleService::class);

            $regions = $codesWholesaleService->getSupportedRegions($values[Constants::BEARER_TOKEN_OPTION]);
            if (empty($regions)) {
                AuthService::createCodesWholesaleToken();

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
        $expiresIn = $values[Constants::BEARER_TOKEN_EXPIRES_IN_OPTION] ?? 0;

        if (
            empty($values[Constants::SUPPORTED_TERRITORIES_OPTION])
            && ! empty($values[Constants::BEARER_TOKEN_OPTION])
            && ! AuthService::isTokenExpired($expiresIn)
        ) {
            /** @var CodesWholesaleService */
            $codesWholesaleService = Container::get(CodesWholesaleService::class);

            $territories = $codesWholesaleService->getSupportedTerritories($values[Constants::BEARER_TOKEN_OPTION]);
            if (empty($territories)) {
                AuthService::createCodesWholesaleToken();

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
        $expiresIn = $values[Constants::BEARER_TOKEN_EXPIRES_IN_OPTION] ?? 0;

        if (
            empty($values[Constants::SUPPORTED_PLATFORMS_OPTION])
            && ! empty($values[Constants::BEARER_TOKEN_OPTION])
            && ! AuthService::isTokenExpired($expiresIn)
        ) {
            /** @var CodesWholesaleService */
            $codesWholesaleService = Container::get(CodesWholesaleService::class);

            $platforms = $codesWholesaleService->getSupportedPlatforms($values[Constants::BEARER_TOKEN_OPTION]);
            if (empty($platforms)) {
                AuthService::createCodesWholesaleToken();

                $platforms = $codesWholesaleService->getSupportedPlatforms($values[Constants::BEARER_TOKEN_OPTION]);
            }

            update_option(
                Constants::SUPPORTED_PLATFORMS_OPTION,
                json_encode(array_column($platforms['platforms'] ?? [], 'name'))
            );
        }
    }
}