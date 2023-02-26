jQuery(function () {
    try {
        jQuery('#cws5baddi_linked_product').on('change', function () {
            let productId = jQuery(`#cws5baddi_products option[value='${jQuery(this).val()}']`).data('product-id');

            if (typeof productId !== 'string' || productId.length === 0) {
                return;
            }

            jQuery('input[name=cws5baddi_linked_product]').val(productId);
        });
    } catch (e) {
        if (cws5Baddi.isDebugMode) {
            console.log(e);
        }
    }
});