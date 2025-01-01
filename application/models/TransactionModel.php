<?php
class TransactionModel extends CI_Model
{

    public function insert_transaction($data)
    {
        $this->db->insert('transactions', $data);
        return $this->db->insert_id();
    }

    public function update_transaction($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('transactions', $data);
    }

    // Method to get a specific transactions by ID
    public function get_transactions_by_id($id)
    {
        $this->db->select('*');
        $this->db->from('transactions');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    // Get payment details by invoice ID
    public function get_payment_by_invoice($invoice_id)
    {
        $this->db->select('transactions.*, payment_methods.title AS payment_mode_title');
        $this->db->from('transactions');
        $this->db->join('payment_methods', 'payment_methods.id = transactions.payment_method_id', 'left'); // Join with payment_methods table
        $this->db->where('transactions.table_id', $invoice_id);
        $this->db->where('transactions.trans_type', 1); // Only get credit transactions
        $this->db->where('transactions.transaction_for_table', 'invoices'); // Filter for 'invoices' table
        $this->db->where('transactions.status', 1); // Only active transactions

        return $this->db->get()->result_array();
    }

    // Get total paid amount by invoice
    public function get_total_paid_by_invoice($invoice_id)
    {
        $this->db->select_sum('amount');
        $this->db->where('table_id', $invoice_id);
        $this->db->where('transaction_for_table', 'invoices');
        $this->db->where('trans_type', 1); // Credit
        $this->db->where('status', 1);
        return $this->db->get('transactions')->row()->amount;
    }

    // Get payment method name by ID
    public function get_payment_method_name($payment_method_id)
    {
        return $this->db->where('id', $payment_method_id)->get('payment_methods')->row()->title;
    }
}
