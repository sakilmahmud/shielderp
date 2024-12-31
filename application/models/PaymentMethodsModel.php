<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PaymentMethodsModel extends CI_Model
{
    // Fetch all payment methods
    public function getAll()
    {
        $this->db->select('*');
        $this->db->from('payment_methods');
        //$this->db->order_by('created_at', 'DESC');
        return $this->db->get()->result_array();
    }

    // Fetch a single payment method by ID
    public function getById($id)
    {
        $this->db->select('*');
        $this->db->from('payment_methods');
        $this->db->where('id', $id);
        return $this->db->get()->row_array();
    }

    // Insert a new payment method
    public function insert($data)
    {
        $this->db->insert('payment_methods', $data);
        return $this->db->insert_id();
    }

    // Update an existing payment method
    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('payment_methods', $data);
    }

    // Delete a payment method by ID
    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('payment_methods');
    }
}
