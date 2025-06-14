<?php
defined('BASEPATH') or exit('No direct script access allowed');
class ExpenseController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('UserModel');
        $this->load->model('ExpenseModel');
        $this->load->model('TransactionModel');
        $this->load->model('PaymentMethodsModel');
        $this->load->library('form_validation');
        if (!$this->session->userdata('user_id')) {
            redirect('login');
        }
    }

    public function index()
    {
        $data['activePage'] = 'expense';
        $data['categories'] = $this->ExpenseModel->getExpenseHeads(); // For dropdown filters
        $data['payment_methods'] = $this->PaymentMethodsModel->getAll(); // For dropdown filters
        $data['users'] = $this->UserModel->getUsers(array(1, 2));
        $this->render_admin('admin/expense/index', $data);
    }

    public function fetchExpenses()
    {
        $from_date = $this->input->post('from_date', true);
        $to_date = $this->input->post('to_date', true);
        $created_by = $this->input->post('created_by', true);
        $category_id = $this->input->post('category', true);
        $paid_status = $this->input->post('paid_status', true);
        $payment_method_id = $this->input->post('payment_method', true);
        $search_value = $this->input->post('search')['value'] ?? null; // Search term
        $start = $this->input->post('start', true); // Offset for pagination
        $length = $this->input->post('length', true); // Limit for pagination
        $draw = $this->input->post('draw', true); // Draw number for DataTables

        // Default to the last 7 days if no date is selected
        if (empty($from_date)) {
            $from_date = date('Y-m-d', strtotime('-7 days'));
        }
        if (empty($to_date)) {
            $to_date = date('Y-m-d');
        }

        // Fetch filtered data with pagination and search
        $result = $this->ExpenseModel->getFilteredExpenses(
            $from_date,
            $to_date,
            $created_by,
            $category_id,
            $paid_status,
            $payment_method_id,
            $search_value,
            $start,
            $length
        );

        // Totals calculation
        $total_transaction_amount = 0;

        // Format data for DataTables
        $data = [];
        foreach ($result['data'] as $expense) {
            // Accumulate totals
            $total_transaction_amount += $expense['transaction_amount'];

            // Role-based action buttons
            $actions = '<a href="' . base_url('admin/expense/edit/' . $expense['id']) . '" class="btn btn-warning btn-sm">Edit</a>';
            if ($this->session->userdata('role') == 1) {
                $actions .= '
            <a href="' . base_url('admin/expense/delete/' . $expense['id']) . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to delete this expense?\');">Delete</a>';
            }

            $row = [
                $expense['id'],
                $expense['head_title'],
                $expense['expense_title'],
                '₹' . number_format($expense['transaction_amount'], 2),
                $expense['method_name'],
                date('d-m-Y', strtotime($expense['transaction_date'])),
                $expense['created_by_name'],
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
                "total_amount" => '₹' . number_format($total_transaction_amount, 2),
            ]
        ]);
    }


    public function addExpense()
    {
        $data['activePage'] = 'expense';
        $data['isUpdate'] = false;
        $data['expenseHeads'] = $this->ExpenseModel->getAllExpenseHeads();
        $data['paymentMethods'] = $this->PaymentMethodsModel->getAll();

        $this->form_validation->set_rules('expense_title', 'Expense Title', 'required');
        $this->form_validation->set_rules('transaction_amount', 'Transaction Amount', 'required|numeric');

        if ($this->form_validation->run() === FALSE) {

            $this->render_admin('admin/expense/add', $data);
        } else {
            $postData = $this->input->post();
            $saveData = [
                'expense_head_id' => $postData['expense_head_id'],
                'expense_title' => $postData['expense_title'],
                'invoice_no' => $postData['invoice_no'],
                'transaction_date' => $postData['transaction_date'],
                'transaction_amount' => $postData['transaction_amount'],
                'payment_method_id' => $postData['payment_method_id'],
                'note' => $postData['note'],
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date('Y-m-d H:i:s'),
                'status' => 1
            ];

            $expenseId = $this->ExpenseModel->saveExpense($saveData);

            if ($postData['transaction_amount'] > 0) {
                $transaction_data = [
                    'amount' => $postData['transaction_amount'],
                    'trans_type' => 2, // Debit
                    'payment_method_id' => $postData['payment_method_id'],
                    'descriptions' => 'Payment for expense',
                    'transaction_for_table' => 'expenses',
                    'table_id' => $expenseId,
                    'trans_by' => $this->session->userdata('user_id'),
                    'trans_date' => $postData['transaction_date'],
                    'created_at' => date('Y-m-d H:i:s')
                ];

                $this->TransactionModel->insert_transaction($transaction_data);
                $this->session->set_flashdata('payment', 'Transaction added successfully');
            }

            $this->session->set_flashdata('message', 'Expense added successfully');
            redirect('admin/expense');
        }
    }


    public function editExpense($id)
    {
        $data['activePage'] = 'expense';
        $data['isUpdate'] = true;
        $data['expense'] = $this->ExpenseModel->getExpense($id);
        $data['expenseHeads'] = $this->ExpenseModel->getAllExpenseHeads();
        $data['paymentMethods'] = $this->PaymentMethodsModel->getAll();

        $this->form_validation->set_rules('expense_title', 'Expense Title', 'required');
        $this->form_validation->set_rules('transaction_amount', 'Transaction Amount', 'required|numeric');

        if ($this->form_validation->run() === FALSE) {

            $this->render_admin('admin/expense/add', $data);
        } else {
            $postData = $this->input->post();
            $updateData = [
                'expense_head_id' => $postData['expense_head_id'],
                'expense_title' => $postData['expense_title'],
                'invoice_no' => $postData['invoice_no'],
                'transaction_date' => $postData['transaction_date'],
                'transaction_amount' => $postData['transaction_amount'],
                'payment_method_id' => $postData['payment_method_id'],
                'note' => $postData['note']
            ];

            $this->ExpenseModel->updateExpense($id, $updateData);

            $transaction_amount = $postData['transaction_amount'];

            if ($transaction_amount > 0) {
                // Check if a transaction exists for the expense
                $existingTransaction = $this->TransactionModel->get_transaction_by_table_and_id('expenses', $id);

                $transaction_data = [
                    'amount' => $transaction_amount,
                    'trans_type' => 2, // Debit
                    'payment_method_id' => $postData['payment_method_id'],
                    'descriptions' => 'Payment for expense',
                    'transaction_for_table' => 'expenses',
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
                    $this->TransactionModel->insert_transaction($transaction_data);
                    $this->session->set_flashdata('payment', 'Transaction added successfully');
                }
            } else {
                // Delete transaction if amount is 0 and a transaction exists
                $existingTransaction = $this->TransactionModel->get_transaction_by_table_and_id('expenses', $id);
                if ($existingTransaction) {
                    $this->TransactionModel->delete_transaction($existingTransaction['id']);
                    $this->session->set_flashdata('payment', 'Transaction deleted successfully');
                }
            }

            $this->session->set_flashdata('message', 'Expense updated successfully');
            redirect('admin/expense');
        }
    }


    public function deleteExpense($id)
    {
        $current_date_time = date('Y-m-d H:i:s');

        // Fetch the expense to check if it exists
        $expense = $this->ExpenseModel->getExpense($id);
        if (!$expense) {
            $this->session->set_flashdata('error', 'Expense not found.');
            redirect('admin/expense');
        }

        // Log the deletion for audit purposes
        $logExpenseData = [
            'expense_id' => $id,
            'expense_data' => json_encode($expense), // Save the deleted expense data
            'action' => 3, // Delete action
            'made_by' => $this->session->userdata('user_id'),
            'device_data' => $this->input->user_agent(),
            'ip_address' => $this->input->ip_address(),
            'created_at' => $current_date_time
        ];
        $this->db->insert('log_expenses', $logExpenseData);

        // Delete related transactions from 'transactions'
        $this->db->delete('transactions', ['table_id' => $id, 'transaction_for_table' => 'expenses']);

        // Delete the expense
        $this->ExpenseModel->deleteExpense($id);

        // Set a success message and redirect
        $this->session->set_flashdata('message', 'Expense deleted successfully.');
        redirect('admin/expense');
    }




    /** Expense Heads */
    public function head()
    {
        $data['activePage'] = 'expense_head';
        $data['expenseHeads'] = $this->ExpenseModel->getAllExpenseHeads();

        $this->render_admin('admin/expense/head/index', $data);
    }

    public function addHead()
    {
        $data['activePage'] = 'expense_head';
        $data['isUpdate'] = false;

        $this->form_validation->set_rules('head_title', 'Head Title', 'required|is_unique[expense_heads.head_title]');
        if ($this->form_validation->run() === FALSE) {

            $this->render_admin('admin/expense/head/add', $data);
        } else {
            $postData = $this->input->post();
            $saveData = [
                'head_title' => $postData['head_title'],
                'description' => $postData['description']
            ];
            $this->ExpenseModel->saveExpenseHead($saveData);
            $this->session->set_flashdata('message', 'Expense Head added successfully');
            redirect('admin/expense/head');
        }
    }

    public function editHead($id)
    {
        $data['activePage'] = 'expense_head';
        $data['isUpdate'] = true;
        $data['expenseHead'] = $this->ExpenseModel->getExpenseHead($id);

        $this->form_validation->set_rules('head_title', 'Head Title', 'required');
        if ($this->form_validation->run() === FALSE) {

            $this->render_admin('admin/expense/head/add', $data);
        } else {
            $postData = $this->input->post();
            $updateData = [
                'head_title' => $postData['head_title'],
                'description' => $postData['description']
            ];
            $this->ExpenseModel->updateExpenseHead($id, $updateData);
            $this->session->set_flashdata('message', 'Expense Head updated successfully');
            redirect('admin/expense/head');
        }
    }

    public function deleteHead($id)
    {
        // Check if the expense head is in use
        $isUsed = $this->ExpenseModel->isExpenseHeadUsed($id);
        if ($isUsed) {
            $this->session->set_flashdata('error', 'Expense Head cannot be deleted as it is already in use.');
            redirect('admin/expense/head');
        }

        // Delete the expense head
        $this->ExpenseModel->deleteExpenseHead($id);

        // Set a success message and redirect
        $this->session->set_flashdata('message', 'Expense Head deleted successfully.');
        redirect('admin/expense/head');
    }
}
