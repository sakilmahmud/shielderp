<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ContactsGroupModel extends CI_Model
{
    // Get all contact groups
    public function getAll()
    {
        $this->db->order_by('id', 'DESC');
        return $this->db->get('contacts_group')->result_array();
    }

    // Save a new contact group
    public function save($data)
    {
        $this->db->insert('contacts_group', $data);
    }
}
