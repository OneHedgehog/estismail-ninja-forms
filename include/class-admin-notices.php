<?php

class Estis_NF_Admin_Notices
{

    function __construct()
    {
        $this->notice();
    }

    public function notice()
    {
        add_action('admin_notices', array($this, 'estismail_admin_notice'), 100);
    }

    public function estismail_admin_notice()
    {
        $err_db = get_option(ESTIS_NF_PREFIX . '_array_error');
        $display = get_option(ESTIS_NF_PREFIX . '_display_error');
        $plug = (get_plugin_data(ESTIS_NF_ABS_PATH));
        $form_id = get_option(ESTIS_NF_PREFIX . '_err_form_id');

        if ($err_db && $display) {
            $mes = json_decode($err_db, 1);
            if ($mes['name'] == 'Unauthorized') {
                $err_text = __('Connection with current api key failed', 'estismail-nf-translate');
            } else {
                $err_text = __('Submitting form to empty list', 'estismail-nf-translate');
            }
            ?>
            <style>
                #estimail_ninja_forms_notice {
                    display: block;
                    border-left: 4px solid #e35950;
                }
            </style>
            <div class="update-nag is-dismissible" id="estimail_ninja_forms_notice">
                <form action="admin-post.php" method="POST" id="estisNinjaSuperForm">
                    <input type="hidden" name="action" value="est_nf_notice_delete"/>
                    <span>
                        <?php
                        _e('Subscription error in', 'estismail-nf-translate');
                        echo(' ' . ($plug['Name']) . ' ');
                        _e('with shortcode', 'estismail-nf-translate');
                        ?>
                    </span>
                    <b><?php print('[ninja_form id=' . $form_id . ']'); ?></b>.
                    <?php
                    echo($err_text);
                    ?>.
                    <a href="<?php echo (admin_url()) . "/admin.php?page=ninja-forms"; ?>"><?php _e('Edit', 'estismail-nf-translate') ?></a>
                    <input type="hidden" name="estismail">
                    <p>
                        <button class="button"><?php _e('Ok', 'estismail-nf-translate') ?></button>
                    </p>
                </form>
            </div>
            <?php
        }
    }
}

?>