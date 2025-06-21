<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CustomerController extends MY_Controller
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
        $this->render_admin('admin/customers/index', $data);
    }

    public function ajax_list()
    {
        $list = $this->CustomerModel->get_datatables();
        $data = [];
        $no = $this->input->post('start');

        foreach ($list as $customer) {
            $no++;
            $row = [];
            $row[] = $customer->id;
            $row[] = $customer->customer_name;
            $row[] = $customer->phone;
            $row[] = $customer->email;
            $row[] = $customer->address;
            $row[] = '
                <a href="' . base_url('admin/customers/show/' . $customer->id) . '" class="btn btn-info btn-sm">Show</a>
                <a href="' . base_url('admin/customers/edit/' . $customer->id) . '" class="btn btn-warning btn-sm">Edit</a>
                <button class="btn btn-danger btn-sm delete-btn" data-id="' . $customer->id . '">Delete</button>
            ';

            $data[] = $row;
        }

        $output = [
            "draw" => intval($this->input->post('draw')),
            "recordsTotal" => $this->CustomerModel->count_all(),
            "recordsFiltered" => $this->CustomerModel->count_filtered(),
            "data" => $data,
        ];

        echo json_encode($output);
    }

    public function add()
    {
        $data['activePage'] = 'customers';

        $this->form_validation->set_rules('customer_name', 'Customer Name', 'required');
        $this->form_validation->set_rules('phone', 'Phone', 'required');
        $this->form_validation->set_rules('address', 'Address', 'required');

        if ($this->form_validation->run() === FALSE) {

            $this->render_admin('admin/customers/add', $data);
        } else {
            $customerData = array(
                'customer_name' => $this->input->post('customer_name'),
                'phone' => $this->input->post('phone'),
                'email' => $this->input->post('email'),
                'gst_number' => $this->input->post('gst_number'),
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

            $this->render_admin('admin/customers/edit', $data);
        } else {
            $customerData = array(
                'customer_name' => $this->input->post('customer_name'),
                'phone' => $this->input->post('phone'),
                'email' => $this->input->post('email'),
                'gst_number' => $this->input->post('gst_number'),
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
        echo json_encode(['status' => true]);
    }

    public function show($id)
    {
        $tab = $this->input->get('tab') ?? 'profile';

        $data['activePage'] = 'customers';
        $data['customer'] = $this->CustomerModel->get_customer_by_id($id);
        $data['tab'] = $tab;

        if (!$data['customer']) show_404();

        switch ($tab) {
            case 'accounts':
                $data['account_summary'] = $this->CustomerModel->get_account_summary($id);
                break;
            case 'payments':
                $data['payments'] = $this->CustomerModel->get_payments($id);
                break;
            case 'invoices':
                $data['invoices'] = $this->CustomerModel->get_invoices($id);
                break;
            default:
                // no extra data needed for profile
                break;
        }

        $this->render_admin('admin/customers/show', $data);
    }
}
