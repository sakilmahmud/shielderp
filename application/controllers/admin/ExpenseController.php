<?php
defined('BASEPATH') or exit('No direct script access allowed');
class ExpenseController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ExpenseModel');
        $this->load->model('TransactionModel');
        $this->load->model('PaymentMethodsModel');
        $this->load->library('form_validation');
        if (!$this->session->userdata('user_id')) {
            redirect('login');
        }
    }

    /** Expenses */
    public function index()
    {
        $data['activePage'] = 'expense';
        $data['expenses'] = $this->ExpenseModel->getAllExpenses();
        $this->load->view('admin/header', $data);
        $this->load->view('admin/expense/index', $data);
        $this->load->view('admin/footer');
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
            $this->load->view('admin/header', $data);
            $this->load->view('admin/expense/add', $data);
            $this->load->view('admin/footer');
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
            $this->load->view('admin/header', $data);
            $this->load->view('admin/expense/add', $data);
            $this->load->view('admin/footer');
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
        $this->ExpenseModel->deleteExpense($id);
        $this->session->set_flashdata('message', 'Expense deleted successfully');
        redirect('admin/expense');
    }



    /** Expense Heads */
    public function head()
    {
        $data['activePage'] = 'expense_head';
        $data['expenseHeads'] = $this->ExpenseModel->getAllExpenseHeads();
        $this->load->view('admin/header', $data);
        $this->load->view('admin/expense/head/index', $data);
        $this->load->view('admin/footer');
    }

    public function addHead()
    {
        $data['activePage'] = 'expense_head';
        $data['isUpdate'] = false;

        $this->form_validation->set_rules('head_title', 'Head Title', 'required|is_unique[expense_heads.head_title]');
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('admin/header', $data);
            $this->load->view('admin/expense/head/add', $data);
            $this->load->view('admin/footer');
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
            $this->load->view('admin/header', $data);
            $this->load->view('admin/expense/head/add', $data);
            $this->load->view('admin/footer');
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
        $this->ExpenseModel->deleteExpenseHead($id);
        $this->session->set_flashdata('message', 'Expense Head deleted successfully');
        redirect('admin/expense/head');
    }
}
