<?php

defined('BASEPATH') or exit('Direct access path is not allowed');

class Students_model extends CI_Model 
{
    public function get_list()
    {
        $students_identity = [
            [
                'name' => 'Aaron Takalamingan',
                'age' => 20,
                'nationality' => 'Timor Leste',
            ],
            [
                'name' => 'Bale Wansaga',
                'age' => 19,
                'nationality' => 'Nauru'
            ]
        ];

        return $students_identity;
    }
}