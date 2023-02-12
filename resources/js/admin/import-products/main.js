async function cws5baddiImportProducts(products) {
    for (const product of products) {
        await fetch(
            `${cws5Baddi.urls.rest}${cws5Baddi.namespace}/v1/products/virtual`,
            {
                method: 'POST',
                headers: {
                    'X-WP-Nonce': cws5Baddi.apiNonce,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(product)
            }
        );

        await sleep(1000);
    }

    setTimeout(() => {
        window.location.replace(cws5Baddi.urls.wooProducts);
    }, 1000);
}

jQuery(async function () {
    try {
        let body = {
            inStockFor: cws5Baddi.inStockFor,
            productDescriptionLanguage: cws5Baddi.productDescriptionLanguage,
            region: cws5Baddi.region,
            platform: cws5Baddi.platform,
        };

        let response = await fetch(
            `${cws5Baddi.urls.rest}${cws5Baddi.namespace}/v1/products/import`,
            {
                method: 'POST',
                headers: {
                    'X-WP-Nonce': cws5Baddi.apiNonce,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(body)
            }
        );

        if (! response.ok) {
            throw new Error();
        }

        let products = await response.json();

        if (typeof products !== 'undefined' && products.length > 0) {
            await cws5baddiImportProducts(products);
        } else {
            jQuery('#importing-products-loader').hide();
            jQuery('.updated').hide();
            jQuery('#importing-products-output').text(cws5Baddi.translations.no_product_to_import || '');

            setTimeout(() => {
                window.location.reload();
            }, 3000);
        }


    } catch (e) {
        if (cws5Baddi.isDebugMode) {
            console.log(e);
        }

        jQuery('#importing-products-loader').hide();
        jQuery('.updated').hide();
        jQuery('#importing-products-output').text(cws5Baddi.translations.error || '');
    }
});