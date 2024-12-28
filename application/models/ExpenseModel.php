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

    public function saveExpense($data)
    {
        $this->db->insert('expenses', $data);
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
}
