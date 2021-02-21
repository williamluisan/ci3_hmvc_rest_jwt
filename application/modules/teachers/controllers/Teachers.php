<?php

defined('BASEPATH') or exit('Direct access path is not allowed');

use chriskacerguis\RestServer\RestController;

class Teachers extends RestController 
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Teachers_model', 'teacher');
    }

    public function index_get()
    {
        $id = $this->get('id');
        
        $teacher = $this->teacher->get_list($id);

        if ($teacher) {
            $this->response([
                'status' => TRUE,
                'data' => $teacher
            ], 200);
        } else {
            $this->response([
                'status' => FALSE,
                'error_code' => 100,
                'error_message' => 'Tidak mendapatkan data teacher'
            ], 404);
        }
    }
}