<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->helper('form'); // Load form helper

        // Get current route
        $current_url = uri_string();

        // Define excluded routes
        $excluded_routes = [
            'login',
            'logout',
            'authController/login',
            'authController/logout',
            'register',
        ];

        // Only store URL if it's not login/logout and not an AJAX call
        if (!in_array($current_url, $excluded_routes) && !$this->input->is_ajax_request()) {
            $this->session->set_userdata('last_url', current_url());
        }

        // Check if session has timed out
        if (!$this->session->userdata('user_id')) {
            // Optional: flash session message
            $this->session->set_flashdata('error', 'Your session has expired. Please log in again.');
            redirect('login');
        }
    }

    protected function render_admin($view, $data = [])
    {
        $this->load->model('ProductModel');
        $this->load->model('CategoryModel');
        $this->load->model('BrandModel');
        $this->load->model('ProductTypeModel');

        $data['view'] = $view;

        // Get all categories, brands, and product types for filters
        $data['categories'] = $this->CategoryModel->get_all_categories();
        $data['brands'] = $this->BrandModel->get_all_brands();
        $data['product_types'] = $this->ProductTypeModel->get_all_product_types();

        $this->load->view('admin/layout/app', $data);
    }
}
