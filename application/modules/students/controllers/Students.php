<?php

defined('BASEPATH') or exit('Direct access path is not allowed');
class Students extends MY_Controller 
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Students_model', 'student');
    }

    public function index()
    {
        $result = $this->student->get_list();

        echo "<pre>";
        print_r($result); exit();
    }
}