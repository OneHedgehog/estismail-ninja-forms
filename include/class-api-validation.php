<?php if (!defined('ABSPATH')) exit;

class Estis_NF_Api_Validation
{
    use Estis_NF_Api_Connect;

    private $api_key = '';
    private $data = '';

    function __construct()
    {
        $this->get_data($_POST[ESTIS_NF_PREFIX . '_api_key']);
        $this->save();
    }

    public function get_data($api_key)
    {
        $this->api_key = $api_key;
        $user_params = array('login', 'email', 'name');
        $user_url = 'https://v1.estismail.com/mailer/users';
        $method = 'GET';
        $res = $this->data = $this->estis_query($user_url, $api_key, $method, $user_params);
        if ($res) {
            delete_option(ESTIS_NF_PREFIX . '_display_error');
        }
    }

    public function save()
    {
        $estis_data = [];
        if ($this->data) {
            $estis_data = $this->data;
            $estis_data['status'] = 200;
        } else {
            $estis_data['status'] = 400;
        }
        $estis_data['api_key'] = $this->api_key;
        update_option(ESTIS_NF_PREFIX . '_array', $estis_data);
    }
}