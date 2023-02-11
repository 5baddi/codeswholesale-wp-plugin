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

namespace BaddiServices\CodesWholesale\Controllers\Products;

use Throwable;
use WP_REST_Server;
use WP_REST_Request;
use WP_HTTP_Response;
use Illuminate\Support\Arr;
use BaddiServices\CodesWholesale\Constants;
use BaddiServices\CodesWholesale\Core\Container;
use BaddiServices\CodesWholesale\Core\BaseController;
use BaddiServices\CodesWholesale\Services\AuthService;
use BaddiServices\CodesWholesale\CodesWholesaleBy5baddi;
use BaddiServices\CodesWholesale\Exceptions\UnauthorizedException;
use BaddiServices\CodesWholesale\Services\Domains\WooCommerceService;
use BaddiServices\CodesWholesale\Services\Domains\CodesWholesaleService;

/**
 * Class ImportProductsController.
 *
 * @category PHP
 *
 * @author   5baddi <project@baddi.info>
 *
 * @link     http://baddi.info
 */
class ImportProductsController extends BaseController
{
    public function register_routes()
    {
        register_rest_route(
            CodesWholesaleBy5baddi::NAMESPACE,
            '/products/virtual',
            [
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => [$this, 'storeVirtualProduct'],
                'permission_callback' => function () {
                    return current_user_can('publish_posts');
                },
            ]
        );

        register_rest_route(
            CodesWholesaleBy5baddi::NAMESPACE,
            '/products/import',
            [
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => [$this, 'fetchProducts'],
                'permission_callback' => function () {
                    return current_user_can('publish_posts');
                },
            ]
        );
    }

    public function storeVirtualProduct(WP_REST_Request $request)
    {
        try {
            $body = json_decode($request->get_body() ?? '{}', true);

            if (! Arr::has($body, ['productId', 'name', 'identifier'])) {
                return new WP_HTTP_Response(
                    [
                        'success'   => false,
                        'message'   => cws5baddiTranslation('Product ID, name and identifier fields are required!'),
                    ],
                    422
                );
            }

            /** @var WooCommerceService */
            $wooCommerceService = Container::get(WooCommerceService::class);

            set_time_limit(300);

            $product = $wooCommerceService->saveVirtualProduct($body);
            if (! $product) {
                return new WP_HTTP_Response(
                    [
                        'success'   => false,
                        'message'   => cws5baddiTranslation('Failed to store the product!'),
                    ],
                    400
                );
            }

            return new WP_HTTP_Response();
        } catch (Throwable $e) {
            return new WP_HTTP_Response(
                [
                    'success'   => false,
                    'message'   => cws5baddiTranslation('Something going wrong!'),
                    'error'     => $e->getMessage()
                ],
                500
            );
        }
    }

    public function fetchProducts(WP_REST_Request $request)
    {
        try {
            $body = json_decode($request->get_body() ?? '{}', true);
            $payload = [];
            $token = get_option(Constants::BEARER_TOKEN_OPTION, '');
            $expiresIn = get_option(Constants::BEARER_TOKEN_EXPIRES_IN_OPTION, 0);

            if (empty($token) || AuthService::isTokenExpired($expiresIn)) {
                return new WP_HTTP_Response(
                    [
                        'success'   => false,
                        'message'   => cws5baddiTranslation('API connection failed! please check your API credentials...'),
                    ],
                    401
                );
            }

            if (Arr::has($body, 'inStockFor')) {
                $payload['inStockDaysAgo'] = intval($body['inStockFor']);
            }

            if (Arr::has($body, 'productDescriptionLanguage')) {
                $payload['language'] = sanitize_text_field($body['productDescriptionLanguage']);
            }

            if (Arr::has($body, 'region')) {
                $payload['region'] = sanitize_text_field($body['region']);
            }

            if (Arr::has($body, 'platform')) {
                $payload['platform'] = sanitize_text_field($body['platform']);
            }

            /** @var CodesWholesaleService */
            $codesWholesaleService = Container::get(CodesWholesaleService::class);

            set_time_limit(600);

            try {
                $products = $codesWholesaleService->getProducts($token, $payload);
            } catch (UnauthorizedException $e) {
                AuthService::createCodesWholesaleToken();

                $products = $codesWholesaleService->getProducts($token, $payload);
            }

            return new WP_HTTP_Response($products['items'] ?? []);
        } catch (Throwable $e) {
            return new WP_HTTP_Response(
                [
                    'success'   => false,
                    'message'   => cws5baddiTranslation('Something going wrong!'),
                    'error'     => $e->getMessage()
                ],
                500
            );
        }
    }
}