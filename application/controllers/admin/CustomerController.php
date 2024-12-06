<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CustomerController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('CustomerModel');
        $this->load->library('form_validation');

        if (!$this->session->userdata('username')) {
            redirect('login');
        }
    }

    public function index()
    {
        $data['activePage'] = 'customers';
        $data['customers'] = $this->CustomerModel->get_all_customers();
        $this->load->view('admin/header', $data);
        $this->load->view('admin/customers/index', $data);
        $this->load->view('admin/footer');
    }

    public function add()
    {
        $data['activePage'] = 'customers';

        $this->form_validation->set_rules('customer_name', 'Customer Name', 'required');
        $this->form_validation->set_rules('phone', 'Phone', 'required');
        $this->form_validation->set_rules('address', 'Address', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('admin/header', $data);
            $this->load->view('admin/customers/add', $data);
            $this->load->view('admin/footer');
        } else {
            $customerData = array(
                'customer_name' => $this->input->post('customer_name'),
                'phone' => $this->input->post('phone'),
                'email' => $this->input->post('email'),
                'address' => $this->input->post('address')
            );

            $this->CustomerModel->insert_customer($customerData);
            $this->session->set_flashdata('message', 'Customer added successfully');
            redirect('admin/customers');
        }
    }

    public function edit($id)
    {
        $data['activePage'] = 'customers';
        $data['customer'] = $this->CustomerModel->get_customer_by_id($id);

        if (empty($data['customer'])) {
            show_404();
        }

        $this->form_validation->set_rules('customer_name', 'Customer Name', 'required');
        $this->form_validation->set_rules('phone', 'Phone', 'required');
        $this->form_validation->set_rules('address', 'Address', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('admin/header', $data);
            $this->load->view('admin/customers/edit', $data);
            $this->load->view('admin/footer');
        } else {
            $customerData = array(
                'customer_name' => $this->input->post('customer_name'),
                'phone' => $this->input->post('phone'),
                'email' => $this->input->post('email'),
                'address' => $this->input->post('address')
            );

            $this->CustomerModel->update_customer($id, $customerData);
            $this->session->set_flashdata('message', 'Customer updated successfully');
            redirect('admin/customers');
        }
    }

    public function delete($id)
    {
        $this->CustomerModel->delete_customer($id);
        $this->session->set_flashdata('message', 'Customer deleted successfully');
        redirect('admin/customers');
    }
}
