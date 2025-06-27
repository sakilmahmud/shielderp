<?php
defined('BASEPATH') or exit('No direct script access allowed');

class IncomeController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('IncomeModel');
        $this->load->model('TransactionModel');
        $this->load->model('PaymentMethodsModel');
        $this->load->library('form_validation');
        if (!$this->session->userdata('user_id')) {
            redirect('login');
        }
    }

    public function index()
    {
        $data['activePage'] = 'income';
        $data['incomes'] = $this->IncomeModel->getAllIncomes();

        $this->render_admin('admin/income/index', $data);
    }

    public function fetchIncomes()
    {
        $from_date = $this->input->post('from_date', true);
        $to_date = $this->input->post('to_date', true);
        $category_id = $this->input->post('category', true);
        $payment_method_id = $this->input->post('payment_method', true);
        $search_value = $this->input->post('search')['value'] ?? null;
        $start = $this->input->post('start', true);
        $length = $this->input->post('length', true);
        $draw = $this->input->post('draw', true);

        // Default date range to the last 7 days
        if (empty($from_date)) {
            $from_date = date('Y-m-d', strtotime('-7 days'));
        }
        if (empty($to_date)) {
            $to_date = date('Y-m-d');
        }

        $result = $this->IncomeModel->getFilteredIncomes(
            $from_date,
            $to_date,
            $category_id,
            $payment_method_id,
            $search_value,
            $start,
            $length
        );

        $total_transaction_amount = 0;
        $data = [];

        foreach ($result['data'] as $income) {
            $total_transaction_amount += $income['transaction_amount'];

            $actions = '<a href="' . base_url('admin/income/edit/' . $income['id']) . '" class="btn btn-warning btn-sm">Edit</a>';
            if ($this->session->userdata('role') == 1) {
                $actions .= '<a href="' . base_url('admin/income/delete/' . $income['id']) . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to delete this income?\');">Delete</a>';
            }

            $data[] = [
                $income['id'],
                $income['head_title'],
                $income['income_title'],
                '₹' . number_format($income['transaction_amount'], 2),
                $income['method_name'],
                $income['invoice_no'],
                date('d-m-Y', strtotime($income['transaction_date'])),
                $actions
            ];
        }

        echo json_encode([
            "draw" => intval($draw),
            "recordsTotal" => $result['recordsTotal'],
            "recordsFiltered" => $result['recordsFiltered'],
            "data" => $data,
            "footer" => [
                "total_amount" => '₹' . number_format($total_transaction_amount, 2),
            ]
        ]);
    }

    public function addIncome()
    {
        $data['activePage'] = 'income';
        $data['isUpdate'] = false;
        $data['income_heads'] = $this->IncomeModel->getIncomeHeads(); // Fetch available income heads
        $data['payment_methods'] = $this->PaymentMethodsModel->getAll(); // Load payment methods
        /* echo "<pre>";
        print_r($data);
        die; */
        $this->form_validation->set_rules('income_title', 'Income Title', 'required');
        $this->form_validation->set_rules('transaction_amount', 'Transaction Amount', 'required|numeric');

        if ($this->form_validation->run() === FALSE) {

            $this->render_admin('admin/income/add', $data);
        } else {
            $postData = $this->input->post();
            $saveData = [
                'income_head_id' => $postData['income_head_id'],
                'income_title' => $postData['income_title'],
                'invoice_no' => $postData['invoice_no'],
                'transaction_date' => $postData['transaction_date'],
                'transaction_amount' => $postData['transaction_amount'],
                'payment_method_id' => $postData['payment_method_id'],
                'note' => $postData['note'],
                'created_by' => $this->session->userdata('user_id'),
                'documents' => $postData['documents'], // Assume file upload is handled
                'is_refunded' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'status' => 1,
            ];

            $incomeId = $this->IncomeModel->saveIncome($saveData);

            if ($postData['transaction_amount'] > 0) {
                $transaction_data = [
                    'amount' => $postData['transaction_amount'],
                    'trans_type' => 1, // Credit
                    'payment_method_id' => $postData['payment_method_id'],
                    'descriptions' => 'Payment for others income',
                    'transaction_for_table' => 'incomes',
                    'table_id' => $incomeId,
                    'trans_by' => $this->session->userdata('user_id'), // User ID
                    'trans_date' => $postData['transaction_date'],
                    'created_at' => date('Y-m-d H:i:s')
                ];

                $last_insert_id = $this->TransactionModel->insert_transaction($transaction_data);
                if ($last_insert_id > 0) {
                    $this->session->set_flashdata('payment', 'Payment added successfully');
                }
            }

            $this->session->set_flashdata('message', 'Income added successfully');
            redirect('admin/income');
        }
    }

    public function editIncome($id)
    {
        $data['activePage'] = 'income';
        $data['isUpdate'] = true;
        $data['income'] = $this->IncomeModel->getIncome($id);
        $data['income_heads'] = $this->IncomeModel->getIncomeHeads();
        $data['payment_methods'] = $this->PaymentMethodsModel->getAll(); // Load payment methods

        $this->form_validation->set_rules('income_title', 'Income Title', 'required');
        $this->form_validation->set_rules('transaction_amount', 'Transaction Amount', 'required|numeric');

        if ($this->form_validation->run() === FALSE) {

            $this->render_admin('admin/income/add', $data);
        } else {
            $postData = $this->input->post();
            $updateData = [
                'income_head_id' => $postData['income_head_id'],
                'income_title' => $postData['income_title'],
                'invoice_no' => $postData['invoice_no'],
                'transaction_date' => $postData['transaction_date'],
                'transaction_amount' => $postData['transaction_amount'],
                'payment_method_id' => $postData['payment_method_id'],
                'note' => $postData['note'],
                //'documents' => $postData['documents'], // Assume file upload is handled
            ];

            // Update income
            $this->IncomeModel->updateIncome($id, $updateData);

            // Manage transaction
            $transaction_amount = $postData['transaction_amount'];

            if ($transaction_amount > 0) {
                // Check if a transaction exists for the income
                $existingTransaction = $this->TransactionModel->get_transaction_by_table_and_id('incomes', $id);

                $transaction_data = [
                    'amount' => $transaction_amount,
                    'trans_type' => 1, // Credit
                    'payment_method_id' => $postData['payment_method_id'],
                    'descriptions' => 'Payment for others income',
                    'transaction_for_table' => 'incomes',
                    'table_id' => $id,
                    'trans_by' => $this->session->userdata('user_id'),
                    'trans_date' => $postData['transaction_date'],
                    'created_at' => date('Y-m-d H:i:s')
                ];

                if ($existingTransaction) {
                    // Update the existing transaction
                    $this->TransactionModel->update_transaction($existingTransaction['id'], $transaction_data);
                    $this->session->set_flashdata('payment', 'Transaction updated successfully');
                } else {
                    // Insert a new transaction
                    $last_insert_id = $this->TransactionModel->insert_transaction($transaction_data);
                    if ($last_insert_id > 0) {
                        $this->session->set_flashdata('payment', 'Transaction added successfully');
                    }
                }
            } else {
                // Delete transaction if amount is 0 and a transaction exists
                $existingTransaction = $this->TransactionModel->get_transaction_by_table_and_id('incomes', $id);
                if ($existingTransaction) {
                    $this->TransactionModel->delete_transaction($existingTransaction['id']);
                    $this->session->set_flashdata('payment', 'Transaction deleted successfully');
                }
            }

            $this->session->set_flashdata('message', 'Income updated successfully');
            redirect('admin/income');
        }
    }

    public function deleteIncome($id)
    {
        $current_date_time = date('Y-m-d H:i:s');

        // Fetch the income to check if it exists
        $income = $this->IncomeModel->getIncome($id);
        if (!$income) {
            $this->session->set_flashdata('error', 'Income not found.');
            redirect('admin/income');
        }

        // Log the deletion for audit purposes
        $logIncomeData = [
            'income_id' => $id,
            'income_data' => json_encode($income), // Save the deleted income data
            'action' => 3, // Delete action
            'made_by' => $this->session->userdata('user_id'),
            'device_data' => $this->input->user_agent(),
            'ip_address' => $this->input->ip_address(),
            'created_at' => $current_date_time
        ];
        $this->db->insert('log_incomes', $logIncomeData);

        // Delete related transactions from 'transactions'
        $this->db->delete('transactions', ['table_id' => $id, 'transaction_for_table' => 'income']);

        // Delete the income
        $this->IncomeModel->deleteIncome($id);

        // Set a success message and redirect
        $this->session->set_flashdata('message', 'Income deleted successfully.');
        redirect('admin/income');
    }


    public function head()
    {
        $data['activePage'] = 'income_head';
        $data['income_heads'] = $this->IncomeModel->getIncomeHeads();


        $this->render_admin('admin/income/head/index', $data);
    }

    public function addHead()
    {
        $data['activePage'] = 'income_head';
        $data['isUpdate'] = false;
        $this->form_validation->set_rules('head_title', 'Head Title', 'required|is_unique[income_heads.head_title]');

        if ($this->form_validation->run() === FALSE) {

            $this->render_admin('admin/income/head/add', $data);
        } else {
            $saveData = [
                'head_title' => $this->input->post('head_title'),
                'description' => $this->input->post('description'),
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ];

            $this->IncomeModel->saveIncomeHead($saveData);

            $this->session->set_flashdata('message', 'Income Head added successfully');
            redirect('admin/income/head');
        }
    }

    public function editHead($id)
    {
        $data['activePage'] = 'income_head';
        $data['income_head'] = $this->IncomeModel->getIncomeHead($id);
        $data['isUpdate'] = true;
        $this->form_validation->set_rules('head_title', 'Head Title', 'required');

        if ($this->form_validation->run() === FALSE) {

            $this->render_admin('admin/income/head/add', $data);
        } else {
            $updateData = [
                'head_title' => $this->input->post('head_title'),
                'description' => $this->input->post('description'),
            ];

            $this->IncomeModel->updateIncomeHead($id, $updateData);

            $this->session->set_flashdata('message', 'Income Head updated successfully');
            redirect('admin/income/head');
        }
    }

    public function deleteHead($id)
    {
        // Check if the income head is in use
        $isUsed = $this->IncomeModel->isIncomeHeadUsed($id);
        if ($isUsed) {
            $this->session->set_flashdata('error', 'Income Head cannot be deleted as it is already in use.');
            redirect('admin/income/head');
        }

        // Delete the income head
        $this->IncomeModel->deleteIncomeHead($id);

        // Set a success message and redirect
        $this->session->set_flashdata('message', 'Income Head deleted successfully.');
        redirect('admin/income/head');
    }
}
