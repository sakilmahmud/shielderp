<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CustomerModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // Method to insert a new customer
    public function insert_customer($data)
    {
        $this->db->insert('customers', $data);
        return $this->db->insert_id(); // Return the ID of the newly created customer
    }

    // Method to update an existing customer
    public function update_customer($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('customers', $data);
    }

    // Method to delete a customer
    public function delete_customer($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('customers');
    }

    // Method to retrieve customer by phone number
    public function get_by_phone($phone)
    {
        $this->db->where('phone', $phone);
        $query = $this->db->get('customers');

        // Check if the customer exists
        if ($query->num_rows() > 0) {
            return $query->row_array(); // Return customer data if found
        } else {
            return false; // Return false if not found
        }
    }

    // Method to get all customers
    public function get_all_customers()
    {
        $query = $this->db->get('customers');
        return $query->result_array();
    }

    // Method to get a specific customer by ID
    public function get_customer_by_id($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('customers');
        return $query->row_array();
    }
}
