<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProductTypeModel extends CI_Model
{
    public function get_all_product_types()
    {
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('product_types')->result_array();
    }

    public function insert_product_type($data)
    {
        $this->db->insert('product_types', $data);
        return $this->db->insert_id();
    }

    public function get_product_type($id)
    {
        return $this->db->get_where('product_types', array('id' => $id))->row_array();
    }

    public function update_product_type($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('product_types', $data);
    }

    public function delete_product_type($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('product_types');
    }
}
