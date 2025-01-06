<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ContactsModel extends CI_Model
{
    public function getAll()
    {
        $this->db->select('c.*, cg.title AS group_title');
        $this->db->from('contacts c');
        $this->db->join('contacts_group cg', 'cg.id = c.contacts_group_id', 'left');
        $this->db->order_by('c.id', 'DESC');
        return $this->db->get()->result_array();
    }

    public function getById($id)
    {
        $this->db->select('*');
        $this->db->from('contacts');
        $this->db->where('id', $id);
        return $this->db->get()->row_array();
    }

    public function getContactsByGroupId($id)
    {
        $this->db->select('*');
        $this->db->from('contacts');
        $this->db->where('contacts_group_id', $id);
        return $this->db->get()->result_array();
    }

    public function insert($data)
    {
        $this->db->insert('contacts', $data);
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('contacts', $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('contacts');
    }

    public function getAllWithGroups()
    {
        $this->db->select('contacts.*, contacts_group.title as group_title');
        $this->db->from('contacts');
        $this->db->join('contacts_group', 'contacts.contacts_group_id = contacts_group.id', 'left');
        $query = $this->db->get();

        return $query->result_array();
    }

    public function getFilteredContacts($start, $length, $searchValue)
    {
        $this->db->select('contacts.*, contacts_group.title as group_title');
        $this->db->from('contacts');
        $this->db->join('contacts_group', 'contacts.contacts_group_id = contacts_group.id', 'left');

        // Apply search filter
        if (!empty($searchValue)) {
            $this->db->group_start();
            $this->db->like('contacts.full_name', $searchValue);
            $this->db->or_like('contacts.contact', $searchValue);
            $this->db->or_like('contacts_group.title', $searchValue);
            $this->db->group_end();
        }

        $this->db->limit($length, $start);
        $query = $this->db->get();

        return $query->result_array();
    }

    public function getTotalContactsCount()
    {
        return $this->db->count_all('contacts');
    }

    public function getFilteredContactsCount($searchValue)
    {
        $this->db->select('contacts.id');
        $this->db->from('contacts');
        $this->db->join('contacts_group', 'contacts.contacts_group_id = contacts_group.id', 'left');

        // Apply search filter
        if (!empty($searchValue)) {
            $this->db->group_start();
            $this->db->like('contacts.full_name', $searchValue);
            $this->db->or_like('contacts.contact', $searchValue);
            $this->db->or_like('contacts_group.title', $searchValue);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }
}
