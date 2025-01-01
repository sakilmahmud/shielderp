<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AccountsController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('AccountsModel');
    }

    public function account_balance()
    {
        $data['activePage'] = 'accounts';

        // Fetch balances grouped by payment methods
        $data['balances'] = $this->AccountsModel->get_balances_by_payment_methods();
        $data['total_balance'] = $this->AccountsModel->get_total_balance();

        // Load the view
        $this->load->view('admin/header', $data);
        $this->load->view('admin/accounts/account_balance', $data);
        $this->load->view('admin/footer');
    }
}
