<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
	exit;
}

delete_option(ESTIS_NF_PREFIX . '_array');
delete_option(ESTIS_NF_PREFIX . '_array_error');
delete_option(ESTIS_NF_PREFIX . '_display_error');