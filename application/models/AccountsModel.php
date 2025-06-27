<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AccountsModel extends CI_Model
{
    public function get_balances_by_payment_methods($from_date = null, $to_date = null)
    {
        $this->db->select('pm.title AS payment_method_title, 
        SUM(CASE WHEN t.trans_type = 1 THEN t.amount ELSE 0 END) AS credit, 
        SUM(CASE WHEN t.trans_type = 2 THEN t.amount ELSE 0 END) AS debit
        ');
        $this->db->from('transactions t');
        $this->db->join('payment_methods pm', 'pm.id = t.payment_method_id', 'left');

        // Apply date filter if provided
        if ($from_date && $to_date) {
            $this->db->where('DATE(t.trans_date) >=', $from_date);
            $this->db->where('DATE(t.trans_date) <=', $to_date);
        }
        $this->db->where('t.status', 1);
        $this->db->group_by('t.payment_method_id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_payment_method_balance($payment_method_id)
    {
        $this->db->select('
        SUM(CASE WHEN trans_type = 1 THEN amount ELSE 0 END) 
        - SUM(CASE WHEN trans_type = 2 THEN amount ELSE 0 END) AS balance
    ');
        $this->db->from('transactions');
        $this->db->where('payment_method_id', $payment_method_id);
        $this->db->where('status', 1); // Only active transactions
        $query = $this->db->get();
        $result = $query->row_array();

        return $result['balance'] ?? 0; // Return balance, or 0 if null
    }


    public function get_total_balance($from_date = null, $to_date = null)
    {
        $this->db->select('
        SUM(CASE WHEN trans_type = 1 THEN amount ELSE 0 END) AS total_credit, 
        SUM(CASE WHEN trans_type = 2 THEN amount ELSE 0 END) AS total_debit
    ');
        $this->db->from('transactions');

        // Apply date filter if provided
        if ($from_date && $to_date) {
            $this->db->where('DATE(trans_date) >=', $from_date);
            $this->db->where('DATE(trans_date) <=', $to_date);
        }

        $query = $this->db->get()->row_array();
        return $query['total_credit'] - $query['total_debit'];
    }

    public function transfer_fund($data)
    {
        $this->db->trans_begin();

        // Deduct amount from "from_payment_method"
        $deductData = [
            'amount' => $data['amount'],
            'trans_type' => 2, // Debit
            'payment_method_id' => $data['from_payment_method'],
            'descriptions' => 'Fund transfer to payment method ID: ' . $data['to_payment_method'],
            'transaction_for_table' => 'fund_transfer',
            'table_id' => 0,
            'trans_by' => $data['transferred_by'],
            'trans_date' => $data['transfer_date'],
            'created_at' => date('Y-m-d H:i:s')
        ];
        $this->db->insert('transactions', $deductData);

        // Add amount to "to_payment_method"
        $addData = [
            'amount' => $data['amount'],
            'trans_type' => 1, // Credit
            'payment_method_id' => $data['to_payment_method'],
            'descriptions' => 'Fund received from payment method ID: ' . $data['from_payment_method'],
            'transaction_for_table' => 'fund_transfer',
            'table_id' => 0,
            'trans_by' => $data['transferred_by'],
            'trans_date' => $data['transfer_date'],
            'created_at' => date('Y-m-d H:i:s')
        ];
        $this->db->insert('transactions', $addData);

        // Commit or rollback transaction
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function get_filtered_fund_transfers($from_date = null, $to_date = null, $search_value = null, $start = 0, $length = 10)
    {
        // Base query
        $this->db->select('t.id,
        pm.title as payment_method,
        t.amount,
        t.trans_type,
        t.descriptions,
        t.trans_date,
        t.created_at,
        u.full_name as transferred_by');
        $this->db->from('transactions t');
        $this->db->join('payment_methods pm', 'pm.id = t.payment_method_id', 'left');
        $this->db->join('users u', 'u.id = t.trans_by', 'left');
        $this->db->where('t.transaction_for_table', 'fund_transfer');

        // Apply date filters
        if (!empty($from_date)) {
            $this->db->where('t.trans_date >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('t.trans_date <=', $to_date);
        }

        // Apply search filter
        if (!empty($search_value)) {
            $this->db->group_start();
            $this->db->like('pm.title', $search_value);
            $this->db->or_like('u.full_name', $search_value);
            $this->db->or_like('t.descriptions', $search_value);
            $this->db->group_end();
        }

        // Get total records before pagination and filtering
        $recordsTotal = $this->db->count_all_results('', false);

        // Limit and offset for pagination
        if ($length != -1) {
            $this->db->limit($length, $start);
        }
        $this->db->order_by('t.trans_date', 'DESC');
        // Execute query
        $query = $this->db->get();

        // Get total records after filtering
        $recordsFiltered = $this->db->query('SELECT FOUND_ROWS() as count')->row()->count;

        // Return results
        return [
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $query->result_array()
        ];
    }

    /** Reports */

    /** Cashbook */

    public function get_cashbook_entries($from, $to)
    {
        return $this->db->select('t.*, pm.title as payment_method')
            ->from('transactions t')
            ->join('payment_methods pm', 'pm.id = t.payment_method_id', 'left')
            ->where('t.trans_date >=', $from)
            ->where('t.trans_date <=', $to)
            ->where('t.status', 1)
            ->order_by('t.trans_date', 'ASC')
            ->get()
            ->result();
    }

    public function get_opening_balance($from_date)
    {
        $debit = $this->db->select_sum('amount')->where('trans_date <', $from_date)->where('trans_type', 2)->where('status', 1)->get('transactions')->row()->amount;
        $credit = $this->db->select_sum('amount')->where('trans_date <', $from_date)->where('trans_type', 1)->where('status', 1)->get('transactions')->row()->amount;
        return round($credit - $debit, 2);
    }

    public function get_closing_balance($to_date)
    {
        $debit = $this->db->select_sum('amount')->where('trans_date <=', $to_date)->where('trans_type', 2)->where('status', 1)->get('transactions')->row()->amount;
        $credit = $this->db->select_sum('amount')->where('trans_date <=', $to_date)->where('trans_type', 1)->where('status', 1)->get('transactions')->row()->amount;
        return round($credit - $debit, 2);
    }

    /** for Payment Paid */
    public function get_all_suppliers()
    {
        return $this->db->get('suppliers')->result();
    }

    public function get_payment_paid($from, $to, $supplier_id = null, $invoice_no = null)
    {
        $this->db->select('t.trans_date, s.supplier_name, po.invoice_no, t.amount');
        $this->db->from('transactions t');
        $this->db->join('purchase_orders po', 'po.id = t.table_id', 'left');
        $this->db->join('suppliers s', 's.id = po.supplier_id', 'left');
        $this->db->where('t.trans_type', 2);
        $this->db->where('t.transaction_for_table', 'purchase_orders');
        $this->db->where('t.trans_date >=', $from);
        $this->db->where('t.trans_date <=', $to);

        if (!empty($supplier_id)) {
            $this->db->where('po.supplier_id', $supplier_id);
        }

        if (!empty($invoice_no)) {
            $this->db->where('po.invoice_no', $invoice_no);
        }

        return $this->db->get()->result();
    }

    /**for Payment Received */
    public function get_payment_received_entries($from, $to, $customer_id = null, $invoice_no = null)
    {
        $this->db->select('t.trans_date, t.amount, inv.invoice_no, inv.customer_name')
            ->from('transactions t')
            ->join('invoices inv', 'inv.id = t.table_id')
            ->join('customers c', 'c.id = inv.customer_id')
            ->where('t.trans_type', 1)
            ->where('t.transaction_for_table', 'invoices')
            ->where('t.trans_date >=', $from)
            ->where('t.trans_date <=', $to);

        if ($customer_id) {
            $this->db->where('c.id', $customer_id);
        }

        if ($invoice_no) {
            $this->db->like('inv.invoice_no', $invoice_no);
        }

        return $this->db->get()->result();
    }

    public function get_all_customers()
    {
        $this->db->select("id, CASE WHEN id = 5 THEN 'Cash' ELSE customer_name END as name");
        return $this->db->get('customers')->result();
    }


    /** for daily_summary*/
    public function get_daily_summary($from, $to)
    {
        $this->db->select("
            t.trans_date,
            SUM(CASE WHEN t.trans_type = 1 THEN t.amount ELSE 0 END) AS total_in,
            SUM(CASE WHEN t.trans_type = 2 THEN t.amount ELSE 0 END) AS total_out,
            SUM(CASE WHEN t.trans_type = 1 AND pm.type = 1 THEN t.amount ELSE 0 END) AS cash_in,
            SUM(CASE WHEN t.trans_type = 2 AND pm.type = 1 THEN t.amount ELSE 0 END) AS cash_out,
            SUM(CASE WHEN t.trans_type = 1 AND pm.type = 2 THEN t.amount ELSE 0 END) AS bank_in,
            SUM(CASE WHEN t.trans_type = 2 AND pm.type = 2 THEN t.amount ELSE 0 END) AS bank_out
        ");
        $this->db->from('transactions t');
        $this->db->join('payment_methods pm', 't.payment_method_id = pm.id', 'left');
        $this->db->where('t.trans_date >=', $from);
        $this->db->where('t.trans_date <=', $to);
        $this->db->where('t.status', 1); // Optional: only active transactions
        $this->db->where('pm.status', 1); // Optional: only active payment methods
        $this->db->group_by('t.trans_date');
        $this->db->order_by('t.trans_date', 'ASC');

        $query = $this->db->get();
        return $query->result();
    }

    /** for profit_loss */
    public function get_total_purchase($from, $to)
    {
        return $this->db->select_sum('total_amount')
            ->from('purchase_orders')
            ->where('purchase_date >=', $from)
            ->where('purchase_date <=', $to)
            ->where('status', 1)
            ->get()->row()->total_amount ?? 0;
    }

    public function get_total_expense($from, $to)
    {
        return $this->db->select_sum('transaction_amount')
            ->from('expenses')
            ->where('transaction_date >=', $from)
            ->where('transaction_date <=', $to)
            ->get()->row()->transaction_amount ?? 0;
    }

    public function get_total_invoice($from, $to)
    {
        return $this->db->select_sum('total_amount')
            ->from('invoices')
            ->where('invoice_date >=', $from)
            ->where('invoice_date <=', $to)
            ->where('status', 1)
            ->get()->row()->total_amount ?? 0;
    }

    public function get_total_income($from, $to)
    {
        return $this->db->select_sum('transaction_amount')
            ->from('incomes')
            ->where('transaction_date >=', $from)
            ->where('transaction_date <=', $to)
            ->get()->row()->transaction_amount ?? 0;
    }

    public function get_current_stock_value()
    {
        $this->db->where('status', 1);
        $products = $this->db->get('products')->result_array();

        $total_value = 0;

        foreach ($products as $product) {
            $product_id = $product['id'];
            $purchase_price = $product['purchase_price'];

            // Total quantity purchased
            $this->db->select_sum('quantity');
            $this->db->from('stock_management');
            $this->db->where('product_id', $product_id);
            $stock = $this->db->get()->row_array();
            $total_quantity = $stock['quantity'] ?? 0;

            // Total quantity sold in date range
            $this->db->select_sum('quantity');
            $this->db->from('invoice_details');
            $this->db->where('product_id', $product_id);
            $sold = $this->db->get()->row_array();
            $total_sold_quantity = $sold['quantity'] ?? 0;

            // Remaining stock and valuation
            $remaining_qty = $total_quantity - $total_sold_quantity;
            $total_value += ($purchase_price * $remaining_qty);
        }

        return $total_value;
    }

    public function get_current_sold_value($from, $to)
    {
        // Step 1: Get all sold products with sum of quantity and final_price
        $this->db->select('product_id, SUM(quantity) as total_qty, SUM(final_price) as total_sales');
        $this->db->from('invoice_details');
        /* $this->db->where('invoice_date >=', $from);
        $this->db->where('invoice_date <=', $to); */
        $this->db->group_by('product_id');
        $sold_products = $this->db->get()->result_array();

        $total_purchase_amount = 0;
        $total_sales_amount = 0;

        // Step 2: Loop through sold products
        foreach ($sold_products as $item) {
            $product_id = $item['product_id'];
            $total_qty = $item['total_qty'];
            $total_sales = $item['total_sales'];

            // Get purchase price and name from products table
            $this->db->select('name, purchase_price');
            $this->db->from('products');
            $this->db->where('id', $product_id);
            $product = $this->db->get()->row_array();

            // If product not found, skip
            if (!$product) continue;

            $purchase_price = $product['purchase_price'] ?? 0;

            // Calculate for this product
            $purchase_amount = $purchase_price * $total_qty;

            /* // Only print if sold at a loss
            if ($total_sales < $purchase_amount) {
                $loss = $purchase_amount - $total_sales;
                echo $product['name'] . " ($product_id) × $total_qty = ₹" . number_format($purchase_amount, 2) .
                    " | Sale: ₹" . number_format($total_sales, 2) .
                    " | Loss: ₹" . number_format($loss, 2) . "<br>";
            } */


            $total_purchase_amount += $purchase_amount;
            $total_sales_amount += $total_sales;
        }

        $net_profit = $total_sales_amount - $total_purchase_amount;

        return [
            'total_purchase_amount' => $total_purchase_amount,
            'total_sales_amount' => $total_sales_amount,
            'net_profit' => $net_profit
        ];
    }

    /**  for balance_sheet*/
    public function get_balance_sheet_data($type, $as_on)
    {
        // This is just a placeholder logic; adapt to your real structure.
        if ($type == 'assets') {
            return [
                (object)['title' => 'Cash', 'amount' => 15000],
                (object)['title' => 'Bank', 'amount' => 40000],
                (object)['title' => 'Accounts Receivable', 'amount' => 12000],
            ];
        }

        if ($type == 'liabilities') {
            return [
                (object)['title' => 'Accounts Payable', 'amount' => 10000],
                (object)['title' => 'Loans', 'amount' => 20000],
            ];
        }

        if ($type == 'equity') {
            return [
                (object)['title' => 'Owner’s Equity', 'amount' => 37000],
            ];
        }

        return [];
    }

    /** for Ledger */
    public function get_ledger_total($table)
    {
        $query = $this->db
            ->select_sum('amount')
            ->where('transaction_for_table', $table)
            ->get('transactions');

        return $query->row()->amount ?? 0;
    }

    // Customer Ledger
    public function get_customer_ledger($from, $to, $party_id = null)
    {
        $this->db->select('t.*, i.invoice_no, i.customer_name');
        $this->db->from('transactions t');
        $this->db->join('invoices i', 'i.id = t.table_id', 'left');
        $this->db->join('customers c', 'c.id = i.customer_id', 'left');
        $this->db->where('t.transaction_for_table', 'invoices');
        $this->db->where('t.trans_date >=', $from);
        $this->db->where('t.trans_date <=', $to);
        if ($party_id) {
            $this->db->where('i.customer_id', $party_id);
        }
        $this->db->order_by('t.trans_date', 'ASC');
        return $this->db->get()->result();
    }


    // Supplier Ledger
    public function get_supplier_ledger($from, $to, $party_id = null)
    {
        $this->db->select('t.*, p.invoice_no, p.purchase_date, s.supplier_name')
            ->from('transactions t')
            ->join('purchase_orders p', 't.table_id = p.id', 'left')
            ->join('suppliers s', 'p.supplier_id = s.id', 'left')
            ->where('t.transaction_for_table', 'purchase_orders')
            ->where('t.amount >', 0)
            ->where('t.trans_date >=', $from)
            ->where('t.trans_date <=', $to);

        if (!empty($party_id)) {
            $this->db->where('p.supplier_id', $party_id);
        }

        return $this->db->order_by('t.trans_date', 'asc')->get()->result();
    }

    // Income Ledger
    public function get_income_ledger($from, $to, $head_id = null)
    {
        $this->db->select('t.*, i.invoice_no, i.income_title, ih.head_title, pm.type as pm_type')
            ->from('transactions t')
            ->join('incomes i', 't.table_id = i.id', 'left')
            ->join('income_heads ih', 'i.income_head_id = ih.id', 'left')
            ->join('payment_methods pm', 't.payment_method_id = pm.id', 'left')
            ->where('t.transaction_for_table', 'incomes')
            ->where('t.trans_date >=', $from)
            ->where('t.trans_date <=', $to);

        if ($head_id) {
            $this->db->where('i.income_head_id', $head_id);
        }

        return $this->db->order_by('t.trans_date', 'asc')->get()->result();
    }


    public function get_income_heads()
    {
        return $this->db->get('income_heads')->result();
    }

    // Expense Ledger
    public function get_expense_ledger($from, $to, $head_id = null)
    {
        $this->db->select('t.*, e.invoice_no, e.expense_title, eh.head_title, pm.type as pm_type')
            ->from('transactions t')
            ->join('expenses e', 't.table_id = e.id', 'left')
            ->join('expense_heads eh', 'e.expense_head_id = eh.id', 'left')
            ->join('payment_methods pm', 't.payment_method_id = pm.id', 'left')
            ->where('t.transaction_for_table', 'expenses')
            ->where('t.trans_date >=', $from)
            ->where('t.trans_date <=', $to);

        if ($head_id) {
            $this->db->where('e.expense_head_id', $head_id);
        }

        return $this->db->order_by('t.trans_date', 'asc')->get()->result();
    }

    public function get_expense_heads()
    {
        return $this->db->get('expense_heads')->result();
    }
}
