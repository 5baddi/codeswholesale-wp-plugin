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

use BaddiServices\CodesWholesale\Constants;
use Throwable;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface;
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
    public const GRANT_TYPE = 'client_credentials';
    
    public const SANDBOX_CLIENT_ID = 'ff72ce315d1259e822f47d87d02d261e';
    public const SANDBOX_CLIENT_SECRET = '$2a$10$E2jVWDADFA5gh6zlRVcrlOOX01Q/HJoT6hXuDMJxek.YEo.lkO2T6';

    public const TOKEN_ENDPOINT = '/oauth/token';
    public const ACCOUNT_DETAILS_ENDPOINT = '/v2/accounts/current';
    public const PRODUCTS_ENDPOINT = '/v2/products';
    public const PRODUCT_ENDPOINT = '/v2/products/{productId}';
    public const LANGUAGES_ENDPOINT = '/v2/languages';
    public const REGIONS_ENDPOINT = '/v2/regions';
    public const TERRITORIES_ENDPOINT = '/v2/territory';
    public const PLATFORMS_ENDPOINT = '/v2/platforms';
    public const ORDERS_ENDPOINT = '/v2/orders';
    public const ORDER_INVOICE_ENDPOINT = '/v2/orders/{orderId}/invoice';
    public const SECURITY_ENDPOINT = '/v2/security';

    public const NEW_PRODUCT_WEBHOOK_EVENT = 'NEW_PRODUCT';
    public const PRODUCT_UPDATED_WEBHOOK_EVENT = 'PRODUCT_UPDATED';
    public const PRODUCT_HIDDEN_WEBHOOK_EVENT = 'PRODUCT_HIDDEN';
    public const STOCK_WEBHOOK_EVENT = 'STOCK';
    public const PREORDER_WEBHOOK_EVENT = 'PREORDER';

    public const SUPPORTED_WEBHOOK_EVENTS = [
        self::NEW_PRODUCT_WEBHOOK_EVENT,
        self::PRODUCT_UPDATED_WEBHOOK_EVENT,
        self::PRODUCT_HIDDEN_WEBHOOK_EVENT,
        self::STOCK_WEBHOOK_EVENT,
        self::PREORDER_WEBHOOK_EVENT,
    ];

    /**
     * @var GuzzleHttp\Client
     */
    private $client;

    public function __construct()
    {
        $apiMode = get_option(Constants::API_MODE_OPTION, Constants::API_SANDBOX_MODE);

        $this->client = new Client([
            'base_uri'      => ($apiMode === Constants::API_LIVE_MODE) ? self::LIVE_API_URL : self::SANDBOX_API_URL,
            'debug'         => false,
            'http_errors'   => (defined('WP_DEBUG') && WP_DEBUG === true),
        ]);
    }

    public function authenticate(string $clientId, string $clientSecret): ?array
    {
        try {
            $query = [
                'grant_type'    => CodesWholesaleService::GRANT_TYPE,
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
            if ($e->getCode() === 401) {
                throw new UnauthorizedException('Unauthorized', 401, $e);
            }

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

    public function getOrders(string $token, array $payload = []): array
    {
        try {
            $response = $this->client->get(
                self::ORDERS_ENDPOINT,
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
            return [];
        }
    }

    public function getProduct(string $token, string $productId): array
    {
        try {
            $response = $this->client->get(
                Str::replace('{productId}', $productId, self::PRODUCT_ENDPOINT),
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

    public function getOrderInvoice(string $token, string $orderId): array
    {
        try {
            $response = $this->client->get(
                Str::replace('{orderId}', $orderId, self::ORDER_INVOICE_ENDPOINT),
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

    public function checkCustomerRiskScore(string $token, string $email, string $agent, string $ip): array
    {
        try {
            $response = $this->client->post(
                self::SECURITY_ENDPOINT,
                [
                    'headers' => [
                        'Authorization' => sprintf('Bearer %s', $token)
                    ],
                    'json'    => [
                        'customerEmail'        => $email,
                        'customerPaymentEmail' => $email,
                        'customerUserAgent'    => $agent,
                        'customerIpAddress'    => $ip,
                    ],
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

    public function createOrder(string $token, int $orderId, array $products = [], bool $allowPreOrder = false): array
    {
        try {
            $response = $this->client->post(
                self::ORDERS_ENDPOINT,
                [
                    'headers' => [
                        'Authorization' => sprintf('Bearer %s', $token)
                    ],
                    'json'    => [
                        'orderId'       => $orderId,
                        'products'      => $products,
                        'allowPreOrder' => $allowPreOrder
                    ],
                ]
            );

            if ($response->getStatusCode() !== 200) {
                return [];
            }

            return $this->fromJson($response);
        } catch (Throwable $e) {var_dump($e->getMessage());die();
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
