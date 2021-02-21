<?php

defined('BASEPATH') or exit('Direct access path is not allowed');

class Auth_model extends CI_Model 
{
    public function user_authentication($app_id, $app_key)
    {
        $expected_app_id = 100;
        $expected_app_key = 'appkey100';

        if ($app_id != $expected_app_id) {
            return FALSE;
        }

        if ($app_key != $expected_app_key) {
            return FALSE;
        }

        return [
            'app_id' => $app_id,
            'app_name' => 'Example App'
        ];
    }
}