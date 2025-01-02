<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AccountsModel extends CI_Model
{
    public function get_balances_by_payment_methods($from_date = null, $to_date = null)
    {
        $this->db->select('
        pm.title AS payment_method_title, 
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

        $this->db->group_by('t.payment_method_id');
        $query = $this->db->get();
        return $query->result_array();
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
}
