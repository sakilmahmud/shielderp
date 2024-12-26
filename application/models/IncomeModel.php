<?php
defined('BASEPATH') or exit('No direct script access allowed');

class IncomeModel extends CI_Model
{

    // Get all income records
    public function getAllIncomes()
    {
        $this->db->select('incomes.*, income_heads.head_title as income_head');
        $this->db->from('incomes');
        $this->db->join('income_heads', 'income_heads.id = incomes.income_head_id', 'left');
        $this->db->where('incomes.status', 1); // Only active incomes
        $this->db->order_by('incomes.created_at', 'DESC');
        return $this->db->get()->result_array();
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
}
