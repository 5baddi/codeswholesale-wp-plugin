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
        $values = $this->settingsPageValues();
        $now = time();
        $expiresIn = $now + ($values[Constants::BEARER_TOKEN_EXPIRES_IN_OPTION] ?? 0);

        if (
            empty($values[Constants::BEARER_TOKEN_OPTION])
            && ! empty($values[Constants::API_CLIENT_ID_OPTION])
            && ! empty($values[Constants::API_CLIENT_SECRET_OPTION])
            && $expiresIn <= $now
        ) {
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
}