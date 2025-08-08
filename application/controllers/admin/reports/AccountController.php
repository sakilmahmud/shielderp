<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AccountController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['AccountsModel']); // if these exist
        $this->load->library('form_validation');
        if (!$this->session->userdata('username')) {
            redirect('login');
        }
    }

    public function index()
    {
        $data['activePage'] = 'report_accounts';

        $this->render_admin('admin/reports/accounts/index', $data);
    }

    public function cashbook()
    {
        $data['activePage'] = 'cashbook';

        // Get from/to dates or use default values
        $from = $this->input->get('from') ?? date('Y-m-01');
        $to   = $this->input->get('to') ?? date('Y-m-d');

        $data['from'] = $from;
        $data['to'] = $to;

        // Get transactions and balances
        $transactions = $this->AccountsModel->get_cashbook_entries($from, $to);
        $opening_balance = $this->AccountsModel->get_opening_balance($from);

        // Calculate running balance
        $running_balance = $opening_balance;
        foreach ($transactions as &$t) {
            if ($t->trans_type == 1) { // Credit
                $running_balance += $t->amount;
            } elseif ($t->trans_type == 2) { // Debit
                $running_balance -= $t->amount;
            }
            $t->running_balance = $running_balance;
        }

        $data['transactions'] = $transactions;
        $data['opening_balance'] = $opening_balance;
        $data['closing_balance'] = $running_balance;


        $this->render_admin('admin/reports/accounts/cashbook', $data);
    }

    public function export_cashbook($format)
    {
        $from = $this->input->get('from') ?: date('Y-m-01');
        $to   = $this->input->get('to') ?: date('Y-m-d');

        $transactions = $this->AccountsModel->get_cashbook_entries($from, $to);
        $opening_balance = $this->AccountsModel->get_opening_balance($from);
        $closing_balance = $this->AccountsModel->get_closing_balance($to);

        if ($format == 'pdf') {
            $data = [
                'transactions' => $transactions,
                'opening_balance' => $opening_balance,
                'closing_balance' => $closing_balance,
                'from' => $from,
                'to' => $to
            ];

            $html = $this->render_admin('admin/reports/accounts/exports/cashbook_pdf', $data, true);
            $this->load->library('pdf');
            $this->pdf->loadHtml($html);
            $this->pdf->setPaper('A4', 'portrait');
            $this->pdf->render();
            $this->pdf->stream('Cashbook_' . $from . '_to_' . $to . '.pdf', array('Attachment' => 0));
        }

        if ($format == 'excel') {
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=Cashbook_{$from}_to_{$to}.xls");

            $data = [
                'transactions' => $transactions,
                'opening_balance' => $opening_balance,
                'closing_balance' => $closing_balance,
                'from' => $from,
                'to' => $to
            ];

            $this->render_admin('admin/reports/accounts/exports/cashbook_excel', $data);
        }
    }

    public function payment_paid()
    {
        $data['activePage'] = 'payment_paid';

        $from = $this->input->get('from') ?: date('Y-m-01');
        $to   = $this->input->get('to') ?: date('Y-m-d');
        $supplier_id = $this->input->get('supplier_id');
        $invoice_no  = $this->input->get('invoice_no');

        $data['suppliers'] = $this->AccountsModel->get_all_suppliers(); // For dropdown
        $data['transactions'] = $this->AccountsModel->get_payment_paid($from, $to, $supplier_id, $invoice_no);
        $data['from'] = $from;
        $data['to'] = $to;
        $data['selected_supplier'] = $supplier_id;
        $data['selected_invoice'] = $invoice_no;


        $this->render_admin('admin/reports/accounts/payment_paid', $data);
    }

    public function export_payment_paid($format)
    {
        $from = $this->input->get('from') ?: date('Y-m-01');
        $to   = $this->input->get('to') ?: date('Y-m-d');
        $supplier_id = $this->input->get('supplier_id');
        $invoice_no = $this->input->get('invoice_no');

        $transactions = $this->AccountsModel->get_payment_paid($from, $to, $supplier_id, $invoice_no);

        if ($format === 'pdf') {
            $data = [
                'transactions' => $transactions,
                'from' => $from,
                'to' => $to
            ];
            $html = $this->render_admin('admin/reports/accounts/exports/payment_paid_pdf', $data, true);

            $this->load->library('pdf');
            $this->pdf->loadHtml($html);
            $this->pdf->setPaper('A4', 'portrait');
            $this->pdf->render();
            $this->pdf->stream("Payment_Paid_{$from}_to_{$to}.pdf", ['Attachment' => 0]);
        }

        if ($format === 'excel') {
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=Payment_Paid_{$from}_to_{$to}.xls");

            $data = [
                'transactions' => $transactions,
                'from' => $from,
                'to' => $to
            ];
            $this->render_admin('admin/reports/accounts/exports/payment_paid_excel', $data);
        }
    }

    public function payment_received()
    {
        $data['activePage'] = 'payment_received';

        $from = $this->input->get('from') ?: date('Y-m-01');
        $to = $this->input->get('to') ?: date('Y-m-d');
        $customer_id = $this->input->get('customer_id');
        $invoice_no = $this->input->get('invoice_no');

        $data['from'] = $from;
        $data['to'] = $to;
        $data['selected_customer'] = $customer_id;
        $data['selected_invoice'] = $invoice_no;

        $data['customers'] = $this->AccountsModel->get_all_customers();
        $data['transactions'] = $this->AccountsModel->get_payment_received_entries($from, $to, $customer_id, $invoice_no);


        $this->render_admin('admin/reports/accounts/payment_received', $data);
    }

    public function export_payment_received($format)
    {
        $from = $this->input->get('from') ?: date('Y-m-01');
        $to = $this->input->get('to') ?: date('Y-m-d');
        $customer_id = $this->input->get('customer_id');
        $invoice_no = $this->input->get('invoice_no');

        $transactions = $this->AccountsModel->get_payment_received_entries($from, $to, $customer_id, $invoice_no);

        if ($format == 'pdf') {
            $data = compact('transactions', 'from', 'to');
            $html = $this->render_admin('admin/reports/accounts/exports/payment_received_pdf', $data, true);
            $this->load->library('pdf');
            $this->pdf->loadHtml($html);
            $this->pdf->setPaper('A4', 'portrait');
            $this->pdf->render();
            $this->pdf->stream("Payment_Received_{$from}_to_{$to}.pdf", array("Attachment" => 0));
        }

        if ($format == 'excel') {
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=Payment_Received_{$from}_to_{$to}.xls");

            $data = compact('transactions', 'from', 'to');
            $this->render_admin('admin/reports/accounts/exports/payment_received_excel', $data);
        }
    }

    public function daily_summary()
    {
        $data['activePage'] = 'daily_summary';

        $from = $this->input->get('from') ?: date('Y-m-01');
        $to   = $this->input->get('to') ?: date('Y-m-d');

        $data['from'] = $from;
        $data['to'] = $to;

        $data['summary'] = $this->AccountsModel->get_daily_summary($from, $to);

        /* echo "<pre>";
        print_r($data['summary']);
        die; */


        $this->render_admin('admin/reports/accounts/daily_summary', $data);
    }

    public function export_daily_summary($format)
    {
        $from = $this->input->get('from') ?: date('Y-m-01');
        $to   = $this->input->get('to') ?: date('Y-m-d');

        $summary = $this->AccountsModel->get_daily_summary($from, $to);

        $data = [
            'summary' => $summary,
            'from' => $from,
            'to' => $to,
        ];

        if ($format == 'pdf') {
            $html = $this->render_admin('admin/reports/accounts/exports/daily_summary_pdf', $data, true);

            $this->load->library('pdf');
            $this->pdf->loadHtml($html);
            $this->pdf->setPaper('A4', 'landscape');
            $this->pdf->render();
            $this->pdf->stream("Daily_Summary_{$from}_to_{$to}.pdf", array("Attachment" => 0));
        }

        if ($format == 'excel') {
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=Daily_Summary_{$from}_to_{$to}.xls");
            $this->render_admin('admin/reports/accounts/exports/daily_summary_excel', $data);
        }
    }

    public function profit_loss_sold_value()
    {
        $from = $this->input->get('from') ?: date('Y-01-01');
        $to   = $this->input->get('to') ?: date('Y-m-d');

        $data = [
            'activePage' => 'profit_loss_sold_value',
            'from' => $from,
            'to' => $to,
            'items'     => $this->AccountsModel->get_current_sold_value($from, $to)
        ];
        /* echo "<pre>";
        print_r($data);
        die; */

        $this->render_admin('admin/reports/accounts/profit_loss_sold_value', $data);
    }

    public function profit_loss()
    {
        $from = $this->input->get('from') ?: date('Y-01-01');
        $to   = $this->input->get('to') ?: date('Y-m-d');

        $data = [
            'activePage' => 'profit_loss',
            'from' => $from,
            'to' => $to,
            'total_purchase'     => $this->AccountsModel->get_total_purchase($from, $to),
            'total_expense'      => $this->AccountsModel->get_total_expense($from, $to),
            'current_stock_value' => $this->AccountsModel->get_current_stock_value($from, $to),
            'total_invoice'      => $this->AccountsModel->get_total_invoice($from, $to),
            'total_income'       => $this->AccountsModel->get_total_income($from, $to),
        ];

        $data['net_profit'] = ($data['total_invoice'] + $data['total_income'] + $data['current_stock_value'])
            - ($data['total_purchase'] + $data['total_expense']);

        $this->render_admin('admin/reports/accounts/profit_loss', $data);
    }

    public function export_profit_loss($format)
    {
        $from = $this->input->get('from') ?: date('Y-01-01');
        $to   = $this->input->get('to') ?: date('Y-m-d');

        $total_income = $this->AccountsModel->get_total_by_type(1, $from, $to);
        $total_expense = $this->AccountsModel->get_total_by_type(2, $from, $to);
        $net_profit = $total_income - $total_expense;

        $data = [
            'from' => $from,
            'to' => $to,
            'total_income' => $total_income,
            'total_expense' => $total_expense,
            'net_profit' => $net_profit,
        ];

        if ($format == 'pdf') {
            $html = $this->render_admin('admin/reports/accounts/exports/profit_loss_pdf', $data, true);

            $this->load->library('pdf');
            $this->pdf->loadHtml($html);
            $this->pdf->setPaper('A4', 'portrait');
            $this->pdf->render();
            $this->pdf->stream("Profit_Loss_{$from}_to_{$to}.pdf", ["Attachment" => 0]);
        } elseif ($format == 'excel') {
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=Profit_Loss_{$from}_to_{$to}.xls");
            $this->render_admin('admin/reports/accounts/exports/profit_loss_excel', $data);
        } else {
            show_404();
        }
    }

    public function balance_sheet()
    {
        $as_on = $this->input->get('as_on') ?: date('Y-m-d');

        $data = [
            'activePage' => 'balance_sheet',
            'as_on' => $as_on,
            'assets' => $this->AccountsModel->get_balance_sheet_data('assets', $as_on),
            'liabilities' => $this->AccountsModel->get_balance_sheet_data('liabilities', $as_on),
            'equity' => $this->AccountsModel->get_balance_sheet_data('equity', $as_on),
        ];


        $this->render_admin('admin/reports/accounts/balance_sheet', $data);
    }

    public function ledger_dashboard()
    {
        $data['activePage'] = 'ledger_dashboard';

        $data['customer_total'] = $this->AccountsModel->get_ledger_total('invoices');
        $data['supplier_total'] = $this->AccountsModel->get_ledger_total('purchase_orders');
        $data['income_total']   = $this->AccountsModel->get_ledger_total('incomes');
        $data['expense_total']  = $this->AccountsModel->get_ledger_total('expenses');

        // echo "<pre>"; print_r($data); die;


        $this->render_admin('admin/reports/accounts/ledger_dashboard', $data);
    }

    public function customer_ledger()
    {
        $data['activePage'] = 'customer_ledger';

        $from = $this->input->get('from') ?: date('Y-m-01');
        $to   = $this->input->get('to') ?: date('Y-m-d');
        $party_id = $this->input->get('party_id');

        $data['from'] = $from;
        $data['to'] = $to;
        $data['party_id'] = $party_id;

        $data['parties'] = $this->AccountsModel->get_all_customers();
        $data['ledger'] = $this->AccountsModel->get_customer_ledger($from, $to, $party_id);


        $this->render_admin('admin/reports/accounts/ledger/customer_ledger', $data);
    }

    public function export_customer_ledger($format)
    {
        $from = $this->input->get('from') ?: date('Y-m-01');
        $to = $this->input->get('to') ?: date('Y-m-d');
        $party_id = $this->input->get('party_id');

        $this->load->model('AccountsModel');
        $ledger = $this->AccountsModel->get_customer_ledger($from, $to, $party_id);

        $data = [
            'from' => $from,
            'to' => $to,
            'party_id' => $party_id,
            'ledger' => $ledger,
        ];

        if ($format == 'pdf') {
            $html = $this->render_admin('admin/reports/accounts/exports/customer_ledger_pdf', $data, true);

            $this->load->library('pdf');
            $this->pdf->loadHtml($html);
            $this->pdf->setPaper('A4', 'landscape');
            $this->pdf->render();
            $this->pdf->stream("Customer_Ledger_{$from}_to_{$to}.pdf", array("Attachment" => 0));
        } elseif ($format == 'excel') {
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=Customer_Ledger_{$from}_to_{$to}.xls");
            $this->render_admin('admin/reports/accounts/exports/customer_ledger_excel', $data);
        } else {
            show_404();
        }
    }

    public function supplier_ledger()
    {
        $data['activePage'] = 'supplier_ledger';

        $from = $this->input->get('from') ?: date('Y-m-01');
        $to = $this->input->get('to') ?: date('Y-m-d');
        $party_id = $this->input->get('party_id');

        $this->load->model('AccountsModel');

        $data['from'] = $from;
        $data['to'] = $to;
        $data['party_id'] = $party_id;
        $data['parties'] = $this->AccountsModel->get_all_suppliers();
        $data['ledger'] = $this->AccountsModel->get_supplier_ledger($from, $to, $party_id);


        $this->render_admin('admin/reports/accounts/ledger/supplier_ledger', $data);
    }
    public function export_supplier_ledger($format)
    {
        $from = $this->input->get('from') ?: date('Y-m-01');
        $to = $this->input->get('to') ?: date('Y-m-d');
        $party_id = $this->input->get('party_id');

        $this->load->model('AccountsModel');
        $ledger = $this->AccountsModel->get_supplier_ledger($from, $to, $party_id);

        $data = [
            'from' => $from,
            'to' => $to,
            'party_id' => $party_id,
            'ledger' => $ledger,
        ];

        if ($format == 'pdf') {
            $html = $this->render_admin('admin/reports/accounts/exports/supplier_ledger_pdf', $data, true);
            $this->load->library('pdf');
            $this->pdf->loadHtml($html);
            $this->pdf->setPaper('A4', 'landscape');
            $this->pdf->render();
            $this->pdf->stream("Supplier_Ledger_{$from}_to_{$to}.pdf", array("Attachment" => 0));
        } elseif ($format == 'excel') {
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=Supplier_Ledger_{$from}_to_{$to}.xls");
            $this->render_admin('admin/reports/accounts/exports/supplier_ledger_excel', $data);
        } else {
            show_404();
        }
    }

    // Income Ledger
    public function income_ledger()
    {
        $data['activePage'] = 'income_ledger';
        $from = $this->input->get('from') ?: date('Y-m-01');
        $to   = $this->input->get('to') ?: date('Y-m-d');
        $head_id = $this->input->get('head_id') ?: '';

        $data['from'] = $from;
        $data['to'] = $to;
        $data['head_id'] = $head_id;
        $data['heads'] = $this->AccountsModel->get_income_heads();
        $data['ledger'] = $this->AccountsModel->get_income_ledger($from, $to, $head_id);


        $this->render_admin('admin/reports/accounts/ledger/income_ledger', $data);
    }

    public function export_income_ledger($format)
    {
        $from = $this->input->get('from') ?: date('Y-m-01');
        $to = $this->input->get('to') ?: date('Y-m-d');
        $head_id = $this->input->get('head_id');

        $ledger = $this->AccountsModel->get_income_ledger($from, $to, $head_id);

        $data = [
            'from' => $from,
            'to' => $to,
            'head_id' => $head_id,
            'ledger' => $ledger,
        ];

        if ($format == 'pdf') {
            $html = $this->render_admin('admin/reports/accounts/exports/income_ledger_pdf', $data, true);
            $this->load->library('pdf');
            $this->pdf->loadHtml($html);
            $this->pdf->setPaper('A4', 'landscape');
            $this->pdf->render();
            $this->pdf->stream("Income_Ledger_{$from}_to_{$to}.pdf", array("Attachment" => 0));
        } elseif ($format == 'excel') {
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=Income_Ledger_{$from}_to_{$to}.xls");
            $this->render_admin('admin/reports/accounts/exports/income_ledger_excel', $data);
        } else {
            show_404();
        }
    }

    // Expense Ledger
    public function expense_ledger()
    {
        $data['activePage'] = 'expense_ledger';
        $from = $this->input->get('from') ?: date('Y-m-01');
        $to   = $this->input->get('to') ?: date('Y-m-d');
        $head_id = $this->input->get('head_id') ?: '';

        $data['from'] = $from;
        $data['to'] = $to;
        $data['head_id'] = $head_id;
        $data['heads'] = $this->AccountsModel->get_expense_heads();
        $data['ledger'] = $this->AccountsModel->get_expense_ledger($from, $to, $head_id);

        $this->render_admin('admin/reports/accounts/ledger/expense_ledger', $data);
    }

    public function export_expense_ledger($format)
    {
        $from = $this->input->get('from') ?: date('Y-m-01');
        $to = $this->input->get('to') ?: date('Y-m-d');
        $head_id = $this->input->get('head_id');

        $ledger = $this->AccountsModel->get_expense_ledger($from, $to, $head_id);

        $data = [
            'from' => $from,
            'to' => $to,
            'head_id' => $head_id,
            'ledger' => $ledger,
        ];

        if ($format == 'pdf') {
            $html = $this->render_admin('admin/reports/accounts/exports/expense_ledger_pdf', $data, true);
            $this->load->library('pdf');
            $this->pdf->loadHtml($html);
            $this->pdf->setPaper('A4', 'landscape');
            $this->pdf->render();
            $this->pdf->stream("Expense_Ledger_{$from}_to_{$to}.pdf", array("Attachment" => 0));
        } elseif ($format == 'excel') {
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=Expense_Ledger_{$from}_to_{$to}.xls");
            $this->render_admin('admin/reports/accounts/exports/expense_ledger_excel', $data);
        } else {
            show_404();
        }
    }

    public function tax() {}
    public function chart_of_accounts() {}
}
