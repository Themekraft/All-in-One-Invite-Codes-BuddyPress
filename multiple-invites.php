<?php
$all_in_one_invite_codes_mail_templates = get_option( 'all_in_one_invite_codes_mail_templates' )
 ?>
<style>

    .bf-alert.success {
        color: #3c763d;
        background-color: #dff0d8;
        border-color: #d6e9c6;}
    .bf-alert {
        display: block;
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid rgba(0,0,0,0.1);
        background-color: #fff;
    }
    .bf-alert.error {
        background-color: #f2dede;
        color: #a94442;
        border-color: #ebccd1;
    }
    #send-invite-aioic input.error {
        border: 1px solid red;
    }
    #send-invite-aioic label.error {
        color: red;
    }


</style>
    <div id="aioic_form_hero" class="the_buddyforms_form ">
    <div class="" id="form_message_aioic"></div>
    <div class="form_wrapper clearfix">
    <h2 class="screen-heading general-settings-screen">
        <?php _e( 'Send Invites', 'all_in_one_invite_codes-buddypress' ); ?>
    </h2>

    <p class="info invite-info">
        <?php _e( 'Invite non-members to create an account. They will receive an email with a link to register.', 'all_in_one_invite_codes-buddypress' ); ?>
    </p>
    <form action="<?php echo esc_url( bp_displayed_user_domain() . '/all-in-one-invite-codes/multiple-invites/' ); ?>" method="post"  class="" id="send-invite-aioic">
        <input type="hidden" name="action" value="aioic_send_multiple_invites">
       <?php wp_nonce_field( 'buddyforms_form_nonce', '_wpnonce', true, true ) ;?>
        <table class="aioci-invite-settings bp-tables-user" id="<?php echo esc_attr( 'member-invites-table' ); ?>">
            <thead>
            <tr>
                <th class="title"><?php esc_html_e( 'Recipient Name', 'all_in_one_invite_codes-buddypress' ); ?></th>
                <th class="title"><?php esc_html_e( 'Recipient Email', 'all_in_one_invite_codes-buddypress' ); ?></th>
                <th class="title"><?php esc_html_e( 'Invites Amount', 'all_in_one_invite_codes-buddypress' ); ?></th>

                <th class="title actions"></th>
            </tr>
            </thead>

            <tbody>
            <?php
            $raw = apply_filters( 'bp_invites_member_default_invitation_raw', 1 );
            for ( $i = 0; $i < $raw; $i++ ) {
                ?>


                <tr>
                    <td class="field-name">
                        <input required="true" type="text" name="invitee[<?php echo $i; ?>][]" id="invitee_<?php echo $i; ?>_title"  class="invites-input"/>
                    </td>
                    <td class="field-email">
                        <input required="true" type="email" data-rule-aioic-email="true" name="email[<?php echo $i; ?>][]" id="email_<?php echo $i; ?>_email" value="<?php echo esc_attr( '' ); ?>" class="invites-input" <?php bp_form_field_attributes( 'email' ); ?>/>
                    </td>
                    <td class="field-amount">
                        <input required="true"type="number" name="invite_amount[<?php echo $i; ?>][]" id="invite_amount_<?php echo $i; ?>_invite_amount" value="<?php echo esc_attr( '' ); ?>" class="invites-input" <?php bp_form_field_attributes( 'email' ); ?>/>
                    </td>

                    <td class="field-actions">
                        <span class="field-actions-remove"><i class="bb-icon bb-icon-close"></i></span>
                    </td>
                </tr>

            <?php }; ?>
            <tr>
                <td class="field-name" colspan="3">
                </td>
                <td class="field-actions-last" colspan="">
                    <span class="field-actions-add"><i class="bb-icon bb-icon-plus"></i></span>
                </td>
            </tr>

            </tbody>
        </table>

        <div id="tk_all_in_one_invite_code_send_invite_form">

            <p>Subject: <input type="text" id="tk_all_in_one_invite_code_send_invite_subject" name="aioic_bp_subject"
                               value="<?php echo empty( $all_in_one_invite_codes_mail_templates['subject'] ) ? '' : $all_in_one_invite_codes_mail_templates['subject']; ?>">
            </p>
            <p>Message Text:<textarea cols="70" rows="5"
                                      id="tk_all_in_one_invite_code_send_invite_message_text"
                                      name="aioic_bp_body">
                    <?php echo empty( $all_in_one_invite_codes_mail_templates['message_text'] ) ? '' : $all_in_one_invite_codes_mail_templates['message_text']; ?></textarea>
            </p>
            <input type="submit" name="aioic-invite-submit" id="submit" value="Send Invites" class="aioic_submit">
        </div>




    </form>
    </div>
    </div>




