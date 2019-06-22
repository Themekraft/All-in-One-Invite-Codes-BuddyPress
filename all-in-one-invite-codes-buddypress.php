<?php

/**
 * Plugin Name: All in One Invite Codes BuddyPress
 * Plugin URI:  https://themekraft.com/all-in-one-invite-codes/
 * Description: Create Invite only Forms
 * Version: 1.0
 * Author: ThemeKraft
 * Author URI: https://themekraft.com/
 * Licence: GPLv3
 * Network: false
 * Text Domain: all-in-one-invite-codes
 *
 * ****************************************************************************
 *
 * This script is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.    See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA    02111-1307    USA
 *
 ****************************************************************************
 */

function all_in_one_invite_codes_profile_tab() {
	global $bp;

	bp_core_new_nav_item( array(
		'name'                => __( 'Invite Friends', 'all-in-one-invite-codes' ),
		'slug'                => apply_filters( 'all_in_one_invite_codes_profile_tab_slug', 'all-in-one-invite-codes' ),
		'screen_function'     => 'all_in_one_invite_codes_profile_tab_screen',
		'position'            => 40,
		'parent_url'          => bp_loggedin_user_domain() . '/' . apply_filters( 'all_in_one_invite_codes_profile_tab_slug', 'all-in-one-invite-codes' ) . '/',
		'parent_slug'         => $bp->profile->slug,
		'default_subnav_slug' => apply_filters( 'all_in_one_invite_codes_profile_sub_tab_slug', 'all-in-one-invite-code' ),
	) );
}

add_action( 'bp_setup_nav', 'all_in_one_invite_codes_profile_tab' );


function all_in_one_invite_codes_profile_tab_screen() {
	add_action( 'bp_template_title', 'all_in_one_invite_codes_profile_tab_tab_title' );
	add_action( 'bp_template_content', 'all_in_one_invite_codes_profile_tab_tab_content' );
	bp_core_load_template( 'buddypress/members/single/plugins' );
}

function all_in_one_invite_codes_profile_tab_tab_title() {
	echo __( 'Invite Friends', 'all-in-one-invite-codes' );
}


function all_in_one_invite_codes_profile_tab_tab_content() {
	$args = array(
		'author'         => get_current_user_id(),
		'posts_per_page' => - 1,
		'post_type'      => 'tk_invite_codes', //you can use also 'any'
	);
	all_in_one_invite_codes_list_codes( $args );
}



/**
 * Register Settings Options
 *
 */
function all_in_one_invite_codes_buddypress_register_option() {

	// General Settings
	register_setting( 'all_in_one_invite_codes_buddypress', 'all_in_one_invite_codes_buddypress', 'all_in_one_invite_codes_default_sanitize' );

}

add_action( 'admin_init', 'all_in_one_invite_codes_buddypress_register_option' );




add_filter( 'all_in_one_invite_codes_admin_tabs', 'all_in_one_invite_codes_buddypress_admin_tabs', 2, 10 );

function all_in_one_invite_codes_buddypress_admin_tabs( $tabs ) {

	$tabs['buddypress'] = 'BuddyPress';
	return $tabs;

}

add_action( 'all_in_one_invite_codes_settings_page_tab', 'all_in_one_invite_codes_buddypress_settings_page_tab' );
function all_in_one_invite_codes_buddypress_settings_page_tab( $tab ) {

	if ( $tab != 'buddypress' ) {
		return;
	}

	$all_in_one_invite_codes_buddypress = get_option( 'all_in_one_invite_codes_buddypress' ); ?>
    <div class="metabox-holder">
        <div class="postbox all_in_one_invite_codes-metabox">

            <div class="inside">

                <form method="post" action="options.php">

					<?php settings_fields( 'all_in_one_invite_codes_buddypress' ); ?>


                    <table class="form-table">
                        <tbody>
                        <tr valign="top">
                            <th scope="row" valign="top">
		                        <?php _e( 'Profile Integration', 'all-in-one-invite-codes' ); ?>
                            </th>
                            <td>
		                        <?php
		                        $pages['enabled'] = 'Enable';
		                        $pages['disable'] = 'Disable';

		                        if ( isset( $pages ) && is_array( $pages ) ) {
			                        echo '<select name="all_in_one_invite_codes_buddypress[profile_tab]" id="all_in_one_invite_codes_buddypress">';

			                        foreach ( $pages as $page_id => $page_name ) {
				                        echo '<option ' . selected( $all_in_one_invite_codes_buddypress['profile_tab'], $page_id ) . 'value="' . $page_id . '">' . $page_name . '</option>';
			                        }
			                        echo '</select>';
		                        }
		                        ?>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row" valign="top">
		                        <?php _e( 'BuddyPress Registration', 'all-in-one-invite-codes' ); ?>
                            </th>
                            <td>
		                        <?php
		                        $pages['enabled'] = 'Enable';
		                        $pages['disable'] = 'Disable';

		                        if ( isset( $pages ) && is_array( $pages ) ) {
			                        echo '<select name="all_in_one_invite_codes_buddypress[profile_tab]" id="all_in_one_invite_codes_buddypress">';

			                        foreach ( $pages as $page_id => $page_name ) {
				                        echo '<option ' . selected( $all_in_one_invite_codes_buddypress['profile_tab'], $page_id ) . 'value="' . $page_id . '">' . $page_name . '</option>';
			                        }
			                        echo '</select>';
		                        }
		                        ?>
                            </td>
                        </tr>
                        </tbody>
                    </table>

					<?php submit_button(); ?>

                </form>
            </div><!-- .inside -->
        </div><!-- .postbox -->
    </div><!-- .metabox-holder -->
	<?php


}

add_action('bp_signup_profile_fields', 'all_in_one_invite_codes_bp_after_profile_field_content');
function all_in_one_invite_codes_bp_after_profile_field_content(){

	// Check if the invite code is coming from a link
	$tk_invite_code = ( ! empty( $_GET['invite_code'] ) ) ? sanitize_key( trim( $_GET['invite_code'] ) ) : '';

	?>
    <p>
        <label for="signup_invite_code"><?php _e( 'Invitation Code', 'all-in-one-invite-code' ) ?></label>
        <?php echo do_action('bp_signup_invite_code_errors') ?>
            <input type="text" name="signup_invite_code" id="signup_invite_code" class="input" required="required"
                   value="<?php echo esc_attr( $tk_invite_code ); ?>" size="25"/>
    </p>
	<?php

}


add_filter( 'bp_core_validate_user_signup', 'all_in_one_invite_codes_bp_core_validate_user_signup',  10, 1 );

function all_in_one_invite_codes_bp_core_validate_user_signup( $result ){
    global $errors;


	$errors['sadasd'] = 'asdad';



//	$errors = $result['errors'];
//	$errors->add( 'invite_code', __( 'INVITE CODE ISSE!', 'all-in-one-invite-code' ) );
//	$result['errors'] = $errors;

	return $result;
}

add_action('bp_signup_validate', 'test_bp_signup_validate');

function test_bp_signup_validate(){
    global $bp;

    $bp->signup->errors['signup_invite_code'] = __( 'This is a Invite Code  field', 'buddypress' );
}

