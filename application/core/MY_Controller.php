<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    protected function render_admin($view, $data = [])
    {
        $this->load->model('ProductModel');
        $this->load->model('CategoryModel');
        $this->load->model('BrandModel');
        $this->load->model('ProductTypeModel'); // ✅ this line was missing

        $data['view'] = $view;

        // Get all categories, brands, and product types for filters
        $data['categories'] = $this->CategoryModel->get_all_categories();
        $data['brands'] = $this->BrandModel->get_all_brands();
        $data['product_types'] = $this->ProductTypeModel->get_all_product_types();

        $this->load->view('admin/layout/app', $data);
    }
}
