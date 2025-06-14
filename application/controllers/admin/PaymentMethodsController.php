<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PaymentMethodsController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('PaymentMethodsModel');
        $this->load->library('form_validation');

        if (!$this->session->userdata('user_id')) {
            redirect('login');
        }
    }

    public function index()
    {
        $data['activePage'] = 'payment_methods';
        $data['payment_methods'] = $this->PaymentMethodsModel->getAll();

        $this->render_admin('admin/payment_methods/index', $data);
    }

    public function add()
    {
        $data['activePage'] = 'payment_methods';

        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('code', 'Code', 'required|is_unique[payment_methods.code]');

        if ($this->form_validation->run() === FALSE) {
            $data['isUpdate'] = false;


            $this->render_admin('admin/payment_methods/add', $data);
        } else {
            $paymentMethodData = [
                'title' => $this->input->post('title'),
                'code' => $this->input->post('code'),
                'status' => $this->input->post('status') ? 1 : 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $this->PaymentMethodsModel->insert($paymentMethodData);

            // Set flash message and redirect
            $this->session->set_flashdata('message', 'Payment method added successfully');
            redirect('admin/PaymentMethods');
        }
    }

    public function edit($id)
    {
        $data['activePage'] = 'payment_methods';

        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('code', 'Code', 'required');

        if ($this->form_validation->run() === FALSE) {
            $data['isUpdate'] = true;
            $data['payment_method'] = $this->PaymentMethodsModel->getById($id);


            $this->render_admin('admin/payment_methods/add', $data);
        } else {
            $paymentMethodData = [
                'title' => $this->input->post('title'),
                'code' => $this->input->post('code'),
                'status' => $this->input->post('status') ? 1 : 0,
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $this->PaymentMethodsModel->update($id, $paymentMethodData);

            // Set flash message and redirect
            $this->session->set_flashdata('message', 'Payment method updated successfully');
            redirect('admin/PaymentMethods');
        }
    }

    public function delete($id)
    {
        $this->PaymentMethodsModel->delete($id);

        // Set flash message and redirect
        $this->session->set_flashdata('message', 'Payment method deleted successfully');
        redirect('admin/PaymentMethods');
    }
}
