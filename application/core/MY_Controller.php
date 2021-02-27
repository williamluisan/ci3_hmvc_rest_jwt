<?php
defined('BASEPATH') or exit('Direct access script is not allowed');

use chriskacerguis\RestServer\RestController;
use \Firebase\JWT\JWT;

class MY_Controller extends CI_Controller
{
    public function __construct() 
    {
        parent::__construct();
    }
}

/**
 * Custom core class that extends RestController
 * 
 * This class is intended to extend on every custom rest controller
 * for JWT authentication at the first time when controller is loaded
 */
class Authentication_Controller extends RestController 
{
    public function __construct()
    {
        parent::__construct();

        $this->load->config('auth/config_jwt');

        $headers = $this->input->get_request_header('Authorization');
        $jwt_key = $this->config->item('auth')['jwt']['key'];
        
        $token = '';
        if ( ! empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers , $matches)) {
                $token = $matches[1];
            }
        }

        try {
            $decoded = JWT::decode($token, $jwt_key, [$this->config->item('auth')['jwt']['algorithm']]);
            $this->_userdata = $decoded;
        } catch (Exception $e) {
            $invalid = [
                'status' => FALSE,
                'error_code' => 102, // custom error code
                'message' => $e->getMessage()    
            ];
            
            $this->response($invalid, 401);
        }
    }
}