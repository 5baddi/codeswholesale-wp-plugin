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

namespace BaddiServices\CodesWholesale\Services\Domains;

use Throwable;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use BaddiServices\CodesWholesale\Constants;
use BaddiServices\CodesWholesale\Exceptions\UnauthorizedException;

/**
 * Class CodesWholesaleService.
 *
 * @category PHP
 *
 * @author   5baddi <project@baddi.info>
 *
 * @link     http://baddi.info
 */
class CodesWholesaleService
{
    public const LIVE_API_URL = 'https://api.codeswholesale.com';
    public const SANDBOX_API_URL = 'https://sandbox.codeswholesale.com';

    public const TOKEN_ENDPOINT = '/oauth/token';
    public const ACCOUNT_DETAILS_ENDPOINT = '/v2/accounts/current';
    public const PRODUCTS_ENDPOINT = '/v2/products';
    public const LANGUAGES_ENDPOINT = '/v2/languages';
    public const REGIONS_ENDPOINT = '/v2/regions';
    public const TERRITORIES_ENDPOINT = '/v2/territory';
    public const PLATFORMS_ENDPOINT = '/v2/platforms';

    /**
     * @var GuzzleHttp\Client
     */
    private $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri'      => self::LIVE_API_URL,
            'debug'         => false,
            'http_errors'   => (defined('WP_DEBUG') && WP_DEBUG === true),
        ]);
    }

    public function authenticate(string $clientId, string $clientSecret): ?array
    {
        try {
            $query = [
                'grant_type'    => Constants::DEFAULT_GRANT_TYPE,
                'client_id'     => $clientId,
                'client_secret' => $clientSecret,
            ];

            $response = $this->client->post(self::TOKEN_ENDPOINT, ['query' => $query]);

            if ($response->getStatusCode() !== 200) {
                // FIXME: throw custom exception
                return null;
            }

            return $this->fromJson($response);
        } catch (Throwable $e) {
            // FIXME: throw custom exception
            return null;
        }
    }

    public function getAccountDetails(string $token): array
    {
        try {
            $response = $this->client->get(
                self::ACCOUNT_DETAILS_ENDPOINT,
                [
                    'headers' => [
                        'Authorization' => sprintf('Bearer %s', $token)
                    ]
                ]
            );

            if ($response->getStatusCode() !== 200) {
                return [];
            }

            return $this->fromJson($response);
        } catch (Throwable $e) {
            return [];
        }
    }

    public function getSupportedProductDescriptionLanguages(string $token): array
    {
        try {
            $response = $this->client->get(
                self::LANGUAGES_ENDPOINT,
                [
                    'headers' => [
                        'Authorization' => sprintf('Bearer %s', $token)
                    ]
                ]
            );

            if ($response->getStatusCode() !== 200) {
                return [];
            }

            return $this->fromJson($response);
        } catch (Throwable $e) {
            return [];
        }
    }

    public function getSupportedRegions(string $token): array
    {
        try {
            $response = $this->client->get(
                self::REGIONS_ENDPOINT,
                [
                    'headers' => [
                        'Authorization' => sprintf('Bearer %s', $token)
                    ]
                ]
            );

            if ($response->getStatusCode() !== 200) {
                return [];
            }

            return $this->fromJson($response);
        } catch (Throwable $e) {
            return [];
        }
    }

    public function getSupportedTerritories(string $token): array
    {
        try {
            $response = $this->client->get(
                self::TERRITORIES_ENDPOINT,
                [
                    'headers' => [
                        'Authorization' => sprintf('Bearer %s', $token)
                    ]
                ]
            );

            if ($response->getStatusCode() !== 200) {
                return [];
            }

            return $this->fromJson($response);
        } catch (Throwable $e) {
            return [];
        }
    }

    public function getSupportedPlatforms(string $token): array
    {
        try {
            $response = $this->client->get(
                self::PLATFORMS_ENDPOINT,
                [
                    'headers' => [
                        'Authorization' => sprintf('Bearer %s', $token)
                    ]
                ]
            );

            if ($response->getStatusCode() !== 200) {
                return [];
            }

            return $this->fromJson($response);
        } catch (Throwable $e) {
            return [];
        }
    }

    public function getProducts(string $token, array $payload = []): array
    {
        try {
            $response = $this->client->get(
                self::PRODUCTS_ENDPOINT,
                [
                    'headers' => [
                        'Authorization' => sprintf('Bearer %s', $token)
                    ],
                    'query' => $payload
                ]
            );

            if ($response->getStatusCode() !== 200) {
                return [];
            }

            return $this->fromJson($response);
        } catch (Throwable $e) {
            if ($e->getCode() === 401) {
                throw new UnauthorizedException();
            }

            return [];
        }
    }

    private function fromJson(?ResponseInterface $response = null): ?array
    {
        if (empty($response)) {
            return [];
        }

        return json_decode($response->getBody() ?? '[]', true);
    }
}
