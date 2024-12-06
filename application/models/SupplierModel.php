<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SupplierModel extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function get_all_suppliers()
    {
        $query = $this->db->get('suppliers');
        return $query->result_array();
    }

    public function get_supplier($id)
    {
        $query = $this->db->get_where('suppliers', array('id' => $id));
        return $query->row_array();
    }

    public function insert_supplier($data)
    {
        return $this->db->insert('suppliers', $data);
    }

    public function update_supplier($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('suppliers', $data);
    }

    public function delete_supplier($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('suppliers');
    }
}
