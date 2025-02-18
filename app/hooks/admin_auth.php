<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class AdminAuthHook
{
    public function checkAdmin()
    {
        $CI =& get_instance();
        $CI->load->library('session');

        if (!$CI->session->userdata('is_admin')) {
            redirect('login');
        }
    }
}
