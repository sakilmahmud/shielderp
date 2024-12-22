<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DtpController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('DtpModel');
        $this->load->library('form_validation');

        if (!$this->session->userdata('user_id')) {
            redirect('login');
        }
    }

    public function index()
    {
        $data['activePage'] = 'dtp';
        $data['dtp_services'] = $this->DtpModel->all();
        $data['categories'] = $this->DtpModel->getCategories();

        $this->load->view('admin/header', $data);
        $this->load->view('admin/dtp/index', $data);
        $this->load->view('admin/footer');
    }

    public function add()
    {
        $data['activePage'] = 'dtp';
        $data['categories'] = $this->DtpModel->getCategories();
        $data['isUpdate'] = false;

        $this->form_validation->set_rules('service_descriptions', 'Service Description', 'required');
        $this->form_validation->set_rules('dtp_service_categories', 'DTP Service Category', 'required');
        $this->form_validation->set_rules('service_charge', 'Service Charge', 'required|numeric');
        $this->form_validation->set_rules('paid_status', 'Paid Status', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('admin/header', $data);
            $this->load->view('admin/dtp/add', $data);
            $this->load->view('admin/footer');
        } else {
            $postData = $this->input->post();
            $saveData = [
                'service_descriptions' => $postData['service_descriptions'],
                'dtp_service_category_id' => $postData['dtp_service_categories'],
                'service_charge' => $postData['service_charge'],
                'paid_status' => $postData['paid_status'],
                'paid_amount' => ($postData['paid_status'] == 2) ? $postData['paid_amount'] : $postData['service_charge'],
                'service_date' => $postData['service_date'],
                'created_by' => $this->session->userdata('user_id') // Use authenticated user ID
            ];

            // Save service and get inserted ID
            $serviceId = $this->DtpModel->saveService($saveData);

            // Append the service ID to the log data
            $logData = [
                'log_data' => json_encode(array_merge(['id' => $serviceId], $saveData)),
                'action' => 1, // 1 = Add
                'made_by_id' => $this->session->userdata('user_id'),
                'made_by_name' => $this->session->userdata('full_name'),
                'created_at' => date('Y-m-d H:i:s')
            ];

            // Insert log entry
            add_log_data('log_dtp_services', $logData);

            $this->session->set_flashdata('message', 'Service added successfully');
            redirect('admin/dtp');
        }
    }


    public function edit($id)
    {
        $data['activePage'] = 'dtp';
        $data['categories'] = $this->DtpModel->getCategories();
        $data['service'] = $this->DtpModel->getService($id);
        $data['isUpdate'] = true;

        $this->form_validation->set_rules('service_descriptions', 'Service Description', 'required');
        $this->form_validation->set_rules('dtp_service_categories', 'DTP Service Category', 'required');
        $this->form_validation->set_rules('service_charge', 'Service Charge', 'required|numeric');
        $this->form_validation->set_rules('paid_status', 'Paid Status', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('admin/header', $data);
            $this->load->view('admin/dtp/add', $data);
            $this->load->view('admin/footer');
        } else {
            $postData = $this->input->post();
            $updateData = [
                'service_descriptions' => $postData['service_descriptions'],
                'dtp_service_category_id' => $postData['dtp_service_categories'],
                'service_charge' => $postData['service_charge'],
                'paid_status' => $postData['paid_status'],
                'paid_amount' => ($postData['paid_status'] == 2) ? $postData['paid_amount'] : $postData['service_charge'],
                'service_date' => $postData['service_date']
            ];

            // Update the service
            $this->DtpModel->updateService($id, $updateData);

            // Append the service ID to the log data
            $logData = [
                'log_data' => json_encode(array_merge(['id' => $id], $updateData)), // Include 'id' in log_data
                'action' => 2, // 2 = Edit
                'made_by_id' => $this->session->userdata('user_id'),
                'made_by_name' => $this->session->userdata('full_name'),
                'created_at' => date('Y-m-d H:i:s')
            ];

            // Insert log entry
            add_log_data('log_dtp_services', $logData);

            $this->session->set_flashdata('message', 'Service updated successfully');
            redirect('admin/dtp');
        }
    }


    public function delete($id)
    {
        // Get service details before deleting (for logging purposes)
        $service = $this->DtpModel->getService($id);

        // Delete the service
        $this->DtpModel->deleteService($id);

        // Prepare log data
        $logData = [
            'log_data' => json_encode($service), // Log the deleted service details
            'action' => 3, // 3 = Delete
            'made_by_id' => $this->session->userdata('user_id'),
            'made_by_name' => $this->session->userdata('full_name'),
            'created_at' => date('Y-m-d H:i:s')
        ];

        // Insert log entry
        add_log_data('log_dtp_services', $logData);

        // Set flash message and redirect
        $this->session->set_flashdata('message', 'Service deleted successfully');
        redirect('admin/dtp');
    }


    public function categories()
    {
        $data['activePage'] = 'dtp_categories';
        $data['categories'] = $this->DtpModel->getCategories();

        $this->load->view('admin/header', $data);
        $this->load->view('admin/dtp/categories/index', $data);
        $this->load->view('admin/footer');
    }

    public function addCategory()
    {
        $data['activePage'] = 'dtp_categories';
        $data['isUpdate'] = false;

        $this->form_validation->set_rules('category_name', 'Category Name', 'required|is_unique[dtp_service_categories.cat_title]');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('admin/header', $data);
            $this->load->view('admin/dtp/categories/add', $data);
            $this->load->view('admin/footer');
        } else {
            $postData = $this->input->post();
            $saveData = [
                'cat_title' => $postData['category_name']
            ];

            // Save the category and get the last inserted ID
            $categoryId = $this->DtpModel->saveCategory($saveData);

            // Prepare log data
            $logData = [
                'log_data' => json_encode(array_merge(['id' => $categoryId], $saveData)),
                'action' => 1, // 1 = Add
                'made_by_id' => $this->session->userdata('user_id'),
                'made_by_name' => $this->session->userdata('full_name'),
                'created_at' => date('Y-m-d H:i:s')
            ];

            // Insert log entry for category addition
            add_log_data('log_dtp_service_categories', $logData);

            $this->session->set_flashdata('message', 'Category added successfully');
            redirect('admin/dtp/categories');
        }
    }


    public function editCategory($id)
    {
        $data['activePage'] = 'dtp_categories';
        $data['isUpdate'] = true;
        $data['category'] = $this->DtpModel->getCategory($id);

        $this->form_validation->set_rules('category_name', 'Category Name', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('admin/header', $data);
            $this->load->view('admin/dtp/categories/add', $data);
            $this->load->view('admin/footer');
        } else {
            $postData = $this->input->post();
            $updateData = [
                'cat_title' => $postData['category_name']
            ];

            $this->DtpModel->updateCategory($id, $updateData);

            // Append the service ID to the log data
            $logData = [
                'log_data' => json_encode(array_merge(['id' => $id], $updateData)), // Include 'id' in log_data
                'action' => 2, // 2 = Edit
                'made_by_id' => $this->session->userdata('user_id'),
                'made_by_name' => $this->session->userdata('full_name'),
                'created_at' => date('Y-m-d H:i:s')
            ];

            // Insert log entry for category update
            add_log_data('log_dtp_service_categories', $logData);

            $this->session->set_flashdata('message', 'Category updated successfully');
            redirect('admin/dtp/categories');
        }
    }

    public function deleteCategory($id)
    {
        // Get category details before deleting (for logging purposes)
        $category = $this->DtpModel->getCategory($id);

        // Delete the category
        $this->DtpModel->deleteCategory($id);

        // Prepare log data
        $logData = [
            'log_data' => json_encode($category), // Log the deleted category details
            'action' => 3, // 3 = Delete
            'made_by_id' => $this->session->userdata('user_id'),
            'made_by_name' => $this->session->userdata('full_name'),
            'created_at' => date('Y-m-d H:i:s')
        ];

        // Insert log entry for category deletion
        add_log_data('log_dtp_service_categories', $logData);

        // Set flash message and redirect
        $this->session->set_flashdata('message', 'Category deleted successfully');
        redirect('admin/dtp/categories');
    }

    public function get_log_data($serviceId)
    {
        // Get log data from the model
        $logData = $this->DtpModel->getServiceLogs($serviceId);

        // Check if log data exists
        if ($logData) {
            // Decode the log_data if it's JSON encoded
            foreach ($logData as &$log) {
                // Decode the JSON encoded log_data field (if applicable)
                if (json_decode($log['log_data'])) {
                    $log['log_data'] = json_decode($log['log_data'], true); // Decode JSON string to array
                }
            }

            // Return the log data as JSON
            echo json_encode(['success' => true, 'log_data' => $logData]);
        } else {
            echo json_encode(['success' => false]);
        }
    }
}
