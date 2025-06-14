<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    protected function render_admin($view, $data = [])
    {
        $data['view'] = $view;
        $this->load->view('admin/layout/app', $data);
    }
}
