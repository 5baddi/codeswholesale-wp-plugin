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

namespace BaddiServices\CodesWholesale\Services;

use Illuminate\Support\Arr;
use BaddiServices\CodesWholesale\Constants;
use BaddiServices\CodesWholesale\Core\Container;
use BaddiServices\CodesWholesale\Services\Domains\CodesWholesaleService;

/**
 * Class AuthService.
 *
 * @category PHP
 *
 * @author   5baddi <project@baddi.info>
 *
 * @link     http://baddi.info
 */
class AuthService
{
    public static function isTokenExpired(int $expiresIn): bool
    {
        $now = time();
        $expiresIn += $now;

        return ($expiresIn <= $now);
    }

    public static function createCodesWholesaleToken()
    {
        $apiClientId = get_option(Constants::API_CLIENT_ID_OPTION, '');
        $apiClientSecret = get_option(Constants::API_CLIENT_SECRET_OPTION, '');

        if (
            empty($apiClientId)
            || empty($apiClientSecret)
        ) {
            update_option(Constants::BEARER_TOKEN_OPTION, '');
            update_option(Constants::BEARER_TOKEN_EXPIRES_IN_OPTION, 0);

            return;
        }

        /** @var CodesWholesaleService */
        $codesWholesaleService = Container::get(CodesWholesaleService::class);

        $token = $codesWholesaleService->authenticate($apiClientId, $apiClientSecret);
        if (! empty($token) && Arr::has($token, 'access_token')) {
            update_option(Constants::BEARER_TOKEN_OPTION, $token['access_token']);
            update_option(Constants::BEARER_TOKEN_EXPIRES_IN_OPTION, $token['expires_in'] ?? 0);

            $accountDetails = $codesWholesaleService->getAccountDetails($token['access_token']);
            if (empty($accountDetails)) {
                return self::createCodesWholesaleToken();
            }

            update_option(Constants::ACCOUNT_DETAILS_OPTION, json_encode($accountDetails));
        }

        if (empty($token) || empty($token['access_token'])) {
            update_option(Constants::BEARER_TOKEN_OPTION, '');
            update_option(Constants::BEARER_TOKEN_EXPIRES_IN_OPTION, 0);
        }
    }
}