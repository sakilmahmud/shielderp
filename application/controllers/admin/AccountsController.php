<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AccountsController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('AccountsModel');
        $this->load->library('form_validation');

        if (!$this->session->userdata('user_id')) {
            redirect('login');
        }
    }

    public function account_balance()
    {
        $data['activePage'] = 'accounts';

        // Get date range from user input (default to null if not set)
        $from_date = $this->input->get('from_date') ? $this->input->get('from_date') : null;
        $to_date = $this->input->get('to_date') ? $this->input->get('to_date') : null;

        // Pass the date range to the model
        $data['balances'] = $this->AccountsModel->get_balances_by_payment_methods($from_date, $to_date);
        $data['total_balance'] = $this->AccountsModel->get_total_balance($from_date, $to_date);

        // Retain the selected date range for display in the view
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;

        // Load the view
        $this->load->view('admin/header', $data);
        $this->load->view('admin/accounts/account_balance', $data);
        $this->load->view('admin/footer');
    }

    public function transfer_fund()
    {
        $data['activePage'] = 'transfer_fund';

        // Load payment methods
        $this->load->model('PaymentMethodsModel');
        $data['paymentMethods'] = $this->PaymentMethodsModel->getAll();

        $this->form_validation->set_rules('from_payment_method', 'From Payment Method', 'required');
        $this->form_validation->set_rules('to_payment_method', 'To Payment Method', 'required');
        $this->form_validation->set_rules('amount', 'Amount', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('transfer_date', 'Transfer Date', 'required');

        if ($this->form_validation->run() === FALSE) {
            // Load the view
            $this->load->view('admin/header', $data);
            $this->load->view('admin/accounts/transfer_fund', $data);
            $this->load->view('admin/footer');
        } else {
            // Get form input
            $postData = $this->input->post();

            // Process the fund transfer
            $transferData = [
                'from_payment_method' => $postData['from_payment_method'],
                'to_payment_method' => $postData['to_payment_method'],
                'amount' => $postData['amount'],
                'transfer_date' => $postData['transfer_date'],
                'note' => $postData['note'],
                'transferred_by' => $this->session->userdata('user_id'),
            ];

            // Call model to handle the transaction
            $this->load->model('AccountsModel');
            $result = $this->AccountsModel->transfer_fund($transferData);

            if ($result) {
                $this->session->set_flashdata('message', 'Funds transferred successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to transfer funds. Please try again.');
            }

            redirect('admin/accounts/transfer_fund');
        }
    }
}
