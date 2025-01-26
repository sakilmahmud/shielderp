<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ExpenseModel extends CI_Model
{
    public function getAllExpenses()
    {
        return $this->db->select('expenses.*, expense_heads.head_title, payment_methods.title as method_name')
            ->join('expense_heads', 'expense_heads.id = expenses.expense_head_id', 'left')
            ->join('payment_methods', 'payment_methods.id = expenses.payment_method_id', 'left')
            ->order_by('expenses.transaction_date', 'DESC')
            ->order_by('expenses.id', 'DESC')
            ->get('expenses')->result_array();
    }

    public function getFilteredExpenses($from_date, $to_date, $created_by = null, $category_id = null, $paid_status = null, $payment_method_id = null, $search_value = null, $start = 0, $length = 10)
    {
        // Base query
        $this->db->select('expenses.*, expense_heads.head_title, payment_methods.title as method_name, users.full_name as created_by_name');
        $this->db->from('expenses');

        // Joins
        $this->db->join('expense_heads', 'expense_heads.id = expenses.expense_head_id', 'left');
        $this->db->join('payment_methods', 'payment_methods.id = expenses.payment_method_id', 'left');
        $this->db->join('users', 'users.id = expenses.created_by', 'left');

        // Filters
        $this->db->where('expenses.transaction_date >=', $from_date);
        $this->db->where('expenses.transaction_date <=', $to_date);

        if (!empty($created_by)) {
            $this->db->where('expenses.created_by', $created_by);
        }
        if (!empty($category_id)) {
            $this->db->where('expenses.expense_head_id', $category_id);
        }
        if ($paid_status !== null && $paid_status !== '') {
            $this->db->where('expenses.status', $paid_status);
        }
        if (!is_null($payment_method_id) && $payment_method_id !== '') {
            $this->db->where('expenses.payment_method_id', $payment_method_id);
        }

        // Search functionality
        if (!empty($search_value)) {
            $this->db->group_start(); // Start grouping conditions for search
            $this->db->like('expenses.expense_title', $search_value);
            $this->db->or_like('expense_heads.head_title', $search_value);
            $this->db->or_like('payment_methods.title', $search_value);
            $this->db->or_like('users.full_name', $search_value);
            $this->db->or_like('expenses.transaction_date', $search_value);
            $this->db->group_end(); // End grouping
        }

        // Order and Pagination
        $this->db->order_by('expenses.transaction_date', 'DESC');
        $this->db->order_by('expenses.id', 'DESC');
        if ($length != -1) { // -1 means no pagination (fetch all data)
            $this->db->limit($length, $start);
        }

        $query = $this->db->get();
        $result['data'] = $query->result_array();

        // Count total records (without filters)
        $this->db->reset_query();
        $this->db->from('expenses');
        $total_records = $this->db->count_all_results();

        // Count filtered records
        $this->db->reset_query();
        $this->db->select('expenses.id');
        $this->db->from('expenses');
        $this->db->join('expense_heads', 'expense_heads.id = expenses.expense_head_id', 'left');
        $this->db->join('payment_methods', 'payment_methods.id = expenses.payment_method_id', 'left');
        $this->db->join('users', 'users.id = expenses.created_by', 'left');

        // Reapply filters for filtered count
        $this->db->where('expenses.transaction_date >=', $from_date);
        $this->db->where('expenses.transaction_date <=', $to_date);
        if (!empty($created_by)) {
            $this->db->where('expenses.created_by', $created_by);
        }
        if (!empty($category_id)) {
            $this->db->where('expenses.expense_head_id', $category_id);
        }
        if ($paid_status !== null && $paid_status !== '') {
            $this->db->where('expenses.status', $paid_status);
        }
        if (!is_null($payment_method_id) && $payment_method_id !== '') {
            $this->db->where('expenses.payment_method_id', $payment_method_id);
        }
        if (!empty($search_value)) {
            $this->db->group_start();
            $this->db->like('expenses.expense_title', $search_value);
            $this->db->or_like('expense_heads.head_title', $search_value);
            $this->db->or_like('payment_methods.title', $search_value);
            $this->db->or_like('users.full_name', $search_value);
            $this->db->or_like('expenses.transaction_date', $search_value);
            $this->db->group_end();
        }
        $filtered_records = $this->db->count_all_results();

        // Combine results
        $result['recordsTotal'] = $total_records;
        $result['recordsFiltered'] = $filtered_records;

        return $result;
    }

    public function getExpenseHeads()
    {
        return $this->db->get('expense_heads')->result_array();
    }

    public function saveExpense($data)
    {
        $this->db->insert('expenses', $data);
        return $this->db->insert_id(); // Return the ID of the newly inserted record
    }

    public function getExpense($id)
    {
        return $this->db->get_where('expenses', ['id' => $id])->row_array();
    }

    public function updateExpense($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('expenses', $data);
    }

    public function deleteExpense($id)
    {
        $this->db->delete('expenses', ['id' => $id]);
    }

    public function getAllExpenseHeads()
    {
        return $this->db->get('expense_heads')->result_array();
    }

    public function saveExpenseHead($data)
    {
        $this->db->insert('expense_heads', $data);
    }

    public function getExpenseHead($id)
    {
        return $this->db->get_where('expense_heads', ['id' => $id])->row_array();
    }

    public function updateExpenseHead($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('expense_heads', $data);
    }

    public function deleteExpenseHead($id)
    {
        $this->db->delete('expense_heads', ['id' => $id]);
    }

    public function isExpenseHeadUsed($head_id)
    {
        $this->db->from('expenses');
        $this->db->where('expense_head_id', $head_id);
        $count = $this->db->count_all_results();
        return $count > 0; // Return true if the head is in use
    }
}
