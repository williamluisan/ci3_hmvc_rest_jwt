<?php

defined('BASEPATH') or exit('Direct access path is not allowed');

class Auth_model extends CI_Model 
{
    private function _refresh_token_inside_database($user_id, $refresh_token)
    {        
        $user_id_stored = 100;
        // change this dummy variable value with the latest value you got from the endpoin /auth/token
        $refresh_token_stored = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJFeGFtcGxlIEFwcCAtIFJlZnJlc2ggVG9rZW4gSXNzdWVyIiwic3ViamVjdCI6eyJpZCI6IjEwMCIsIm5hbWUiOiJFeGFtcGxlIEFwcCJ9LCJleHAiOjE2MTUwNDMyMDUsImlhdCI6MTYxNDQzNzcwNX0.bNS-S2NW2TrgAsSynNd8Ot5akbckwbxZRjhGwUNMdR8';
    
        // comparison
        if (($user_id == $user_id_stored) && ($refresh_token == $refresh_token_stored)) {
            // if the refresh token is valid, return app_id and app_key. Should get this from the database.
            return [
                'app_id' => 100,
                'app_key' => 'appkey100'
            ];
        }

        return FALSE;
    }

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

    /**
     * Check if the refresh token exists in the database
     * and match it with the user id
     * 
     * @param   user_id         id user
     * @param   refresh_token   refresh token
     */
    public function is_refresh_token_valid($user_id, $refresh_token)
    {
        // this checking should be compared with the record (user_id, refresh token) which stored in the database
        $result = $this->_refresh_token_inside_database($user_id, $refresh_token);

        return $result;
    }
}