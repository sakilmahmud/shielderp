<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AccountsController extends MY_Controller
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

        // Default to today's date if not set
        $from_date = $this->input->get('from_date') ?? date('Y-m-d');
        $to_date   = $this->input->get('to_date') ?? date('Y-m-d');

        // Fetch balances
        $data['balances'] = $this->AccountsModel->get_balances_by_payment_methods($from_date, $to_date);
        $data['total_balance'] = $this->AccountsModel->get_total_balance($from_date, $to_date);

        // Pass selected dates to view
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;

        $this->render_admin('admin/accounts/account_balance', $data);
    }

    public function get_payment_method_balance()
    {
        $payment_method_id = $this->input->post('payment_method_id');
        if (!$payment_method_id) {
            echo json_encode(['error' => 'Invalid request']);
            return;
        }

        $this->load->model('AccountsModel');
        $balance = $this->AccountsModel->get_payment_method_balance($payment_method_id);

        echo json_encode(['balance' => $balance]);
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
            $this->render_admin('admin/accounts/transfer_fund', $data);
        } else {
            // Get form input
            $postData = $this->input->post();
            $from_payment_method = $postData['from_payment_method'];
            $amount = $postData['amount'];
            // Get the current balance
            $current_balance = $this->AccountsModel->get_payment_method_balance($from_payment_method);

            if ($amount > $current_balance) {
                $this->session->set_flashdata('error', 'Insufficient balance for transfer!');
                redirect('admin/accounts/transfer_fund');
                return;
            }

            // Process the fund transfer
            $transferData = [
                'from_payment_method' => $postData['from_payment_method'],
                'to_payment_method' => $postData['to_payment_method'],
                'amount' => $postData['amount'],
                'transfer_date' => $postData['transfer_date'],
                'note' => $postData['note'],
                'transferred_by' => $this->session->userdata('user_id'),
            ];

            $result = $this->AccountsModel->transfer_fund($transferData);

            if ($result) {
                $this->session->set_flashdata('message', 'Funds transferred successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to transfer funds. Please try again.');
            }

            redirect('admin/accounts/transfer_fund');
        }
    }

    public function list_fund_transfers()
    {
        $data['activePage'] = 'list_fund_transfers';

        $this->render_admin('admin/accounts/list_fund_transfers', $data);
    }

    public function fetch_fund_transfers()
    {
        // Retrieve search, pagination, and filtering inputs from DataTables
        $from_date = $this->input->post('from_date', true);
        $to_date = $this->input->post('to_date', true);
        $search_value = $this->input->post('search')['value'] ?? null; // Search term
        $start = $this->input->post('start', true); // Offset for pagination
        $length = $this->input->post('length', true); // Limit for pagination
        $draw = $this->input->post('draw', true); // Draw number for DataTables

        // Fetch filtered data with pagination
        $result = $this->AccountsModel->get_filtered_fund_transfers($from_date, $to_date, $search_value, $start, $length);

        // Prepare data for DataTables
        $data = [];
        foreach ($result['data'] as $transfer) {
            $row = [
                $transfer['id'],
                $transfer['payment_method'],
                ($transfer['trans_type'] == 1) ? "Credit" : "Debit",
                '₹' . number_format($transfer['amount'], 2),
                $transfer['descriptions'] ?? 'N/A',
                $transfer['transferred_by'],
                date('d-m-Y', strtotime($transfer['trans_date'])),
                date('d-m-Y H:i', strtotime($transfer['created_at']))
            ];
            $data[] = $row;
        }

        // Return JSON response
        echo json_encode([
            "draw" => intval($draw),
            "recordsTotal" => $result['recordsTotal'], // Total records without filtering
            "recordsFiltered" => $result['recordsFiltered'], // Total records after filtering
            "data" => $data // Processed data
        ]);
    }
}
