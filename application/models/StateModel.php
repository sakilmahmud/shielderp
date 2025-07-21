<?php
defined('BASEPATH') or exit('No direct script access allowed');

class StateModel extends CI_Model
{

    public function get_all_states()
    {
        return $this->db->get('states')->result_array();
    }

    public function get_state($id)
    {
        return $this->db->get_where('states', ['id' => $id])->row_array();
    }

    public function insert_state($data)
    {
        return $this->db->insert('states', $data);
    }

    public function update_state($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('states', $data);
    }

    public function delete_state($id)
    {
        return $this->db->delete('states', ['id' => $id]);
    }
}