jQuery(async function () {
    jQuery(`.${cws5Baddi.namespace}-download-invoice`).on('click', async function (event) {
        event.preventDefault();

        let orderId = jQuery(this).data('id');
        let fileName = jQuery(this).data('name') || orderId;
        if (typeof orderId !== 'string' || orderId.length === 0) {
            return Promise.reject();
        }

        fetch(
                `${cws5Baddi.urls.rest}${cws5Baddi.namespace}/v1/orders/invoice`,
                {
                    method: 'POST',
                    headers: {
                        'X-WP-Nonce': cws5Baddi.apiNonce,
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ orderId })
                }
            )
            .then(response => response.blob())
            .then(blob => {
                let url = window.URL.createObjectURL(blob);
                let link = document.createElement('a');

                link.href = url;
                link.download = `${fileName}.pdf`;
                document.body.appendChild(link);
                link.click();
                link.remove();
            });
    });
});