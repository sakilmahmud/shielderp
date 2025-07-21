<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CustomerController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('CustomerModel');
        $this->load->model('StateModel');
        $this->load->model('SettingsModel');
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

            $photo_url = !empty($customer->photo)
                ? base_url($customer->photo)
                : base_url('assets/admin/img/user.jpg');

            $row[] = '<img src="' . $photo_url . '" class="rounded-circle" width="40" height="40" alt="Photo">';
            $row[] = $customer->id;
            $row[] = $customer->customer_name;
            $row[] = $customer->phone;
            $row[] = $customer->email;
            $row[] = '<strong class="' . ($customer->balance < 0 ? 'text-danger' : 'text-success') . '">₹' . number_format($customer->balance, 2) . '</strong>';
            $row[] = '<a href="' . base_url('admin/customers/show/' . $customer->id) . '" class="btn btn-info btn-sm">Show</a><a href="' . base_url('admin/customers/edit/' . $customer->id) . '" class="btn btn-warning btn-sm">Edit</a><button class="btn btn-danger btn-sm delete-btn" data-id="' . $customer->id . '">Delete</button>';

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
        $data['states'] = $this->StateModel->get_all_states();
        $data['company_state'] = $this->SettingsModel->get_setting('company_state');

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
                'address' => $this->input->post('address'),
                'state_id' => $this->input->post('state_id')
            );

            $this->CustomerModel->insert_customer($customerData);
            $this->session->set_flashdata('message', 'Customer added successfully');
            redirect('admin/customers');
        }
    }

    public function edit($id)
    {
        $data['activePage'] = 'customers';
        $data['states'] = $this->StateModel->get_all_states();
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
                'address' => $this->input->post('address'),
                'state_id' => $this->input->post('state_id')
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
        $this->load->model('StateModel');
        $data['states'] = $this->StateModel->get_all_states();
        $data['customer'] = $this->CustomerModel->get_customer_by_id($id);
        $data['tab'] = $tab;

        if (!$data['customer']) show_404();

        // Handle Profile Update
        if ($tab == 'profile' && $this->input->method() == 'post') {
            $this->form_validation->set_rules('customer_name', 'Customer Name', 'required');
            $this->form_validation->set_rules('phone', 'Phone', 'required');
            $this->form_validation->set_rules('address', 'Address', 'required');
            $this->form_validation->set_rules('state_id', 'State', 'required');

            if ($this->form_validation->run()) {
                $update = [
                    'customer_name' => $this->input->post('customer_name'),
                    'phone' => $this->input->post('phone'),
                    'email' => $this->input->post('email'),
                    'gst_number' => $this->input->post('gst_number'),
                    'address' => $this->input->post('address'),
                    'state_id' => $this->input->post('state_id'),
                    'status' => $this->input->post('status'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                $this->CustomerModel->update_customer($id, $update);
                $this->session->set_flashdata('message', 'Profile updated successfully.');
                redirect(current_url() . '?tab=profile');
            }
        }

        // Load tab-specific data
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
                break;
        }

        $this->render_admin('admin/customers/show', $data);
    }

    public function upload_photo()
    {
        $customer_id = $this->input->post('customer_id') ?? 0;

        $customer = $this->CustomerModel->get_customer_by_id($customer_id);
        if (!$customer) show_404();

        if (!empty($_FILES['photo']['name'])) {
            $upload_path = 'uploads/customers/' . $customer_id . '/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }

            $config['upload_path']   = $upload_path;
            $config['allowed_types'] = 'jpg|jpeg|png|webp';
            $config['file_name']     = time() . '_' . $_FILES['photo']['name'];
            $config['overwrite']     = true;

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('photo')) {
                $upload_data = $this->upload->data();
                $photo_path = $upload_path . $upload_data['file_name'];

                // Update DB
                $this->CustomerModel->update_customer($customer_id, ['photo' => $photo_path]);
                $this->session->set_flashdata('message', 'Photo uploaded successfully.');
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
            }
        }

        redirect('admin/customers/show/' . $customer_id);
    }

    public function remove_photo()
    {
        $customer_id = $this->input->post('customer_id') ?? 0;
        $customer = $this->CustomerModel->get_customer_by_id($customer_id);
        if (!$customer || empty($customer['photo'])) {
            $this->session->set_flashdata('error', 'No photo found to remove.');
            redirect('admin/customers/show/' . $customer_id);
        }

        if (file_exists($customer['photo'])) {
            unlink($customer['photo']);
        }

        $this->CustomerModel->update_customer($customer_id, ['photo' => null]);
        $this->session->set_flashdata('message', 'Photo removed successfully.');
        redirect('admin/customers/show/' . $customer_id);
    }
}
