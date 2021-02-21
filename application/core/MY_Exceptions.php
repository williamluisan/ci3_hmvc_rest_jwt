<?php

    class MY_Exceptions extends CI_Exceptions {

        public function __construct()
        {
            parent::__construct();
        }

        function show_404($page = '', $log_error = TRUE)
        {
            $this->config =& get_config();
            $base_url = $this->config['base_url'];

            header("location: ".$base_url.'404');
        }
    }