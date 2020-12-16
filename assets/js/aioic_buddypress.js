jQuery(document).ready(function ($) {

    $( document).on('click','table.aioci-invite-settings .field-actions .field-actions-remove, table.aioci-invite-settings .field-actions-add',this,function(event){
        var currentTarget = event.currentTarget, currentDataTable = $( currentTarget ).closest( 'tbody' );

        if ( $( currentTarget ).hasClass( 'field-actions-remove' ) ) {

            if ( $( this ).closest( 'tr' ).siblings().length > 1 ) {

                $( this ).closest( 'tr' ).remove();
                currentDataTable.find( '.field-actions-add.disabled' ).removeClass( 'disabled' );
            } else {

                return;

            }

        } else if ( $( currentTarget ).hasClass( 'field-actions-add' ) ) {

            if ( ! $( currentTarget ).hasClass( 'disabled' ) ) {

                var prev_data_row = $( this ).closest( 'tr' ).prev( 'tr' ).html();
                $( '<tr>' + prev_data_row + '</tr>' ).insertBefore( $( this ).closest( 'tr' ) );
                currentDataTable.find( 'tr' ).length > 20 ? $( currentTarget ).addClass( 'disabled' ) : ''; // Add Limit of 20

            } else {

                return;

            }

        }

        // reset the id of all inputs
        var data_rows = currentDataTable.find( 'tr:not(:last-child)' );
        $.each(
            data_rows,
            function(index){
                $( this ).find( '.field-name > input' ).attr( 'name','invitee[' + index + '][]' );
                $( this ).find( '.field-name > input' ).attr( 'id','invitee_' + index + '_title' );
                $( this ).find( '.field-email > input' ).attr( 'name','email[' + index + '][]' );
                $( this ).find( '.field-email > input' ).attr( 'id','email_' + index + '_email' );
                $( this ).find( '.field-amount > input' ).attr( 'name','invite_amount[' + index + '][]' );
                $( this ).find( '.field-amount > input' ).attr( 'id','invite_amount_' + index + '_invite_amount' );


            }
        );
    });
    jQuery('#send-invite-aioic').submit(function(event){

        var currentForm = jQuery("#send-invite-aioic");
        var formMessage = jQuery('#form_message_aioic');
        formMessage.removeClass();
        if (jQuery.validator && !currentForm.valid()) {
            return false;
        }
        jQuery("#aioic_form_hero .form_wrapper form").LoadingOverlay("show");
        var FormData = currentForm.serialize();

        jQuery.ajax({
            url: aioicBuddyformsGlobal.admin_url,
            type: 'POST',
            dataType: 'json',
            data: {
                "action": "aioic_send_multiple_invites",
                "data": FormData
            },
            error: function (xhr, status, error) {
                formMessage.addClass('bf-alert error');
                formMessage.html(xhr.responseText);
            },
            success: function (response) {
                jQuery.each(response, function (i, val) {

                    switch (i) {
                        case 'error':
                            formMessage.addClass('bf-alert error');
                            formMessage.html(val);
                            break;
                        case 'message':
                            formMessage.addClass('bf-alert success');
                            formMessage.html(val);
                            break;
                        case 'form_remove':
                            jQuery("#aioic_form_hero .form_wrapper").fadeOut("normal", function () {
                                jQuery("#aioic_form_hero .form_wrapper").remove();
                            });
                            break;
                    };
                    // formMessage.addClass('bf-alert success');
                    // var message =
                    //     formMessage.html(response['message']);
                });



            },
            complete: function () {

                var scrollElement = jQuery('#aioic_form_hero');
                if (scrollElement.length > 0) {
                    jQuery('html, body').animate({scrollTop: scrollElement.offset().top - 100}, {
                        duration: 500, complete: function () {
                            jQuery('html, body').on("click", function () {
                                jQuery('html, body').stop()
                            });
                        }
                    }).one("click", function () {
                        jQuery('html, body').stop()
                    });
                }
                jQuery("#aioic_form_hero .form_wrapper form").LoadingOverlay("hide");

            }



    });
        return false;
});

    if (jQuery && jQuery.validator) {
        jQuery('#send-invite-aioic').validate();
        jQuery.validator.addMethod("aioic-email", function (value, element, param) {

            var msjString = 'Enter a valid email.';
            var currentFieldSlug = jQuery(element).attr('name');
            jQuery.validator.messages['aioic-email'] = msjString;
            return /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(value);
        }, "");
    }
})