<?php

defined('BASEPATH') or exit('Direct access script is not allowed');

$config['auth']['jwt'] = [
    'key' => 'parlenteSablas', // your custom key
    'algorithm' => 'HS256',
    'token_lifetime' => 86500, // in seconds
    'refresh_token_lifetime' => 86500 * 7, // in seconds, expired in 7 days ahead compare to acces_token
];