<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SupplierController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('SupplierModel');
        $this->load->library('form_validation');

        if (!$this->session->userdata('username')) {
            redirect('login');
        }
    }

    public function index()
    {
        $data['activePage'] = 'suppliers';
        $data['suppliers'] = $this->SupplierModel->get_all_suppliers();
        $this->load->view('admin/header', $data);
        $this->load->view('admin/suppliers/index', $data);
        $this->load->view('admin/footer');
    }

    public function add()
    {
        $data['activePage'] = 'suppliers';

        $this->form_validation->set_rules('supplier_name', 'Supplier Name', 'required');
        $this->form_validation->set_rules('phone', 'Phone', 'required');
        $this->form_validation->set_rules('address', 'Address', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('admin/header', $data);
            $this->load->view('admin/suppliers/add', $data);
            $this->load->view('admin/footer');
        } else {
            $supplierData = array(
                'supplier_name' => $this->input->post('supplier_name'),
                'phone' => $this->input->post('phone'),
                'email' => $this->input->post('email'),
                'address' => $this->input->post('address'),
                'gst_number' => $this->input->post('gst_number')
            );

            $this->SupplierModel->insert_supplier($supplierData);
            $this->session->set_flashdata('message', 'Supplier added successfully');
            redirect('admin/suppliers');
        }
    }

    public function edit($id)
    {
        $data['activePage'] = 'suppliers';
        $data['supplier'] = $this->SupplierModel->get_supplier($id);

        if (empty($data['supplier'])) {
            show_404();
        }

        $this->form_validation->set_rules('supplier_name', 'Supplier Name', 'required');
        $this->form_validation->set_rules('phone', 'Phone', 'required');
        $this->form_validation->set_rules('address', 'Address', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('admin/header', $data);
            $this->load->view('admin/suppliers/edit', $data);
            $this->load->view('admin/footer');
        } else {
            $supplierData = array(
                'supplier_name' => $this->input->post('supplier_name'),
                'phone' => $this->input->post('phone'),
                'email' => $this->input->post('email'),
                'address' => $this->input->post('address'),
                'gst_number' => $this->input->post('gst_number')
            );

            $this->SupplierModel->update_supplier($id, $supplierData);
            $this->session->set_flashdata('message', 'Supplier updated successfully');
            redirect('admin/suppliers');
        }
    }

    public function delete($id)
    {
        $this->SupplierModel->delete_supplier($id);
        $this->session->set_flashdata('message', 'Supplier deleted successfully');
        redirect('admin/suppliers');
    }
}
