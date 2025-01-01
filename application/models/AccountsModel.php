<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AccountsModel extends CI_Model
{
    public function get_balances_by_payment_methods()
    {
        $this->db->select('
        pm.title AS payment_method_title, 
        SUM(CASE WHEN t.trans_type = 1 THEN t.amount ELSE 0 END) AS credit, 
        SUM(CASE WHEN t.trans_type = 2 THEN t.amount ELSE 0 END) AS debit
    ');
        $this->db->from('transactions t');
        $this->db->join('payment_methods pm', 'pm.id = t.payment_method_id', 'left');
        $this->db->group_by('t.payment_method_id');
        $query = $this->db->get();
        return $query->result_array();
    }


    public function get_total_balance()
    {
        $this->db->select('SUM(CASE WHEN trans_type = 1 THEN amount ELSE 0 END) AS total_credit, SUM(CASE WHEN trans_type = 2 THEN amount ELSE 0 END) AS total_debit');
        $this->db->from('transactions');
        $query = $this->db->get()->row_array();
        return $query['total_credit'] - $query['total_debit'];
    }
}
