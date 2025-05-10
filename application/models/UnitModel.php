<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UnitModel extends CI_Model
{
    public function get_all_units()
    {
        return $this->db->get('units')->result_array();
    }

    public function get_unit($id)
    {
        return $this->db->get_where('units', ['id' => $id])->row_array();
    }

    public function insert_unit($data)
    {
        $this->db->insert('units', $data);
        return $this->db->insert_id();
    }

    public function update_unit($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('units', $data);
    }

    public function delete_unit($id)
    {
        $this->db->delete('units', ['id' => $id]);
    }
}
