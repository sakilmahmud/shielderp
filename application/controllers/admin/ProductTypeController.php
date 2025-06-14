<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProductTypeController extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ProductTypeModel');
        $this->load->library('form_validation');

        if (!$this->session->userdata('username')) {
            redirect('login');
        }
    }

    public function index()
    {
        $data['activePage'] = 'product_types';
        $data['product_types'] = $this->ProductTypeModel->get_all_product_types();

        $this->render_admin('admin/product_types/index', $data);
    }

    public function add()
    {
        $data['activePage'] = 'product_types';

        $this->form_validation->set_rules('product_type_name', 'product_type Name', 'required|is_unique[product_types.product_type_name]');

        if ($this->form_validation->run() === FALSE) {
            $data['isUpdate'] = false;


            $this->render_admin('admin/product_types/add', $data);
        } else {
            $product_typeData = array(
                'product_type_name' => $this->input->post('product_type_name'),
                'product_type_descriptions' => $this->input->post('product_type_descriptions'),
                'status' => 1
            );

            $this->ProductTypeModel->insert_product_type($product_typeData);

            // Set flash message and redirect
            $this->session->set_flashdata('message', 'Product type added successfully');
            redirect('admin/product-types');
        }
    }

    public function edit($id)
    {
        $data['activePage'] = 'product_types';

        $this->form_validation->set_rules('product_type_name', 'product_type Name', 'required');

        if ($this->form_validation->run() === FALSE) {
            $data['isUpdate'] = true;
            $data['product_type'] = $this->ProductTypeModel->get_product_type($id);


            $this->render_admin('admin/product_types/add', $data);
        } else {
            $product_typeData = array(
                'product_type_name' => $this->input->post('product_type_name'),
                'product_type_descriptions' => $this->input->post('product_type_descriptions'),
                'updated_at' => date('Y-m-d H:i:s')
            );

            $this->ProductTypeModel->update_product_type($id, $product_typeData);
            // Set flash message and redirect
            $this->session->set_flashdata('message', 'Product type updated successfully');
            redirect('admin/product-types');
        }
    }

    public function addAjax()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('product_type_name', 'product_type Name', 'required|is_unique[product_types.product_type_name]');

        if ($this->form_validation->run() === FALSE) {
            echo json_encode(['success' => false, 'errors' => validation_errors()]);
        } else {
            $data = [
                'product_type_name' => $this->input->post('product_type_name'),
            ];

            $product_type_id = $this->ProductTypeModel->insert_product_type($data);

            echo json_encode([
                'success' => true,
                'product_type' => [
                    'id' => $product_type_id,
                    'name' => $this->input->post('product_type_name')
                ]
            ]);
        }
    }

    public function delete($id)
    {
        $this->ProductTypeModel->delete_product_type($id);
        // Set flash message and redirect
        $this->session->set_flashdata('message', 'Product type delete successfully');
        redirect('admin/product-types');
    }
}
