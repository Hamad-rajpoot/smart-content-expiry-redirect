jQuery(function ($) {

    function scer_toggle_fields() {
        let action = $('#scer_action_after_expiry').val();

        // Hide All Groups First
        $('#scer_redirect_url_group').hide();
        $('#scer_replace_content_group').hide();
        $('#scer_status_change_group').hide();

        // Show Based on Action
        if (action === 'replace') {
            $('#scer_replace_content_group').show();
        } else if (action === 'redirect') {
            $('#scer_redirect_url_group').show();
        } else if (action === 'status_change') {
            $('#scer_status_change_group').show();
        }
    }

    // On Load
    scer_toggle_fields();

    // On Change
    $(document).on('change', '#scer_action_after_expiry', function () {
        scer_toggle_fields();
    });

});
