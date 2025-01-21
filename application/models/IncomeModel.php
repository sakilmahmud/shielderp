<?php
defined('BASEPATH') or exit('No direct script access allowed');

class IncomeModel extends CI_Model
{

    // Get all income records
    public function getAllIncomes()
    {
        $this->db->select('incomes.*, income_heads.head_title as income_head, payment_methods.title as method_name');
        $this->db->from('incomes');
        $this->db->join('income_heads', 'income_heads.id = incomes.income_head_id', 'left');
        $this->db->join('payment_methods', 'payment_methods.id = incomes.payment_method_id', 'left');
        $this->db->where('incomes.status', 1); // Only active incomes
        $this->db->order_by('incomes.created_at', 'DESC');
        return $this->db->get()->result_array();
    }

    public function getFilteredIncomes($from_date, $to_date, $category_id = null, $payment_method_id = null, $search_value = null, $start = 0, $length = 10)
    {
        $this->db->select('incomes.*, income_heads.head_title, payment_methods.title as method_name');
        $this->db->from('incomes');
        $this->db->join('income_heads', 'income_heads.id = incomes.income_head_id', 'left');
        $this->db->join('payment_methods', 'payment_methods.id = incomes.payment_method_id', 'left');
        $this->db->where('incomes.transaction_date >=', $from_date);
        $this->db->where('incomes.transaction_date <=', $to_date);

        if (!empty($category_id)) {
            $this->db->where('incomes.income_head_id', $category_id);
        }
        if (!is_null($payment_method_id) && $payment_method_id !== '') {
            $this->db->where('incomes.payment_method_id', $payment_method_id);
        }

        if (!empty($search_value)) {
            $this->db->group_start();
            $this->db->like('incomes.income_title', $search_value);
            $this->db->or_like('income_heads.head_title', $search_value);
            $this->db->or_like('payment_methods.title', $search_value);
            $this->db->or_like('incomes.invoice_no', $search_value);
            $this->db->or_like('incomes.transaction_date', $search_value);
            $this->db->group_end();
        }

        $this->db->order_by('incomes.transaction_date', 'DESC');
        $this->db->order_by('incomes.id', 'DESC');
        if ($length != -1) {
            $this->db->limit($length, $start);
        }

        $query = $this->db->get();
        $result['data'] = $query->result_array();

        $this->db->reset_query();
        $this->db->from('incomes');
        $total_records = $this->db->count_all_results();

        $this->db->reset_query();
        $this->db->from('incomes');
        $this->db->join('income_heads', 'income_heads.id = incomes.income_head_id', 'left');
        $this->db->join('payment_methods', 'payment_methods.id = incomes.payment_method_id', 'left');
        $this->db->where('incomes.transaction_date >=', $from_date);
        $this->db->where('incomes.transaction_date <=', $to_date);

        if (!empty($category_id)) {
            $this->db->where('incomes.income_head_id', $category_id);
        }
        if (!is_null($payment_method_id) && $payment_method_id !== '') {
            $this->db->where('incomes.payment_method_id', $payment_method_id);
        }

        if (!empty($search_value)) {
            $this->db->group_start();
            $this->db->like('incomes.income_title', $search_value);
            $this->db->or_like('income_heads.head_title', $search_value);
            $this->db->or_like('payment_methods.title', $search_value);
            $this->db->or_like('incomes.invoice_no', $search_value);
            $this->db->or_like('incomes.transaction_date', $search_value);
            $this->db->group_end();
        }

        $filtered_records = $this->db->count_all_results();
        $result['recordsTotal'] = $total_records;
        $result['recordsFiltered'] = $filtered_records;

        return $result;
    }

    // Get a specific income by ID
    public function getIncome($id)
    {
        $this->db->select('incomes.*');
        $this->db->from('incomes');
        $this->db->where('id', $id);
        return $this->db->get()->row_array();
    }

    // Add a new income record
    public function saveIncome($data)
    {
        $this->db->insert('incomes', $data);
        return $this->db->insert_id(); // Return the ID of the newly inserted record
    }

    // Update an income record
    public function updateIncome($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('incomes', $data);
    }

    // Delete (soft delete) an income record
    public function deleteIncome($id)
    {
        $this->db->where('id', $id);
        return $this->db->update('incomes', ['status' => 0]); // Set status to 0 for soft delete
    }

    public function getIncomeHeads()
    {
        return $this->db->get('income_heads')->result_array();
    }

    public function getIncomeHead($id)
    {
        return $this->db->get_where('income_heads', ['id' => $id])->row_array();
    }

    public function saveIncomeHead($data)
    {
        return $this->db->insert('income_heads', $data);
    }

    public function updateIncomeHead($id, $data)
    {
        return $this->db->where('id', $id)->update('income_heads', $data);
    }

    public function deleteIncomeHead($id)
    {
        return $this->db->where('id', $id)->delete('income_heads');
    }

    public function isIncomeHeadUsed($head_id)
    {
        $this->db->from('incomes');
        $this->db->where('income_head_id', $head_id);
        $count = $this->db->count_all_results();
        return $count > 0; // Return true if the head is in use
    }
}
