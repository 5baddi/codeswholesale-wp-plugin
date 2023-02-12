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

use WC_Product_Simple;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use BaddiServices\CodesWholesale\Constants;

/**
 * Class WooCommerceService.
 *
 * @category PHP
 *
 * @author   5baddi <project@baddi.info>
 *
 * @link     http://baddi.info
 */
class WooCommerceService
{
    public function saveVirtualProduct(array $attributes): bool
    {
        if (! class_exists('WC_Product_Simple')) {
            return false;
        }

        $name = sanitize_text_field($attributes['name']);
        $product = new WC_Product_Simple();
        $existsProduct = null;

        $productId = wc_get_product_id_by_sku($attributes['identifier']);
        if (! empty($productId)) {
            $existsProduct = wc_get_product($productId);
        }

        if ($existsProduct instanceof WC_Product_Simple) {
            $product = $existsProduct;
        }

        $product->set_virtual(true);
        $product->set_name($name);
        $product->set_slug(Str::lower(Str::slug($name)));
        $product->set_sku(sanitize_text_field($attributes['identifier']));
        $product->add_meta_data('uuid', sanitize_text_field($attributes['productId']));

        if (! empty($attributes['quantity'])) {
            $this->setQuantity($product, $attributes['quantity']);
        }

        if (Arr::has($attributes, 'prices') && ! empty($attributes['prices'][0])) {
            $this->setPrice($product, $attributes['prices'][0]);
        }

        if (! empty($attributes['platform'])) {
            $this->setCategoriesByName($product, [$attributes['platform']]);
        }

        if (Arr::has($attributes, 'languages') && is_array($attributes['languages'])) {
            $this->setTagsByName($product, $attributes['languages']);
        }

        if (Arr::has($attributes, 'regions') && is_array($attributes['regions'])) {
            $this->setTagsByName($product, $attributes['regions']);
        }

        if (! empty($attributes['image'])) {
            $attachmentId = WpService::insertImageFromUrlAsAttachment(sanitize_url($attributes['image']));

            if (! empty($attachmentId)) {
                $product->set_image_id($attachmentId);
            }
        }

        return $product->save();
    }

    public function hideVirtualProduct(string $productId): bool
    {
        if (! class_exists('WC_Product_Simple')) {
            return false;
        }

        return wp_update_post(['ID' => $productId, 'post_status' => 'pending']);
    }

    private function setQuantity(WC_Product_Simple $product, int $quantity): void
    {
        $product->set_manage_stock(true);
        $product->set_stock_quantity(intval($quantity));
        $product->set_stock_status($quantity > 0 ? 'instock' : 'outofstock');
    }

    private function setPrice(WC_Product_Simple $product, array $price): void
    {
        if (empty($price['from']) || $price['from'] === 1) {
            $product->set_sold_individually(true);
        }

        $value = $price['value'] ?? 0;
        $priceMargin = get_option(Constants::PROFIT_MARGIN_VALUE_OPTION, Constants::DEFAULT_PROFIT_MARGIN_VALUE);
        $priceMarginType = intval(get_option(Constants::PROFIT_MARGIN_TYPE_OPTION, Constants::DEFAULT_PROFIT_MARGIN_TYPE));

        if ($priceMarginType === Constants::PROFIT_MARGIN_AMOUNT) {
            $value += $priceMargin;
        }

        if ($priceMarginType === Constants::PROFIT_MARGIN_PERCENTAGE) {
            $priceMargin = $value * ($priceMargin / 100);
            $value += $priceMargin;
        }

        $product->set_regular_price($value);
    }

    private function setCategoriesByName(WC_Product_Simple $product, array $categoriesNames = []): void
    {
        if (empty($categoriesNames)) {
            return;
        }

        $categoriesIds = $product->get_tag_ids();

        foreach ($categoriesNames as $categoryName) {
            $categoryName = sanitize_text_field($categoryName);
            $categorySlug = Str::lower(Str::slug($categoryName));
            $category = get_term_by('slug', $categorySlug, 'product_cat');

            if (empty($category)) {
                $category = wp_insert_term(
                    $categoryName,
                    'product_cat',
                    [
                        'slug'  => $categorySlug
                    ]
                );
            }

            if (empty($category) || ! property_exists($category, 'term_id')) {
                continue;
            }

            $categoriesIds[] = $category->term_id;
        }

        $product->set_category_ids(array_unique($categoriesIds));
    }

    private function setTagsByName(WC_Product_Simple $product, array $tagsNames = []): void
    {
        if (empty($tagsNames)) {
            return;
        }

        $tagsIds = $product->get_tag_ids();

        foreach ($tagsNames as $tagName) {
            $tagName = sanitize_text_field($tagName);
            $tagSlug = Str::lower(Str::slug($tagName));
            $tag = get_term_by('slug', $tagSlug, 'product_tag');

            if (empty($tag)) {
                $tag = wp_insert_term(
                    $tagName,
                    'product_tag',
                    [
                        'slug'  => $tagSlug
                    ]
                );
            }

            if (empty($tag) || ! property_exists($tag, 'term_id')) {
                continue;
            }

            $tagsIds[] = $tag->term_id;
        }

        $product->set_tag_ids(array_unique($tagsIds));
    }
}