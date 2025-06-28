<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CategoryModel extends CI_Model
{

    public function get_all_categories()
    {
        $query = $this->db->get('categories');
        return $query->result_array();
    }

    public function get_category($id)
    {
        $query = $this->db->get_where('categories', array('id' => $id));
        return $query->row_array();
    }

    public function insert_category($data)
    {

        $this->db->insert('categories', $data);
        return $this->db->insert_id();
    }
    public function update_category($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('categories', $data);
    }

    public function delete_category($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('categories');
    }
    public function get_category_by_slug($slug)
    {
        $this->db->where('slug', $slug);
        $query = $this->db->get('categories');
        return $query->row_array();
    }

    public function bulk_insert($data)
    {
        return $this->db->insert_batch('categories', $data);
    }

    public function get_or_create($value)
    {
        $this->db->where('name', $value);
        $record = $this->db->get('categories')->row();
        if ($record) return $record->id;

        $insert = ['name' => $value, 'created_at' => date('Y-m-d H:i:s')]; // extend if needed
        $this->db->insert('categories', $insert);
        return $this->db->insert_id();
    }
}
