<?php

defined('BASEPATH') or exit('Direct access path is not allowed');

class Teachers_model extends CI_Model 
{
    public function get_list($id = NULL)
    {
        $teachers = [
            [
                'name' => 'Bradley Manumpil',
                'age' => 42,
                'nationality' => 'Guayana'
            ],
            [
                'name' => 'Shirley Tangkuman',
                'age' => 38,
                'nationality' => 'Suriname'
            ]
        ];

        if (is_null($id)) {
            return $teachers;
        } else {
            return $teachers[$id];
        }
    }
}