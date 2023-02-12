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
    // try {
    //     let body = {
    //         inStockFor: {{ inStockFor }},
    //         productDescriptionLanguage: '{{ productDescriptionLanguage }}',
    //         region: '{{ region }}',
    //         platform: '{{ platform }}',
    //     };

    //     let response = await fetch(
    //         `{{ urls.rest ~ namespace }}/v1/products/import`,
    //         {
    //             method: 'POST',
    //             headers: {
    //                 'X-WP-Nonce': '{{ apiNonce }}',
    //                 'Content-Type': 'application/json',
    //             },
    //             body: JSON.stringify(body)
    //         }
    //     );

    //     if (! response.ok) {
    //         throw new Error();
    //     }

    //     let products = await response.json();

    //     if (typeof products !== 'undefined' && products.length > 0) {
    //         await cws5baddiImportProducts(products);
    //     } else {
    //         jQuery('#importing-products-loader').hide();
    //         jQuery('.updated').hide();
    //         jQuery('#importing-products-output').text(`{{ 'There\'s no product to import! please try with another criteria...'|translate }}`);

    //         setTimeout(() => {
    //             window.location.reload();
    //         }, 3000);
    //     }


    // } catch (e) {
    //     {% if isDebugMode == true %}
    //     console.log(e);
    //     {% endif %}

    //     jQuery('#importing-products-loader').hide();
    //     jQuery('.updated').hide();
    //     jQuery('#importing-products-output').text(`{{ 'Something going wrong! please try again or contact support...'|translate }}`);
    // }
});