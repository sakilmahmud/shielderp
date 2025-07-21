<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SupplierController extends MY_Controller
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

        $this->render_admin('admin/suppliers/index', $data);
    }

    public function add()
    {
        $data['activePage'] = 'suppliers';
        $this->load->model('StateModel');
        $data['states'] = $this->StateModel->get_all_states();
        $data['company_state'] = $this->SettingsModel->get_setting('company_state');

        $this->form_validation->set_rules('supplier_name', 'Supplier Name', 'required');
        $this->form_validation->set_rules('phone', 'Phone', 'required');
        $this->form_validation->set_rules('address', 'Address', 'required');
        $this->form_validation->set_rules('state_id', 'State', 'required');

        if ($this->form_validation->run() === FALSE) {

            $this->render_admin('admin/suppliers/add', $data);
        } else {
            $supplierData = array(
                'supplier_name' => $this->input->post('supplier_name'),
                'phone' => $this->input->post('phone'),
                'email' => $this->input->post('email'),
                'address' => $this->input->post('address'),
                'gst_number' => $this->input->post('gst_number'),
                'state_id' => $this->input->post('state_id')
            );

            $this->SupplierModel->insert_supplier($supplierData);
            $this->session->set_flashdata('message', 'Supplier added successfully');
            redirect('admin/suppliers');
        }
    }

    public function edit($id)
    {
        $data['activePage'] = 'suppliers';
        $this->load->model('StateModel');
        $data['states'] = $this->StateModel->get_all_states();
        $data['supplier'] = $this->SupplierModel->get_supplier($id);

        if (empty($data['supplier'])) {
            show_404();
        }

        $this->form_validation->set_rules('supplier_name', 'Supplier Name', 'required');
        $this->form_validation->set_rules('phone', 'Phone', 'required');
        $this->form_validation->set_rules('address', 'Address', 'required');
        $this->form_validation->set_rules('state_id', 'State', 'required');

        if ($this->form_validation->run() === FALSE) {

            $this->render_admin('admin/suppliers/edit', $data);
        } else {
            $supplierData = array(
                'supplier_name' => $this->input->post('supplier_name'),
                'phone' => $this->input->post('phone'),
                'email' => $this->input->post('email'),
                'address' => $this->input->post('address'),
                'gst_number' => $this->input->post('gst_number'),
                'state_id' => $this->input->post('state_id')
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
