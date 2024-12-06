<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BrandModel extends CI_Model
{
    public function get_all_brands()
    {
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('brands')->result_array();
    }

    public function insert_brand($data)
    {
        $this->db->insert('brands', $data);
        return $this->db->insert_id();
    }

    public function get_brand($id)
    {
        return $this->db->get_where('brands', array('id' => $id))->row_array();
    }

    public function update_brand($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('brands', $data);
    }

    public function delete_brand($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('brands');
    }
}
