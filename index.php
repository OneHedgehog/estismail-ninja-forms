<?php
/*
* Plugin Name: Estismail for Ninja Forms
* Plugin URI: https://estismail.com/
* Description: Integrate Estismail subscription into Ninja Forms
* Author: Estismail
* Version: 1.0
* Author URI: https://estismail.com/
* Text Domain: estismail-nf-translate
* Domain Path: /langs/
*/
if (!defined('ABSPATH')) exit;

require_once ('constant.php');
if (in_array('ninja-forms/ninja-forms.php', apply_filters('active_plugins', get_option('active_plugins')))) {


    function estis_nf_activation_plugin()
    {
        $estis_data['api_key'] = '';
        $estis_data['status'] = '2';
        add_option(ESTIS_NF_PREFIX . '_array', $estis_data);
    }

    register_activation_hook(__FILE__, ESTIS_NF_PREFIX . '_activation_plugin');

    require_once( 'include/styles.php' );
    require_once( 'include/display-menu.php' );
    require_once( 'include/menus.php' );
    require_once( 'include/class-api-connect.php' );
    require_once( 'include/class-api-validation.php' );
    require_once( 'include/class-admin-notices.php' );
    require_once( 'include/notice-delete.inc.php' );

    if (isset($_POST[ESTIS_NF_PREFIX . '_api_key']) && !empty($_POST[ESTIS_NF_PREFIX . '_api_key'])) {
        $obj = new Estis_NF_Api_Validation();
    }

    $status = get_option(ESTIS_NF_PREFIX . '_array')['status'];
    $err = get_option(ESTIS_NF_PREFIX . '_display_error');

    if ($status == 200 && !$err) {
        add_action('ninja_forms_register_actions', function ($actions) {
            require_once( 'include/class-main-action.php' );
            $actions['estis'] = new Estis_NF_Action();
            return $actions;
        });

        add_action('ninja_forms_register_fields', function ($fields) {
            require_once( 'include/class-main-field.php' );
            $fields['estis_options'] = new Estis_NF_Field();
            return $fields;
        });
    }

    $notice = new Estis_NF_Admin_Notices();

    add_action('admin_menu', ESTIS_NF_PREFIX . '_admin_menu');
    add_action('admin_menu-view', ESTIS_NF_PREFIX . '_admin_menu_view');
    add_action('admin_post_est_nf_notice_delete', ESTIS_NF_PREFIX . '_notice_delete');

} else {
    function estis_nf_plugins_deactivate()
    {
        if ($active_plugins = get_option('active_plugins')) {
            $deactivate_this = array(
                'estismail-ninja-forms/index.php'
            );
            $active_plugins = array_diff($active_plugins, $deactivate_this);
            update_option('active_plugins', $active_plugins);
        }
    }

    add_action('admin_init', ESTIS_NF_PREFIX . '_plugins_deactivate');


    function estis_nf_admin_notice_load()
    {
        ?>
        <style>div#message.updated {
                display: none;
            }</style>
        <div class="notice notice-error is-dismissible">
            <h1>
                <?php _e('Ninja Forms should be active!', 'estismail-nf-translate'); ?>
            </h1>
        </div>
        <?php
    }

    add_action('admin_notices', ESTIS_NF_PREFIX . '_admin_notice_load');
}

add_action('plugins_loaded', ESTIS_NF_PREFIX . '_true_load_textdomain');
function estis_nf_true_load_textdomain()
{
    load_plugin_textdomain('estismail-nf-translate', false, dirname(plugin_basename(__FILE__)) . '/langs/');
}

?>