<?php
function estis_nf_notice_delete()
{
    delete_option(ESTIS_NF_PREFIX . '_array_error');
    wp_redirect($_SERVER['HTTP_REFERER']);
}