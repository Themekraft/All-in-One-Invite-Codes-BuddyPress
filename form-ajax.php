<?php


add_action('wp_ajax_aioic_send_multiple_invites', 'aioic_send_multiple_invites');
function aioic_send_multiple_invites()
{
    $form_data = array();

    if ( isset( $_POST['data'] ) ) {
        parse_str( $_POST['data'], $form_data );
        $_POST = $form_data;
    }
    $json_array = array();

    //Check nonce
    $buddyforms_form_nonce_value = $_POST['_wpnonce'];

    $nonce_result = wp_verify_nonce( $buddyforms_form_nonce_value, 'buddyforms_form_nonce' );

    if ( ! $nonce_result ) {


        header( "Content-type: application/json" );

        echo wp_json_encode( array( "errors" => __( 'Form submit error. Please contact the site administrator.', 'buddyforms' ) ) );
        die;
    }
    $invite_count =0;
    foreach ($form_data['invitee'] as $index=>$value){
        // Get or generate the invite code
        $all_in_one_invite_code = all_in_one_invite_codes_md5();
        $tk_invite_code = sanitize_key( trim( $all_in_one_invite_code ) );
        $user_id = get_current_user_id();
        $args    = array(
            'post_type'   => 'tk_invite_codes',
            'post_author' => $user_id,
            'post_status' => 'publish',
            'post_title'  => $tk_invite_code,
        );
        $invitee = $form_data['invitee'] [$index][0];
        $email                                                 = $form_data['email'] [$index][0];
        $generate_codes                                        = $form_data['invite_amount'] [$index][0];
        $type                                                  =  'any';
        $all_in_one_invite_codes_new_options                   = array();
        $all_in_one_invite_codes_new_options['email']          = sanitize_email( $email );
        $all_in_one_invite_codes_new_options['generate_codes'] = wp_filter_post_kses( $generate_codes );
        $all_in_one_invite_codes_new_options['$type']          = sanitize_text_field( $type );

        $new_code_id = wp_insert_post( $args );
        update_post_meta( $new_code_id, 'tk_all_in_one_invite_code', $tk_invite_code );
        update_post_meta( $new_code_id, 'tk_all_in_one_invite_code_status', 'Active' );
        update_post_meta( $new_code_id, 'all_in_one_invite_codes_options', $all_in_one_invite_codes_new_options );

        if ( ! empty( $email ) ) {
            $all_in_one_invite_codes_mail_templates = get_option( 'all_in_one_invite_codes_mail_templates' );
            $subject     =  empty($form_data['aioic_bp_subject']) ? __("Dear ".$invitee.", You've Been Invited!","all-in-one-invite-code") : sanitize_text_field($form_data['aioic_bp_subject']) ;
            $body        =  empty($form_data['aioic_bp_body']) ?  __("You got an invite from the site [site_name]. Please use this link to register with your invite code [invite_link]","all-in-one-invite-code") :sanitize_text_field($form_data['aioic_bp_body']) ;
            $site_name = get_bloginfo( 'name' );
            $subject   = all_in_one_invite_codes_replace_shortcode( $subject, '[site_name]', $site_name );
            $subject   = all_in_one_invite_codes_replace_shortcode( $subject, '[invite_code]', $tk_invite_code );

            $body      = all_in_one_invite_codes_replace_shortcode( $body, '[site_name]', $site_name );
            $body      = all_in_one_invite_codes_replace_shortcode( $body, '[invite_code]', $tk_invite_code );

            // Invite Link
            $buddypress_active = false;
            if(function_exists('bp_is_active')){
                $buddypress_active = true;
            }
            if ($buddypress_active){
                $invite_link = '<a href="' . wp_registration_url() . '?invite_code=' . $tk_invite_code . '">Link</a>';
            }
            else{
                $invite_link = '<a href="' . wp_registration_url() . '&invite_code=' . $tk_invite_code . '">Link</a>';
            }

            $subject     = all_in_one_invite_codes_replace_shortcode( $subject, '[invite_link]', $invite_link );
            $body        = all_in_one_invite_codes_replace_shortcode( $body, '[invite_link]', $invite_link );

            // sent the mail
            $headers = array( 'Content-Type: text/html; charset=UTF-8' );
            $send    = wp_mail( $email, $subject, $body, $headers );
            // IF something went wrong during the sent message process
            if ( ! $send ) {
                $json['error'] = __( 'Invite : '.$email.'  could not get send. Please contact the Support.', 'all-in-one-invite-code' );
                echo json_encode( $json );
                die();
            }
        }
        $invite_count++;
    }
    $json['form_remove'] = 'true';

    $json['message'] = __( $invite_count. ' Invites sent out successfully', 'all-in-one-invite-code' );;
    echo json_encode( $json );
    die();

}