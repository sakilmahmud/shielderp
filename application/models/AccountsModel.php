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
}
