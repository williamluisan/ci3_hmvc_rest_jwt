<?php

defined('BASEPATH') or exit('Direct access path is not allowed');

use chriskacerguis\RestServer\RestController;
use \Firebase\JWT\JWT;

class Auth extends RestController 
{
    public function __construct()
    {
        parent::__construct();

        $this->load->config('config_jwt');
        $this->load->model('Auth_model', 'auth');
    }

    public function token_post()
    {
        $app_id  = $this->post('id');
        $app_key = $this->post('key');
        $jwt_key = $this->config->item('auth')['jwt']['key'];
        
        $user_authentication = $this->auth->user_authentication($app_id, $app_key);
        if ( ! $user_authentication) {
            $this->response([
                'status' => FALSE,
                'error_code' => 101,
                'message' => 'Autentikasi gagal'
            ], 402);
        } else {
            $date = new DateTime();
            $payload = [
                'iss' => 'Example App - Issuer',
                'subject' => [
                    'id' => $user_authentication['app_id'],
                    'name' => $user_authentication['app_name']
                ],
                'exp' => $date->getTimeStamp() + $this->config->item('auth')['jwt']['token_lifetime'],
                'iat' => $date->getTimeStamp()
            ];

            $JWT = JWT::encode($payload, $jwt_key, $this->config->item('auth')['jwt']['algorithm']);

            $this->response([
                'status' => TRUE,
                'token' => $JWT
            ], 200);
        }
    }

    public function token_refresh_post()
    {
        // still looking for the best practice
        // ..
    }
}