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
use Psr\Http\Message\ResponseInterface;

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

    public function getAccountDetails(): array
    {
        try {
            $response = $this->client->get(self::ACCOUNT_DETAILS_ENDPOINT);

            if ($response->getStatusCode() !== 200) {
                return [];
            }

            return $this->fromJson($response);
        } catch (Throwable $e) {
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