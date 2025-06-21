<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CustomerModel extends CI_Model
{
    private $table = 'customers';
    private $column_order = ['id', 'customer_name', 'phone', 'email', 'address'];
    private $column_search = ['customer_name', 'phone', 'email', 'address'];
    private $order = ['id' => 'desc'];

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

    private function _get_datatables_query()
    {
        $this->db->from($this->table);

        $i = 0;
        foreach ($this->column_search as $item) {
            if ($_POST['search']['value']) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->db->order_by(
                $this->column_order[$_POST['order']['0']['column']],
                $_POST['order']['0']['dir']
            );
        } else {
            $this->db->order_by(key($this->order), $this->order[key($this->order)]);
        }
    }

    public function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);

        return $this->db->get()->result();
    }

    public function count_filtered()
    {
        $this->_get_datatables_query();
        return $this->db->count_all_results();
    }

    public function count_all()
    {
        return $this->db->count_all($this->table);
    }

    public function delete_customer($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }
}
