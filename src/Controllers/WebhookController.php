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

namespace BaddiServices\CodesWholesale\Controllers;

use Throwable;
use WP_REST_Server;
use WP_REST_Request;
use WP_HTTP_Response;
use BaddiServices\CodesWholesale\Constants;
use BaddiServices\CodesWholesale\Core\Container;
use BaddiServices\CodesWholesale\Core\BaseController;
use BaddiServices\CodesWholesale\Services\AuthService;
use BaddiServices\CodesWholesale\CodesWholesaleBy5baddi;
use BaddiServices\CodesWholesale\Services\Domains\WooCommerceService;
use BaddiServices\CodesWholesale\Services\Domains\CodesWholesaleService;

/**
 * Class WebhookController.
 *
 * @category PHP
 *
 * @author   5baddi <project@baddi.info>
 *
 * @link     http://baddi.info
 */
class WebhookController extends BaseController
{
    /**
     * @var CodesWholesaleService
     */
    private $codesWholesaleService;

    /**
     * @var WooCommerceService
     */
    private $wooCommerceService;

    /**
     * @var string|null
     */
    private $token;

    public function register_routes()
    {
        register_rest_route(
            CodesWholesaleBy5baddi::NAMESPACE,
            '/v1/webhook',
            [
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => [$this, '__invoke'],
                'permission_callback' => function () {
                    return AuthService::verifyWebhookSignature();
                },
            ]
        );
    }

    public function __invoke(WP_REST_Request $request)
    {
        try {
            $body = json_decode($request->get_body() ?? '{}', true);

            $this->prepare();

            if (empty($this->token)) {
                return new WP_HTTP_Response(null, 400);
            }

            switch ($body['type']) {
                case CodesWholesaleService::STOCK_WEBHOOK_EVENT:
                    $this->updateVirtualProductQuantity($body['productId'], intval($body['quantity']));
                    break;
                case CodesWholesaleService::PREORDER_WEBHOOK_EVENT:
                    // TODO:
                    break;
                case CodesWholesaleService::PRODUCT_HIDDEN_WEBHOOK_EVENT:
                    $this->hideVirtualProduct($body['productId']);
                    break;
                default:
                    $this->saveVirtualProduct($body['productId']);
                    break;
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

    private function hideVirtualProduct(string $productId): void
    {
        $hideProductEnabled = boolval(get_option(Constants::HIDE_PRODUCTS_OPTION, false));
        if (! $hideProductEnabled) {
            return;
        }

        $this->wooCommerceService->hideVirtualProduct($productId);
    }

    private function updateVirtualProductQuantity(string $productId, int $quantity): void
    {
        $product = $this->codesWholesaleService->getProduct($this->token, $productId);
        if (empty($product)) {
            $this->hideVirtualProduct($productId);

            return;
        }

        $product['quantity'] = $quantity;

        $this->wooCommerceService->saveVirtualProduct($product);
    }

    private function saveVirtualProduct(string $productId): void
    {
        $product = $this->codesWholesaleService->getProduct($this->token, $productId);
        if (empty($product)) {
            $this->hideVirtualProduct($productId);

            return;
        }

        $this->wooCommerceService->saveVirtualProduct($product);
    }

    private function prepare()
    {
        $this->codesWholesaleService = Container::get(CodesWholesaleService::class);
        $this->wooCommerceService = Container::get(WooCommerceService::class);
        $this->token = get_option(Constants::BEARER_TOKEN_OPTION, '');

        set_time_limit(300);
    }
}