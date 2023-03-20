<?php

/**
 * Plugin Name: All in One Invite Codes BuddyPress
 * Plugin URI: https://themekraft.com/products/all-in-one-invite-codes-buddypress/
 * Description: Create Invite only Forms
 * Version: 1.0.6
 * Author: ThemeKraft
 * Author URI: https://themekraft.com/
 * Licence: GPLv3
 * Network: false
 * Text Domain: all-in-one-invite-codes-buddypress
 * Domain Path: /languages
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
add_action('wp_enqueue_scripts', 'aioic_buddypress_scripts', 100, 1);
require_once('aioic-invites-widget.php');
add_action('widgets_init', 'aioic_register_widget');
add_action( 'init', 'load_plugin_textdomain_aioic_buddypress' ) ;
function aioic_register_widget()
{
    register_widget('Aioic_Invites_Widget');
}

/**
		 * Load the textdomain for the plugin
		 *
		 * @package all_in_one_invite_codes
		 * @since  0.1
		 */
function load_plugin_textdomain_aioic_buddypress() {

	load_plugin_textdomain( 'all-in-one-invite-codes-buddypress', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

function aioic_buddypress_scripts()
{
    wp_enqueue_style('aioic_style', plugins_url('/', __FILE__) . 'assets/css/main.css', array(), false, false);
    wp_enqueue_style('jquery-ui-styles', plugins_url('/', __FILE__) . 'assets/css/jquery-ui.css', array(), false, false);
    wp_enqueue_script('aioic_buddypress',  plugins_url('/', __FILE__) . 'assets/js/aioic_buddypress.js', array('jquery', 'bp-api-request'), false, true);
    wp_enqueue_style('aioic_bb_icons', plugins_url('/', __FILE__) . 'assets/icons/aioic-bb-icons.css', array(), false, false);
    wp_enqueue_script('buddyforms-loadingoverlay', plugins_url('/', __FILE__) . 'assets/loadingoverlay/loadingoverlay.min.js', array('jquery'));
    wp_enqueue_script('jquery-validation', plugins_url('/', __FILE__) . 'assets/js/jquery.validate.js', array('jquery'));
    wp_enqueue_script('jquery-ui', plugins_url('/', __FILE__) . 'assets/js/jquery-ui.js', array('jquery'));

    $front_js_arguments = array(
        'admin_url'                => admin_url('admin-ajax.php'),

    );
    wp_localize_script("aioic_buddypress", "aioicBuddyformsGlobal", $front_js_arguments);
}
function aioic_buddypress_load_plugin_textdomain()
{
    
    require_once('form-ajax.php');
}
add_action('init', 'aioic_buddypress_load_plugin_textdomain');

function all_in_one_invite_codes_profile_tab()
{
    global $bp;


    bp_core_new_nav_item(array(
        'name'                => __('Invite Friends', 'all-in-one-invite-codes-buddypress'),
        'slug'                => apply_filters('all_in_one_invite_codes_profile_tab_slug', 'all-in-one-invite-codes'),
        'screen_function'     => 'all_in_one_invite_codes_profile_tab_screen',
        'position'            => 40,
        'parent_url'          => bp_loggedin_user_domain() . '/' . apply_filters('all_in_one_invite_codes_profile_tab_slug', 'all-in-one-invite-codes') . '/',
        'parent_slug'         => $bp->profile->slug,
        'default_subnav_slug' => apply_filters('all_in_one_invite_codes_profile_tab_slug1', 'all-in-one-invite-codes'),
    ));
    $access       = bp_core_can_edit_settings();


    bp_core_new_subnav_item(array(
        'name'            => __('Single Invites ', 'all-in-one-invite-codes-buddypress'),
        'slug'            => 'single-invites',
        'parent_url'      => bp_loggedin_user_domain() . '/' . apply_filters('all_in_one_invite_codes_profile_tab_slug', 'all-in-one-invite-codes') . '/',
        'parent_slug'     => apply_filters('all_in_one_invite_codes_profile_tab_slug', 'all-in-one-invite-codes'),
        'screen_function' => 'all_in_one_invite_codes_profile_tab_screen',
        'user_has_access' => $access,
        'position'        => 15,
    ));
    bp_core_new_subnav_item(array(
        'name'            => __('Multiple Invites ', 'all-in-one-invite-codes-buddypress'),
        'slug'            => 'multiple-invites',
        'parent_url'      => bp_loggedin_user_domain() . '/' . apply_filters('all_in_one_invite_codes_profile_tab_slug', 'all-in-one-invite-codes') . '/',
        'parent_slug'     => apply_filters('all_in_one_invite_codes_profile_tab_slug', 'all-in-one-invite-codes'),
        'screen_function' => 'all_in_one_invite_codes_profile_tab_multiple_invite_screen',
        'user_has_access' => $access,
        'position'        => 21,
    ));
    bp_core_new_subnav_item(array(
        'name'            => __('Sent Invites ', 'all-in-one-invite-codes-buddypress'),
        'slug'            => 'aioic_sent-invites',
        'parent_url'      => bp_loggedin_user_domain() . '/' . apply_filters('all_in_one_invite_codes_profile_tab_slug', 'all-in-one-invite-codes') . '/',
        'parent_slug'     => apply_filters('all_in_one_invite_codes_profile_tab_slug', 'all-in-one-invite-codes'),
        'screen_function' => 'all_in_one_invite_codes_profile_tab_aioic_sent_invites_screen',
        'user_has_access' => $access,
        'position'        => 25,
    ));
}

$all_in_one_invite_codes_buddypress_options = get_option('all_in_one_invite_codes_buddypress');
$invite_friends_restricted =  isset($all_in_one_invite_codes_buddypress_options['restrict_invite_friends']) ? $all_in_one_invite_codes_buddypress_options['restrict_invite_friends'] === 'enabled' ? true : false : false;
if($invite_friends_restricted ==false){
    add_action('bp_setup_nav', 'all_in_one_invite_codes_profile_tab');
}



function all_in_one_invite_codes_profile_tab_aioic_sent_invites_screen()
{
    add_action('bp_template_title', 'all_in_one_invite_codes_profile_tab_tab_title');
    add_action('bp_template_content', 'all_in_one_invite_codes_profile_tab_aioic_sent_invite_content');
    bp_core_load_template('buddypress/members/single/plugins');
}
function all_in_one_invite_codes_profile_tab_screen()
{
    add_action('bp_template_title', 'all_in_one_invite_codes_profile_tab_tab_title');
    add_action('bp_template_content', 'all_in_one_invite_codes_profile_tab_tab_content');
    bp_core_load_template('buddypress/members/single/plugins');
}
function all_in_one_invite_codes_profile_tab_multiple_invite_screen()
{
    add_action('bp_template_title', 'all_in_one_invite_codes_profile_tab_tab_title');
    add_action('bp_template_content', 'all_in_one_invite_codes_profile_tab_multiple_invite_content');
    bp_core_load_template('buddypress/members/single/plugins');
}

function all_in_one_invite_codes_profile_tab_tab_title()
{
    echo ''; //__( 'Invite Friends', 'all-in-one-invite-codes' );
}
function all_in_one_invite_codes_profile_tab_aioic_sent_invite_content()
{


    require 'aioic-sent-invites.php';
}

function all_in_one_invite_codes_profile_tab_multiple_invite_content()
{


    require 'multiple-invites.php';
}
function all_in_one_invite_codes_profile_tab_tab_content()
{
    global $post;
    $post_id = '';
    $args = array(
        'author'         => get_current_user_id(),
        'posts_per_page' => -1,
        'post_type'      => 'tk_invite_codes', //you can use also 'any'
    );
    AllinOneInviteCodes::setNeedAssets(true, 'buddypress');
    $all_in_one_invite_codes_buddypress = get_option('all_in_one_invite_codes_buddypress');
    $create_invites_restricted =  isset($all_in_one_invite_codes_buddypress['restrict_create_invites']) ? $all_in_one_invite_codes_buddypress['restrict_create_invites'] === 'enabled' ? true : false : false;
    ?>

    <?php if ($create_invites_restricted == false) : ?>

        <p>
            <a id="all_in_one_invite_codes_profile" href="#TB_inline?width=500&height=auto&inlineId=all_in_one_invite_codes_profile_modal" title="" class="thickbox button"><?php _e('Create Invite', 'all-in-one-invite-codes-buddypress') ?></a>
        </p>

        <div id="all_in_one_invite_codes_profile_modal" style="display:none;">
            <div id="buddyforms_invite_wrap">
                <?php do_shortcode('[all_in_one_invite_codes_create]'); ?>
                <button id="tk_all_in_one_invite_code_buddypress_create" data-post_id="<?php echo $post_id ?>" href="#" class="button"><?php echo __('Create Invite', 'all-in-one-invite-codes-buddypress') ?></button>
                <img width="30" height="30" src="<?php echo plugin_dir_url(__FILE__) . '/spinner/spinner.gif' ?>" id="loader" style="display: none">
            </div>
        </div>

    <?php endif ?>


    <?php
    echo '<div id="all_in_one_invite_codes_list_codes">';
    echo all_in_one_invite_codes_list_codes($args);
    echo '</div>';
}


/**
 * Register Settings Options
 *
 */
function all_in_one_invite_codes_buddypress_register_option()
{

    // General Settings
    register_setting('all_in_one_invite_codes_buddypress', 'all_in_one_invite_codes_buddypress', 'all_in_one_invite_codes_default_sanitize');
}

add_action('admin_init', 'all_in_one_invite_codes_buddypress_register_option');


add_filter('all_in_one_invite_codes_admin_tabs', 'all_in_one_invite_codes_buddypress_admin_tabs', 2, 10);

function all_in_one_invite_codes_buddypress_admin_tabs($tabs)
{

    $tabs['buddypress'] = 'BuddyPress';

    return $tabs;
}

add_action('all_in_one_invite_codes_settings_page_tab', 'all_in_one_invite_codes_buddypress_settings_page_tab');
function all_in_one_invite_codes_buddypress_settings_page_tab($tab)
{

    if ($tab != 'buddypress') {
        return;
    }

    $all_in_one_invite_codes_buddypress = get_option('all_in_one_invite_codes_buddypress'); ?>
    <div class="metabox-holder">
        <div class="postbox all_in_one_invite_codes-metabox">

            <div class="inside">

                <form method="post" action="options.php">

                    <?php settings_fields('all_in_one_invite_codes_buddypress'); ?>


                    <table class="form-table">
                        <tbody>
                            <tr valign="top">
                                <th scope="row" valign="top">
                                    <?php _e('Profile Integration', 'all-in-one-invite-codes-buddypress'); ?>
                                </th>
                                <td>
                                    <?php
                                    $pages['enabled'] = __('Enable', 'all-in-one-invite-codes-buddypress');
                                    $pages['disable'] = __('Disable', 'all-in-one-invite-codes-buddypress');

                                    if (isset($pages) && is_array($pages)) {
                                        echo '<select name="all_in_one_invite_codes_buddypress[profile_tab]" id="all_in_one_invite_codes_buddypress_profile_tab">';

                                        foreach ($pages as $page_id => $page_name) {
                                            echo '<option ' . selected($all_in_one_invite_codes_buddypress['profile_tab'], $page_id) . 'value="' . $page_id . '">' . $page_name . '</option>';
                                        }
                                        echo '</select>';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row" valign="top">
                                    <?php _e('BuddyPress Registration', 'all-in-one-invite-codes-buddypress'); ?>
                                </th>
                                <td>
                                    <?php
                                    $pages['enabled'] = __('Enable', 'all-in-one-invite-codes-buddypress');
                                    $pages['disable'] = __('Disable', 'all-in-one-invite-codes-buddypress');

                                    if (isset($pages) && is_array($pages)) {
                                        echo '<select name="all_in_one_invite_codes_buddypress[registration]" id="all_in_one_invite_codes_buddypress_registration">';

                                        foreach ($pages as $page_id => $page_name) {
                                            echo '<option ' . selected($all_in_one_invite_codes_buddypress['registration'], $page_id) . 'value="' . $page_id . '">' . $page_name . '</option>';
                                        }
                                        echo '</select>';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row" valign="top">
                                    <?php _e('Auto Follow', 'all-in-one-invite-codes-buddypress'); ?>
                                </th>
                                <td>
                                    <?php
                                    $pages['enabled'] = __('Enable', 'all-in-one-invite-codes-buddypress');
                                    $pages['disable'] = __('Disable', 'all-in-one-invite-codes-buddypress');

                                    if (isset($pages) && is_array($pages)) {
                                        echo '<select name="all_in_one_invite_codes_buddypress[autofollow]" id="all_in_one_invite_codes_buddypress_autofollow">';

                                        foreach ($pages as $page_id => $page_name) {
                                            echo '<option ' . selected($all_in_one_invite_codes_buddypress['autofollow'], $page_id) . 'value="' . $page_id . '">' . $page_name . '</option>';
                                        }
                                        echo '</select>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php _e('Automatically follow the activity of the inviter profile', 'all-in-one-invite-codes-buddypress'); ?>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row" valign="top">
                                    <?php _e('Auto Connect', 'all-in-one-invite-codes-buddypress'); ?>
                                </th>
                                <td>
                                    <?php
                                    $pages['enabled'] = __('Enable', 'all-in-one-invite-codes-buddypress');
                                    $pages['disable'] = __('Disable', 'all-in-one-invite-codes-buddypress');

                                    if (isset($pages) && is_array($pages)) {
                                        echo '<select name="all_in_one_invite_codes_buddypress[autoconnect]" id="all_in_one_invite_codes_buddypress_autoconnect">';

                                        foreach ($pages as $page_id => $page_name) {
                                            echo '<option ' . selected($all_in_one_invite_codes_buddypress['autoconnect'], $page_id) . 'value="' . $page_id . '">' . $page_name . '</option>';
                                        }
                                        echo '</select>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php _e('Automatically send a friend request to the inviter profile', 'all-in-one-invite-codes-buddypress'); ?>
                                </td>
                            </tr>

                            <tr valign="top">
                                <th scope="row" valign="top">
                                    <?php _e('Restrict create invite codes', 'all-in-one-invite-codes-buddypress'); ?>
                                </th>
                                <td>
                                    <?php
                                    $pages['enabled'] = __('Enable', 'all-in-one-invite-codes-buddypress');
                                    $pages['disable'] = __('Disable', 'all-in-one-invite-codes-buddypress');



                                    if (isset($pages) && is_array($pages)) {
                                        echo '<select  name="all_in_one_invite_codes_buddypress[restrict_create_invites]" id="all_in_one_invite_codes_buddypress_resctric_create_invites">';

                                        foreach ($pages as $page_id => $page_name) {
                                            echo '<option ' . selected($all_in_one_invite_codes_buddypress['restrict_create_invites'], $page_id) . 'value="' . $page_id . '">' . $page_name . '</option>';
                                        }
                                        echo '</select>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php _e('Restrict the invite codes creation to the backend area, users can\'t create invites codes on the frontend', 'all-in-one-invite-codes-buddypress'); ?>

                                </td>
                            </tr>

                            <tr valign="top">
                                <th scope="row" valign="top">
                                    <?php _e('Restrict invite friends', 'all-in-one-invite-codes-buddypress'); ?>
                                </th>
                                <td>
                                    <?php
                                    $pages['enabled'] = __('Enable', 'all-in-one-invite-codes-buddypress');
                                    $pages['disable'] = __('Disable', 'all-in-one-invite-codes-buddypress');



                                    if (isset($pages) && is_array($pages)) {
                                        echo '<select  name="all_in_one_invite_codes_buddypress[restrict_invite_friends]" id="all_in_one_invite_codes_buddypress_resctric_invite_friends">';

                                        foreach ($pages as $page_id => $page_name) {
                                            echo '<option ' . selected($all_in_one_invite_codes_buddypress['restrict_invite_friends'], $page_id) . 'value="' . $page_id . '">' . $page_name . '</option>';
                                        }
                                        echo '</select>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php _e('Restrict the invite friends option to the backend area, users can\'t send invites to friends on the frontend', 'all-in-one-invite-codes-buddypress'); ?>

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
function all_in_one_invite_codes_bp_after_profile_field_content()
{

    // Check if the invite code is coming from a link
    $tk_invite_code = (!empty($_GET['invite_code'])) ? sanitize_key(trim($_GET['invite_code'])) : '';

?>
    <div class="editfield required-field">
        <label for="tk_invite_code"><?php _e('Invitation Code', 'all-in-one-invite-codes-buddypress') ?></label>
        <?php echo do_action('bp_tk_invite_code_errors') ?>
        <input type="text" name="tk_invite_code" id="tk_invite_code" class="input" required="required" value="<?php echo esc_attr($tk_invite_code); ?>" size="25" />
    </div>
<?php

}

add_action('bp_signup_validate', 'test_bp_signup_validate');
function test_bp_signup_validate()
{
    global $bp;

    // Check if the field has a code
    if (empty($_POST['tk_invite_code']) || !empty($_POST['tk_invite_code']) && trim($_POST['tk_invite_code']) == '') {
        $bp->signup->errors['tk_invite_code'] = __('Please enter a Invite Code.', 'all-in-one-invite-codes-buddypress');
    } else {

        $tk_invite_code = sanitize_key(trim($_POST['tk_invite_code']));

        // Validate teh code
        $result = all_in_one_invite_codes_validate_code($tk_invite_code, $_POST['signup_email']);
        if (isset($result['error'])) {
            $bp->signup->errors['tk_invite_code'] = sprintf('<strong>%s</strong>: %s', __('ERROR', 'all-in-one-invite-codes-buddypress'), $result['error']);
        }
    }
}

add_action('bp_core_activated_user', 'aioic_buddypress_after_activation_process', 10, 3);
function aioic_buddypress_after_activation_process($user_id, $key, $user)
{

    global $bp;

    $user = get_user_by('ID', $user_id);
    $all_in_one_invite_codes_buddypress = get_option('all_in_one_invite_codes_buddypress');
    $autofollow_enabled = isset($all_in_one_invite_codes_buddypress['autofollow']) ? $all_in_one_invite_codes_buddypress['autofollow'] : false;
    $autoconnect_enabled = isset($all_in_one_invite_codes_buddypress['autoconnect']) ? $all_in_one_invite_codes_buddypress['autoconnect'] : false;

    if ($user->ID && $autoconnect_enabled == 'enabled' || $autofollow_enabled == 'enabled') {
        $email = $user->user_email;
        $args = array(

            'posts_per_page' => -1,
            'post_type'      => 'tk_invite_codes', //you can use also 'any'
            'orderby' => 'post_author',
            'order' => 'ASC'
        );
        $the_query = new WP_Query($args);

        if ($the_query->have_posts()) {

            while ($the_query->have_posts()) : $the_query->the_post();
                $all_in_one_invite_codes_options = get_post_meta(get_the_ID(), 'all_in_one_invite_codes_options', true);
                $email_needle                           = empty($all_in_one_invite_codes_options['email']) ? '' : $all_in_one_invite_codes_options['email'];


                if ($email == $email_needle) {
                    $author_id =  (int)$the_query->post->post_author;
                    $inviter      = get_user_by('ID', $author_id);
                    $invited_by = $inviter->ID;
                    if ($autoconnect_enabled == 'enabled') {
                        if (!friends_add_friend($user->ID, $invited_by)) {
                            bp_core_add_message(__('Friendship could not be requested.', 'buddypress'), 'error');
                        } else {
                            bp_core_add_message(__('Friendship requested', 'buddypress'));
                        }
                    }
                    if ($autofollow_enabled == 'enabled') {
                        $params = array(
                            'leader_id' => $invited_by,
                            'follower_id' => $user->ID
                        );
                        if (!bp_start_following($params)) {

                            bp_core_add_message(__('Error when start following user.', 'buddypress'), 'error');
                        } else {
                            bp_core_add_message(__('Started following user with success', 'buddypress'));
                        }
                    }

                    break;
                }




            endwhile;
        }
    }
}

if (!function_exists('br_fs')) {
    // Create a helper function for easy SDK access.
    function br_fs()
    {
        global $br_fs;

        if (!isset($br_fs)) {
            // Include Freemius SDK.
            if (file_exists(dirname(dirname(__FILE__)) . '/all-in-one-invite-codes/includes/resources/freemius/start.php')) {
                // Try to load SDK from parent plugin folder.
                require_once dirname(dirname(__FILE__)) . '/all-in-one-invite-codes/includes/resources/freemius/start.php';
            } elseif (file_exists(dirname(dirname(__FILE__)) . '/all-in-one-invite-codes-premium/includes/resources/freemius/start.php')) {
                // Try to load SDK from premium parent plugin folder.
                require_once dirname(dirname(__FILE__)) . '/all-in-one-invite-codes-premium/includes/resources/freemius/start.php';
            }


            $br_fs = fs_dynamic_init(array(
                'id'               => '3323',
                'slug'             => 'buddypress',
                'premium_slug'     => 'buddypress-registration-premium',
                'type'             => 'plugin',
                'public_key'       => 'pk_25bbf96c6d7f5376ee564ad54df6d',
                'is_premium'       => true,
                'is_premium_only'  => true,
                'has_paid_plans'   => true,
                'is_org_compliant' => false,
                'trial'            => array(
                    'days'               => 7,
                    'is_require_payment' => false,
                ),
                'parent'           => array(
                    'id'         => '3322',
                    'slug'       => 'all-in-one-invite-codes',
                    'public_key' => 'pk_955be38b0c4d2a2914a9f4bc98355',
                    'name'       => 'All in One Invite Codes',
                ),
                'bundle_license_auto_activation' => true,
                'menu'             => array(
                    'support' => false,
                )
            ));
        }

        return $br_fs;
    }
}
function br_fs_is_parent_active_and_loaded()
{
    // Check if the parent's init SDK method exists.
    return function_exists('all_in_one_invite_codes_core_fs');
}

function br_fs_is_parent_active()
{
    $active_plugins = get_option('active_plugins', array());

    if (is_multisite()) {
        $network_active_plugins = get_site_option('active_sitewide_plugins', array());
        $active_plugins         = array_merge($active_plugins, array_keys($network_active_plugins));
    }

    foreach ($active_plugins as $basename) {
        if (
            0 === strpos($basename, 'all-in-one-invite-codes/') ||
            0 === strpos($basename, 'all-in-one-invite-codes-premium/')
        ) {
            return true;
        }
    }

    return false;
}

function br_fs_init()
{
    if (br_fs_is_parent_active_and_loaded()) {
        // Init Freemius.
        br_fs();


        // Signal that the add-on's SDK was initiated.
        do_action('br_fs_loaded');

        // Parent is active, add your init code here.

    } else {
        // Parent is inactive, add your error handling here.
    }
}

if (br_fs_is_parent_active_and_loaded()) {
    // If parent already included, init add-on.
    br_fs_init();
} else if (br_fs_is_parent_active()) {
    // Init add-on only after the parent is loaded.
    add_action('all_in_one_invite_codes_core_fs_loaded', 'br_fs_init');
} else {
    // Even though the parent is not activated, execute add-on for activation / uninstall hooks.
    br_fs_init();
}
