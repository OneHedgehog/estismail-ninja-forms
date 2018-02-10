<?php if (!defined('ABSPATH')) exit;

final class Estis_NF_Action extends NF_Abstracts_Action
{
    use Estis_NF_Api_Connect;

    public $api_key = '';
    protected $_name = 'estis';
    public $lists = [];

    public function __construct()
    {
        parent::__construct();

        $this->api_from_db();
        $this->get_lists();

        $this->_nicename = __('Estismail', 'estismail-nf-translate');

        $this->_settings['double_option'] = array(
            'name' => 'double_option',
            'type' => 'select',
            'label' => __('Use double opt-in?', 'estismail-nf-translate'),
            'width' => 'full',
            'group' => 'primary',
            'options' => array(
                array(
                    'value' => 1,
                    'label' => __('Yes', 'estismail-nf-translate'),
                ),
                array(
                    'value' => 0,
                    'label' => __('No', 'estismail-nf-translate'),
                ),
            ),
        );

        $this->_settings['redirect'] = array(
            'name' => 'redirect',
            'type' => 'select',
            'label' => __('Redirect after subscription?', 'estismail-nf-translate'),
            'width' => 'full',
            'group' => 'primary',
            'options' => [
                array(
                    'value' => 1,
                    'label' => __('Yes', 'estismail-nf-translate')
                ),
                array(
                    'value' => 0,
                    'label' => __("No", 'estismail-nf-translate')
                )
            ]
        );

        $this->_settings['lists'] = array(
            'name' => 'lists',
            'type' => 'select',
            'label' => __('Select list', 'estismail-nf-translate'),
            'width' => 'full',
            'group' => 'primary',
            'options' => $this->lists_html(),
        );
    }

    public function process($action_settings, $form_id, $data)
    {
        update_option(ESTIS_NF_PREFIX . '_err_form_id', $form_id);

        //empty lists
        if (empty($action_settings['lists']) || !isset($action_settings['lists'])) {
            return false;
        }
        //values from estis-action in Ninja-form constructor
        $em_params = array(
            'list_id' => $action_settings['lists'],
            'activation_letter' => (int)$action_settings['double_option']
        );

        //collecting data from form submitting
        foreach ($data['fields'] as $key => $field_data) {
            //if unchecked checkbox
            if ($field_data['type'] === 'estis_options' && empty($field_data['value'])) {
                return false;
            }
            //get email for estis if it's set
            if ($field_data['type'] === 'email') {
                $em_params['email'] = $field_data['value'];
            }
            //get name for estis if it's set
            if ($field_data['type'] === 'textbox' || $field_data['type'] === 'firstname') {
                $em_params['name'] = $field_data['value'];
            }
            //get city for estis if it's set
            if ($field_data['type'] === 'city') {
                $em_params['city'] = $field_data['value'];
            }
            //get phone for estis if it's setf
            if ($field_data['type'] === 'phone') {
                $em_params['phone'] = $field_data['value'];
            }
        }

        $this->subscribe($em_params);

        $err_db = get_option(ESTIS_NF_PREFIX . '_array_error');
        $display = get_option(ESTIS_NF_PREFIX . '_display_error');

        if ($action_settings['redirect'] == 1) {
            $data['actions']['redirect'] = $this->lists['lists'][$action_settings['lists']]['subscribe_page'];

            if ($data['actions']['redirect'] == "https://mailer.estismail.com/subscribeme" || ($err_db && $display)) {
                $data['actions']['redirect'] = '';
            }
        }
        return $data;
    }

    private function get_lists()
    {
        $api_key = $this->api_key;
        $lists_url = 'https://v1.estismail.com/mailer/lists';
        $list_params = ['id', 'title', 'subscribe_page'];
        $lists = $this->estis_query($lists_url, $api_key, 'GET', $list_params);
        if ($lists) {
            foreach ($lists['lists'] as $key => $list) {
                $lists['lists'][$lists['lists'][$key]['id']] = $list;
                unset($lists['lists'][$key]);

            }
            $this->lists = $lists;
        }
    }

    private function lists_html()
    {
        $lists = $this->lists;
        if (!$lists) {
            return false;
        }
        $lists_html = [];
        foreach ($lists['lists'] as $key => $value) {
            $arr = array(
                'value' => $key,
                'label' => $value['title'],
            );
            array_push($lists_html, $arr);
        }
        return $lists_html;
    }

    //It should be an array of params in func arguments in future
    private function subscribe($em_params)
    {
        $api_key = $this->api_key;
        $em_url = 'https://v1.estismail.com/mailer/emails';
        $res = $this->estis_query($em_url, $api_key, 'POST', $em_params);

        if (get_option(ESTIS_NF_PREFIX . '_array_error')) {
            update_option(ESTIS_NF_PREFIX . '_display_error', 'Subscribe Error');
        } else {
            delete_option(ESTIS_NF_PREFIX . '_display_error');
        }
        return $res;
    }

    private function api_from_db()
    {
        $db = get_option(ESTIS_NF_PREFIX . '_array');
        $this->api_key = $db['api_key'];
    }
}