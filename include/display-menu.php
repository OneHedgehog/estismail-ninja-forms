<?php
if (!defined('ABSPATH')) exit;

function estis_nf_admin_menu_view()
{
    $estis_data = get_option(ESTIS_NF_PREFIX . '_array');
    $status = isset($estis_data['status']) ? $estis_data['status'] : 'status';
    $message = __('Not connected', 'estismail-nf-translate');
    $class = "alert-warning";

    if ($status == '400') {
        $message = __('Invalid API key', 'estismail-nf-translate');
        $class = 'alert-danger';
    }

    if ($status == '200') {
        $message = __('Connected', 'estismail-nf-translate');
        $class = "alert-info";
    }
    ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 col-xs-12 col-sm-12">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="caption-subject font-dark bold uppercase"><?php _e('API connection', 'estismail-nf-translate'); ?></span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <form method="post" action="" class="estis_form estisPostApiKeyForm">
                            <div class="alert <?php echo($class) ?>"><?php echo($message) ?></div>
                            <div class="form-group">
                                <h3><?php _e('Please, enter your API key', 'estismail-nf-translate'); ?></h3>
                                <input type="text" name="estis_nf_api_key" class="form-control estisApiKeyinput"
                                       value="<?php echo($estis_data['api_key']); ?>"/>
                            </div>
                            <div class="form-group">
                                <input type="submit" value="<?php _e('Connect', 'estismail-nf-translate'); ?>"
                                       class="btn btn-success api_key_button"/>
                                <a href="https://my.estismail.com/settings/profile#tab_1_5" target="_blank"
                                   class="btn btn-info get_api_key_href"><?php _e('Get your API key', 'estismail-nf-translate'); ?></a>
                                <a href="https://estismail.com/"
                                   class="estis_nf_readme"><?php _e('ReadMe', 'estismail-nf-translate'); ?></a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>