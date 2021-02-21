<?php

defined('BASEPATH') or exit('Direct access script is not allowed');

$config['auth']['jwt'] = [
    'key' => 'parlenteSablas',
    'algorithm' => 'HS256',
    'token_lifetime' => 86500, // in seconds
];