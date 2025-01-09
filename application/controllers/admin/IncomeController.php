<?php
defined('BASEPATH') or exit('No direct script access allowed');

class IncomeController extends CI_Controller
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

        $this->load->view('admin/header', $data);
        $this->load->view('admin/income/index', $data);
        $this->load->view('admin/footer');
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
            $this->load->view('admin/header', $data);
            $this->load->view('admin/income/add', $data);
            $this->load->view('admin/footer');
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
            $this->load->view('admin/header', $data);
            $this->load->view('admin/income/add', $data);
            $this->load->view('admin/footer');
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
        $this->IncomeModel->deleteIncome($id);
        $this->session->set_flashdata('message', 'Income deleted successfully');
        redirect('admin/income');
    }

    public function head()
    {
        $data['activePage'] = 'income_head';
        $data['income_heads'] = $this->IncomeModel->getIncomeHeads();

        $this->load->view('admin/header', $data);
        $this->load->view('admin/income/head/index', $data);
        $this->load->view('admin/footer');
    }

    public function addHead()
    {
        $data['activePage'] = 'income_head';
        $data['isUpdate'] = false;
        $this->form_validation->set_rules('head_title', 'Head Title', 'required|is_unique[income_heads.head_title]');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('admin/header', $data);
            $this->load->view('admin/income/head/add', $data);
            $this->load->view('admin/footer');
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
            $this->load->view('admin/header', $data);
            $this->load->view('admin/income/head/add', $data);
            $this->load->view('admin/footer');
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
        $this->IncomeModel->deleteIncomeHead($id);

        $this->session->set_flashdata('message', 'Income Head deleted successfully');
        redirect('admin/income/head');
    }
}
