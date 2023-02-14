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

namespace BaddiServices\CodesWholesale\Controllers\Orders;

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
 * Class OrdersController.
 *
 * @category PHP
 *
 * @author   5baddi <project@baddi.info>
 *
 * @link     http://baddi.info
 */
class OrdersController extends BaseController
{
    public function register_routes()
    {
        register_rest_route(
            CodesWholesaleBy5baddi::NAMESPACE,
            '/v1/orders/invoice',
            [
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => [$this, 'downloadOrderInvoice'],
                'permission_callback' => function () {
                    return current_user_can('publish_posts');
                },
            ]
        );
    }

    public function downloadOrderInvoice(WP_REST_Request $request)
    {
        try {
            $body = json_decode($request->get_body() ?? '{}', true);

            if (! Arr::has($body, ['orderId'])) {
                return new WP_HTTP_Response(
                    [
                        'success'   => false,
                        'message'   => cws5baddiTranslation('Order ID is required!'),
                    ],
                    422
                );
            }

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

            /** @var CodesWholesaleService */
            $codesWholesaleService = Container::get(CodesWholesaleService::class);

            $orderInvoice = $codesWholesaleService->getOrderInvoice($token, $body['orderId']);
            if (empty($orderInvoice) || ! Arr::has($orderInvoice, ['body'])) {
                return new WP_HTTP_Response(
                    [
                        'success'   => false,
                        'message'   => cws5baddiTranslation('Order invoice not found!'),
                    ],
                    404
                );

                return;
            }
            
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment;filename="%s-invoice.pdf"', $orderInvoice['invoiceNo'] ?? time());

            echo base64_decode($orderInvoice['body']);
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