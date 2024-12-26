<?php
defined('BASEPATH') or exit('No direct script access allowed');
class ExpenseController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ExpenseModel');
        $this->load->model('PaymentModel');
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
        $data['paymentMethods'] = $this->PaymentModel->get_all_payment_methods();

        $this->form_validation->set_rules('expense_title', 'Expense Title', 'required');
        $this->form_validation->set_rules('transaction_amount', 'Transaction Amount', 'required');

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
                'documents' => $postData['documents'],
                'is_refunded' => $postData['is_refunded']
            ];
            $this->ExpenseModel->saveExpense($saveData);
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
        $data['paymentMethods'] = $this->PaymentModel->get_all_payment_methods();

        $this->form_validation->set_rules('expense_title', 'Expense Title', 'required');
        $this->form_validation->set_rules('transaction_amount', 'Transaction Amount', 'required');

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
                'note' => $postData['note'],
                'documents' => $postData['documents'],
                'is_refunded' => $postData['is_refunded']
            ];
            $this->ExpenseModel->updateExpense($id, $updateData);
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
