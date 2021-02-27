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

        $this->_jwt_key = $this->config->item('auth')['jwt']['key'];
    }

    /**
     * Generate access token
     * 
     */
    private function _generate_access_token($app_id, $app_name)
    {
        $date = new DateTime();
        $payload = [
            'iss' => $app_name . ' - Issuer',
            'subject' => [
                'id' => $app_id,
                'name' => $app_name
            ],
            'exp' => $date->getTimeStamp() + $this->config->item('auth')['jwt']['token_lifetime'],
            'iat' => $date->getTimeStamp()
        ];

        $JWT = JWT::encode($payload, $this->_jwt_key, $this->config->item('auth')['jwt']['algorithm']);
    
        return $JWT;
    }

    /**
     * Generate refresh token
     * 
     * @param   app_id      application id
     */
    private function _generate_refresh_token($app_id, $app_name)
    {
        $date = new DateTime();
        $payload = [
            'iss' => $app_name . ' - Refresh Token Issuer',
            'subject' => [
                'id' => $app_id,
                'name' => $app_name
            ],
            'exp' => $date->getTimeStamp() + $this->config->item('auth')['jwt']['refresh_token_lifetime'],
            'iat' => $date->getTimeStamp(),
        ];

        /**
         * This token needs to be stored in the database with 
         * user_id and this token as a value pair.
         * 
         * When request for the new access token,
         * this token have to be sent along with the expired access token as request body
         * 
         * after the request,
         * the expired access token payload need to be decoded and get the user_id
         * then the user_id and refresh token will be compared with
         * the record stored in the database.
         * 
         * if it match, return a new access token
         */
        $JWT_refresh_token = JWT::encode($payload, $this->_jwt_key, $this->config->item('auth')['jwt']['algorithm']);
        
        // store to the database
        // in this example, I just stored it inside the array
        // ...
            
        return $JWT_refresh_token;
    }

    public function token_post()
    {
        $app_id  = $this->post('id');
        $app_key = $this->post('key');
        
        $user_authentication = $this->auth->user_authentication($app_id, $app_key);
        if ( ! $user_authentication) {
            $this->response([
                'status' => FALSE,
                'error_code' => 101, // custom error code
                'message' => 'Failed to authenticate'
            ], 402);
        } else {            
            // generate access token
            $access_token = $this->_generate_access_token($user_authentication['app_id'], $user_authentication['app_name']);

            // generate refresh token
            $refresh_token = $this->_generate_refresh_token($user_authentication['app_id'], $user_authentication['app_name']);

            $this->response([
                'status' => TRUE,
                'access_token' => $access_token,
                'refresh_token' => $refresh_token
            ], 200);
        }
    }

    public function token_refresh_post()
    {
        $expired_access_token = $this->input->post('access_token');
        
        $user_id = NULL;
        try {
            JWT::decode($expired_access_token, $this->_jwt_key, [$this->config->item('auth')['jwt']['algorithm']]);
        } catch (\Firebase\JWT\ExpiredException $e) {
            // decode the JWT payload with another way
            list($headb64, $bodyb64, $cryptob64) = explode('.', $expired_access_token);
            $payload = json_decode(base64_decode($bodyb64), TRUE);

            // if payload empty
            if (empty($payload)) {
                $this->response([
                    'status' => FALSE,
                    'error_code' => 201,
                    'message' => 'expired access token is not valid'
                ]);
            } else {
                $user_id = $payload['subject']['id'];
            }
        } catch (Exception $e) {
            $invalid = [
                'status' => FALSE,
                'error_code' => 102, // custom error code
                'message' => $e->getMessage()    
            ];
            
            $this->response($invalid, 401);
        }
        
        // refresh token validation
        $headers = $this->input->get_request_header('Authorization');
        $refresh_token = '';
        if ( ! empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers , $matches)) {
                $refresh_token = $matches[1];
            }
        }
        
        $decoded = NULL;
        try {
            // refresh token decode (if needed)
            $decoded = JWT::decode($refresh_token, $this->_jwt_key, [$this->config->item('auth')['jwt']['algorithm']]);
        } catch (Exception $e) {
            $invalid = [
                'status' => FALSE,
                'error_code' => 102, // custom error code
                'message' => $e->getMessage()    
            ];
            
            $this->response($invalid, 401);
        }

        // check if refresh token is exists and match with the user id
        $refresh_token_validation = $this->auth->is_refresh_token_valid($user_id, $refresh_token);
        if ( ! $refresh_token_validation) {
            $this->response([
                'status' => FALSE,
                'error_code' => 102,
                'message' => 'Refresh token is not valid'
            ], 401); 
        } else {
            $user_authentication = $this->auth->user_authentication($refresh_token_validation['app_id'], $refresh_token_validation['app_key']);
            if ( ! $user_authentication) {
                $this->response([
                    'status' => FALSE,
                    'error_code' => 101,
                    'message' => 'Failed to authenticate'
                ], 402);
            } else {            
                // generate access token
                $access_token = $this->_generate_access_token($user_authentication['app_id'], $user_authentication['app_name']);

                // generate refresh token
                $refresh_token = $this->_generate_refresh_token($user_authentication['app_id'], $user_authentication['app_name']);

                $this->response([
                    'status' => TRUE,
                    'access_token' => $access_token,
                    'refresh_token' => $refresh_token
                ], 200);
            }
        }
    }
}