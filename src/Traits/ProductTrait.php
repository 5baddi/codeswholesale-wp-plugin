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

use WP_Post;
use Throwable;
use WC_Product_Simple;
use Illuminate\Support\Arr;
use BaddiServices\CodesWholesale\Logger;
use BaddiServices\CodesWholesale\Constants;
use BaddiServices\CodesWholesale\Core\Container;
use BaddiServices\CodesWholesale\Models\Product;
use BaddiServices\CodesWholesale\Services\Domains\WooCommerceService;
use BaddiServices\CodesWholesale\Services\Domains\CodesWholesaleService;

/**
 * Trait ProductTrait.
 *
 * @category PHP
 *
 * @author   5baddi <project@baddi.info>
 *
 * @link     http://baddi.info
 */
trait ProductTrait
{
    public function doubleCheckProductPrice(WP_Post $post): WP_Post
    {
        if (! is_singular('product')) {
            // Prevent infinite loop
            remove_action('the_post', [$this, 'doubleCheckProductPrice'], 1);

            return $post;
        }

        try {
            $product = null;
            $cwsProductId = null;
            $cwsProduct = null;
            $doubleCheckPriceEnabled = boolval(get_option(Constants::DOUBLE_CHECK_PRICE_OPTION, 0));
            $token = get_option(Constants::BEARER_TOKEN_OPTION, '');

            if ($doubleCheckPriceEnabled && ! empty($token)) {
                $product = wc_get_product($post->ID);
            }

            if ($product instanceof WC_Product_Simple) {
                $cwsProductId = $product->get_meta('cws_product_uuid', true);
            }

            if (! empty($cwsProductId)) {
                /** @var CodesWholesaleService */
                $codesWholesaleService = Container::get(CodesWholesaleService::class);

                $cwsProduct = $codesWholesaleService->getProduct($token, $cwsProductId);
            }

            if (! empty($cwsProduct) && Arr::has($cwsProduct, 'prices.0.value')) {
                $currentPrice = $product->get_regular_price() ?? 0;
                $newPrice = WooCommerceService::calculatePriceWithProfit(Arr::get($cwsProduct, 'prices.0.value', 0));

                if (empty($product->get_meta(Product::PRICE_META_DATA)) || $currentPrice < $newPrice) {
                    $product->set_regular_price($newPrice);
                    $product->add_meta_data(Product::PRICE_META_DATA, Arr::get($cwsProduct, 'prices.0.value', 0), true);
                    $product->save();
                }
            }
        } catch (Throwable $e) {
            Logger::trace($e);
        }

        // Prevent infinite loop
        remove_action('the_post', [$this, 'doubleCheckProductPrice'], 1);

        return $post;
    }
}