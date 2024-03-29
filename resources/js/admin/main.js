function toggleCredentialsInputs(mode) {
    if (typeof mode === 'string' && mode === 'live') {
        jQuery('input[name=cws5baddi_api_client_id], input[name=cws5baddi_api_client_secret], input[name=cws5baddi_api_client_signature]').prop('readonly', false);
    } else {
        jQuery('input[name=cws5baddi_api_client_id], input[name=cws5baddi_api_client_secret], input[name=cws5baddi_api_client_signature]').prop('readonly', true);
    }
}

function sleep(delay) {
    return new Promise((resolve) => setTimeout(resolve, delay));
}

jQuery(function () {
    toggleCredentialsInputs(jQuery('input[name=cws5baddi_api_mode]:checked').val());

    jQuery('input[name=cws5baddi_api_mode]').on('change', function () {
        toggleCredentialsInputs(jQuery(this).val());
    });
});