<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DtpController extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('UserModel');
        $this->load->model('DtpModel');
        $this->load->model('TransactionModel');
        $this->load->model('PaymentMethodsModel');
        $this->load->library('form_validation');

        if (!$this->session->userdata('user_id')) {
            redirect('login');
        }
    }

    public function index()
    {
        $data['activePage'] = 'dtp';
        $data['payment_methods'] = $this->PaymentMethodsModel->getAll(); // Load payment methods
        // Get filter inputs
        $from_date = $this->input->get('from_date', true);
        $to_date = $this->input->get('to_date', true);
        $created_by = $this->input->get('created_by', true);
        $category_id = $this->input->get('category', true);
        $paid_status = $this->input->get('paid_status', true); // Existing filter for Paid Status
        $payment_mode = $this->input->get('payment_mode', true); // New filter for Payment Mode

        // Default to today's data if no date is selected
        if (empty($from_date)) {
            $from_date = date('Y-m-d');
        }
        if (empty($to_date)) {
            $to_date = date('Y-m-d');
        }

        // Fetch filtered data
        $dtp_services = $this->DtpModel->getFilteredData($from_date, $to_date, $created_by, $category_id, $paid_status, $payment_mode);
        $data['dtp_services'] = $dtp_services;

        // Calculate totals
        $data['total_service_charge'] = array_sum(array_column($dtp_services, 'service_charge'));
        $data['total_paid_amount'] = array_sum(array_column($dtp_services, 'paid_amount'));

        // Get all categories and users for filters
        $data['dtp_categories'] = $this->DtpModel->getCategories();
        $data['users'] = $this->UserModel->getUsers(array(1, 2)); // Assuming this method exists to fetch users

        $this->render_admin('admin/dtp/index', $data);
    }

    public function fetchData()
    {
        $from_date = $this->input->post('from_date', true);
        $to_date = $this->input->post('to_date', true);
        $created_by = $this->input->post('created_by', true);
        $category_id = $this->input->post('category', true);
        $paid_status = $this->input->post('paid_status', true);
        $payment_mode = $this->input->post('payment_mode', true);
        $search_value = $this->input->post('search')['value'] ?? null; // Search term
        $start = $this->input->post('start', true); // Offset for pagination
        $length = $this->input->post('length', true); // Limit for pagination
        $draw = $this->input->post('draw', true); // Draw number for DataTables

        // Default to today's data if no date is selected
        if (empty($from_date)) {
            $from_date = date('Y-m-d');
        }
        if (empty($to_date)) {
            $to_date = date('Y-m-d');
        }

        // Fetch filtered data with pagination and search
        $result = $this->DtpModel->getFilteredData($from_date, $to_date, $created_by, $category_id, $paid_status, $payment_mode, $search_value, $start, $length);

        // Totals calculation
        $total_service_charge = 0;
        $total_paid_amount = 0;

        // Format data for DataTables
        $data = [];
        foreach ($result['data'] as $service) {
            $status = ['Due', 'Full Paid', 'Partial'];

            // Accumulate totals
            $total_service_charge += $service['service_charge'];
            $total_paid_amount += $service['paid_amount'];

            // Role-based action buttons
            $actions = '<a href="' . base_url('admin/dtp/edit/' . $service['id']) . '" class="btn btn-warning btn-sm">Edit</a>';
            if ($this->session->userdata('role') == 1) {
                $actions .= '
                <a href="' . base_url('admin/dtp/delete/' . $service['id']) . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to delete this service?\');">Delete</a>
                <button type="button" class="btn btn-info btn-sm" onclick="viewLog(' . $service['id'] . ')">View Log</button>';
            }

            $row = [
                $service['id'],
                $service['service_descriptions'],
                $service['category_title'],
                '₹' . number_format($service['service_charge'], 2),
                '₹' . number_format($service['paid_amount'], 2),
                $status[$service['paid_status']],
                $service['payment_mode_title'],
                date('d-m-Y', strtotime($service['service_date'])),
                $service['created_by_name'],
                $actions
            ];
            $data[] = $row;
        }

        // Prepare JSON response for DataTables
        echo json_encode([
            "draw" => intval($draw),
            "recordsTotal" => $result['recordsTotal'], // Total records without filtering
            "recordsFiltered" => $result['recordsFiltered'], // Total records after filtering
            "data" => $data, // Processed data
            "footer" => [
                "total_service_charge" => '₹' . number_format($total_service_charge, 2),
                "total_paid_amount" => '₹' . number_format($total_paid_amount, 2)
            ]
        ]);
    }

    public function add()
    {
        $data['activePage'] = 'dtp';
        $data['dtp_categories'] = $this->DtpModel->getCategories();
        $data['payment_methods'] = $this->PaymentMethodsModel->getAll();
        $data['isUpdate'] = false;

        $this->form_validation->set_rules('service_descriptions', 'Service Description', 'required');
        $this->form_validation->set_rules('dtp_service_categories', 'DTP Service Category', 'required');
        $this->form_validation->set_rules('service_charge', 'Service Charge', 'required|numeric');
        $this->form_validation->set_rules('paid_status', 'Paid Status', 'required');
        $this->form_validation->set_rules('payment_mode', 'Payment Mode', 'required'); // New validation rule

        if ($this->form_validation->run() === FALSE) {

            $this->render_admin('admin/dtp/add', $data);
        } else {
            $postData = $this->input->post();
            $partial_paid = $postData['partial_paid'];
            if ($postData['paid_status'] == 2) {
                $paid_amount = $partial_paid;
            } elseif ($postData['paid_status'] == 0) {
                $paid_amount = 0;
            } else {
                $paid_amount = $postData['service_charge'];
            }
            $saveData = [
                'service_descriptions' => $postData['service_descriptions'],
                'dtp_service_category_id' => $postData['dtp_service_categories'],
                'service_charge' => $postData['service_charge'],
                'paid_status' => $postData['paid_status'],
                'paid_amount' => $paid_amount,
                'payment_mode' => $postData['payment_mode'], // Save the selected payment mode
                'service_date' => $postData['service_date'],
                'created_by' => $this->session->userdata('user_id') // Use authenticated user ID
            ];

            // Save service and get inserted ID
            $serviceId = $this->DtpModel->saveService($saveData);

            // Append the service ID to the log data
            $logData = [
                'dtp_service_id' => $serviceId,
                'log_data' => json_encode(array_merge(['id' => $serviceId], $saveData)),
                'action' => 1, // 1 = Add
                'made_by_id' => $this->session->userdata('user_id'),
                'made_by_name' => $this->session->userdata('full_name'),
                'created_at' => date('Y-m-d H:i:s')
            ];

            // Insert log entry
            add_log_data('log_dtp_services', $logData);

            if ($paid_amount > 0) {
                $transaction_data = [
                    'amount' => $paid_amount,
                    'trans_type' => 1, // Credit
                    'payment_method_id' => $postData['payment_mode'],
                    'descriptions' => 'Payment for DTP service',
                    'transaction_for_table' => 'dtp_services',
                    'table_id' => $serviceId, // Order ID
                    'trans_by' => $this->session->userdata('user_id'), // User ID
                    'trans_date' => $postData['service_date'],
                    'created_at' => date('Y-m-d H:i:s')
                ];

                $last_insert_id = $this->TransactionModel->insert_transaction($transaction_data);
                if ($last_insert_id > 0) {
                    $this->session->set_flashdata('payment', 'Payment added successfully');
                }
            }

            $this->session->set_flashdata('message', 'Service added successfully');
            redirect('admin/dtp');
        }
    }


    public function edit($id)
    {
        $data['activePage'] = 'dtp';
        $data['dtp_categories'] = $this->DtpModel->getCategories();
        $data['payment_methods'] = $this->PaymentMethodsModel->getAll();
        $data['service'] = $this->DtpModel->getService($id);
        $data['isUpdate'] = true;

        $this->form_validation->set_rules('service_descriptions', 'Service Description', 'required');
        $this->form_validation->set_rules('dtp_service_categories', 'DTP Service Category', 'required');
        $this->form_validation->set_rules('service_charge', 'Service Charge', 'required|numeric');
        $this->form_validation->set_rules('paid_status', 'Paid Status', 'required');

        if ($this->form_validation->run() === FALSE) {

            $this->render_admin('admin/dtp/add', $data);
        } else {
            $postData = $this->input->post();
            $partial_paid = $postData['partial_paid'];
            if ($postData['paid_status'] == 2) {
                $paid_amount = $partial_paid;
            } elseif ($postData['paid_status'] == 0) {
                $paid_amount = 0;
            } else {
                $paid_amount = $postData['service_charge'];
            }

            $updateData = [
                'service_descriptions' => $postData['service_descriptions'],
                'dtp_service_category_id' => $postData['dtp_service_categories'],
                'service_charge' => $postData['service_charge'],
                'paid_status' => $postData['paid_status'],
                'paid_amount' => $paid_amount,
                'payment_mode' => $postData['payment_mode'], // Save the selected payment mode
                'service_date' => $postData['service_date']
            ];

            // Update the service
            $this->DtpModel->updateService($id, $updateData);

            // Append the service ID to the log data
            $logData = [
                'dtp_service_id' => $id,
                'log_data' => json_encode(array_merge(['id' => $id], $updateData)), // Include 'id' in log_data
                'action' => 2, // 2 = Edit
                'made_by_id' => $this->session->userdata('user_id'),
                'made_by_name' => $this->session->userdata('full_name'),
                'created_at' => date('Y-m-d H:i:s')
            ];

            // Insert log entry
            add_log_data('log_dtp_services', $logData);

            if ($paid_amount > 0) {
                $existingTransaction = $this->TransactionModel->get_transaction_by_table_and_id('dtp_services', $id);

                $transaction_data = [
                    'amount' => $paid_amount,
                    'trans_type' => 1, // Credit
                    'payment_method_id' => $postData['payment_mode'],
                    'descriptions' => 'Payment for DTP service',
                    'transaction_for_table' => 'dtp_services',
                    'table_id' => $id,
                    'trans_by' => $this->session->userdata('user_id'),
                    'trans_date' => $postData['service_date'],
                    'created_at' => date('Y-m-d H:i:s')
                ];

                if ($existingTransaction) {
                    // Update the existing transaction
                    $this->TransactionModel->update_transaction($existingTransaction['id'], $transaction_data);
                    $this->session->set_flashdata('payment', 'Payment updated successfully');
                } else {
                    // Insert a new transaction
                    $last_insert_id = $this->TransactionModel->insert_transaction($transaction_data);
                    if ($last_insert_id > 0) {
                        $this->session->set_flashdata('payment', 'Payment added successfully');
                    }
                }
            } else {
                // Delete transaction if paid_amount is 0 and transaction exists
                $existingTransaction = $this->TransactionModel->get_transaction_by_table_and_id('dtp_services', $id);
                if ($existingTransaction) {
                    $this->TransactionModel->delete_transaction($existingTransaction['id']);
                    $this->session->set_flashdata('payment', 'Payment deleted successfully');
                }
            }

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
            'dtp_service_id' => $id,
            'log_data' => json_encode($service), // Log the deleted service details
            'action' => 3, // 3 = Delete
            'made_by_id' => $this->session->userdata('user_id'),
            'made_by_name' => $this->session->userdata('full_name'),
            'created_at' => date('Y-m-d H:i:s')
        ];

        // Insert log entry
        add_log_data('log_dtp_services', $logData);


        // Delete the related transactions from 'transactions'
        $this->db->delete('transactions', ['table_id' => $id, 'transaction_for_table' => 'dtp_services']);

        // Set flash message and redirect
        $this->session->set_flashdata('message', 'Service deleted successfully');
        redirect('admin/dtp');
    }


    public function categories()
    {
        $data['activePage'] = 'dtp_categories';
        $data['dtp_categories'] = $this->DtpModel->getCategories();


        $this->render_admin('admin/dtp/categories/index', $data);
    }

    public function addCategory()
    {
        $data['activePage'] = 'dtp_categories';
        $data['isUpdate'] = false;

        $this->form_validation->set_rules('category_name', 'Category Name', 'required|is_unique[dtp_service_categories.cat_title]');

        if ($this->form_validation->run() === FALSE) {

            $this->render_admin('admin/dtp/categories/add', $data);
        } else {
            $postData = $this->input->post();
            $saveData = [
                'cat_title' => $postData['category_name']
            ];

            // Save the category and get the last inserted ID
            $categoryId = $this->DtpModel->saveCategory($saveData);

            // Prepare log data
            $logData = [
                'service_cat_id' => $categoryId,
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

            $this->render_admin('admin/dtp/categories/add', $data);
        } else {
            $postData = $this->input->post();
            $updateData = [
                'cat_title' => $postData['category_name']
            ];

            $this->DtpModel->updateCategory($id, $updateData);

            // Append the service ID to the log data
            $logData = [
                'service_cat_id' => $id,
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
            'service_cat_id' => $id,
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
