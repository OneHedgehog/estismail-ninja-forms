<?php

defined('ABSPATH') or exit;

function estis_nf_admin_menu()
{
    $plug = (get_plugin_data(ESTIS_NF_ABS_PATH));
    $estis_nf_hook_suffix = add_options_page('Estis ninja forms',
        $plug['Name'],
        'manage_options',
        ESTIS_NF_PREFIX . '_menu',
        ESTIS_NF_PREFIX . '_admin_menu_view');

    add_action("load-{$estis_nf_hook_suffix}", ESTIS_NF_PREFIX . '_style_function');
}