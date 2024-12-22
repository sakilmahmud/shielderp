<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BrandsController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('BrandModel');
        $this->load->library('form_validation');

        if (!$this->session->userdata('user_id')) {
            redirect('login');
        }
    }

    public function index()
    {
        $data['activePage'] = 'brands';
        $data['brands'] = $this->BrandModel->get_all_brands();

        $this->load->view('admin/header', $data);
        $this->load->view('admin/brands/index', $data);
        $this->load->view('admin/footer');
    }

    public function add()
    {
        $data['activePage'] = 'brands';

        $this->form_validation->set_rules('brand_name', 'Brand Name', 'required');

        if ($this->form_validation->run() === FALSE) {
            $data['isUpdate'] = false;

            $this->load->view('admin/header', $data);
            $this->load->view('admin/brands/add', $data);
            $this->load->view('admin/footer');
        } else {
            $brandData = array(
                'brand_name' => $this->input->post('brand_name'),
                'brand_descriptions' => $this->input->post('brand_descriptions'),
                'status' => 1
            );

            $this->BrandModel->insert_brand($brandData);

            // Set flash message and redirect
            $this->session->set_flashdata('message', 'Brand added successfully');
            redirect('admin/brands');
        }
    }

    public function edit($id)
    {
        $data['activePage'] = 'brands';

        $this->form_validation->set_rules('brand_name', 'Brand Name', 'required');

        if ($this->form_validation->run() === FALSE) {
            $data['isUpdate'] = true;
            $data['brand'] = $this->BrandModel->get_brand($id);

            $this->load->view('admin/header', $data);
            $this->load->view('admin/brands/add', $data);
            $this->load->view('admin/footer');
        } else {
            $brandData = array(
                'brand_name' => $this->input->post('brand_name'),
                'brand_descriptions' => $this->input->post('brand_descriptions'),
                'updated_at' => date('Y-m-d H:i:s')
            );

            $this->BrandModel->update_brand($id, $brandData);
            // Set flash message and redirect
            $this->session->set_flashdata('message', 'Brand updated successfully');
            redirect('admin/brands');
        }
    }

    public function addAjax()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('brand_name', 'Brand Name', 'required|is_unique[brands.brand_name]');

        if ($this->form_validation->run() === FALSE) {
            echo json_encode(['success' => false, 'errors' => validation_errors()]);
        } else {
            $data = [
                'brand_name' => $this->input->post('brand_name'),
            ];

            $brand_id = $this->BrandModel->insert_brand($data);

            echo json_encode([
                'success' => true,
                'brand' => [
                    'id' => $brand_id,
                    'name' => $this->input->post('brand_name')
                ]
            ]);
        }
    }

    public function delete($id)
    {
        $this->BrandModel->delete_brand($id);
        // Set flash message and redirect
        $this->session->set_flashdata('message', 'Brand delete successfully');
        redirect('admin/brands');
    }
}
