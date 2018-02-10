<?php if (!defined('ABSPATH')) exit;

trait Estis_NF_Api_Connect
{
    /**
     * @param string $url
     * @param string $api_key
     * @param string $method
     * @param array $query_params
     * @return array $response['body'] (JSON)
     **/
    public function estis_query($url, $api_key, $method, $query_params)
    {
        $params = array(
            'timeout' => 3,
            'httpversion' => '1.1',
            'sslverify' => true,
            'headers' => array('X-Estis-Auth' => $api_key)
        );

        switch ($method) {
            case 'POST': {
                $params['method'] = 'POST';
                $params['blocking'] = true;
                $params['body'] = $query_params;
                $response = wp_remote_post($url, $params);
                $res_code = 201;
            }
                break;
            case 'GET': {
                if (isset($query_params) && !empty($query_params)) {
                    $query_params = array('fields' => json_encode($query_params));
                    $pretty_url = $url . '?' . http_build_query($query_params);
                } else {
                    $pretty_url = $url;
                }
                $response = wp_remote_get($pretty_url, $params);
                $res_code = 200;
            }
                break;

        }

        if (wp_remote_retrieve_response_code($response) !== $res_code) {

            update_option(ESTIS_NF_PREFIX . '_array_error', $response['body']);
            return [];
        }

        if (!$response['body']) {
            return array('body' => 'empty body');
        }

        return json_decode($response['body'], true);
    }
}