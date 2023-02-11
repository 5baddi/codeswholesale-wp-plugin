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
        if (! class_exists('WC_Product_Download')) {
            return false;
        }

        $product = new WC_Product_Simple();
        $product->set_virtual(true);
        $product->set_name($attributes['name']);
        $product->set_slug(Str::slug($attributes['name']));
        $product->set_sku($attributes['identifier']);

        if (Arr::has($attributes, 'quantity')) {
            $product->set_manage_stock(true);
            $product->set_stock_quantity(intval($attributes['quantity']));
        }

        if (Arr::has($attributes, 'images') && Arr::has(last($attributes['images']) ?? [], 'image')) {
            // $attachmentId = WpService::insertImageFromUrlAsAttachment(last($attributes['images'])['image'], $attributes['name']);
            // var_dump($attachmentId);die();
        }

        return $product->save();
    }
}