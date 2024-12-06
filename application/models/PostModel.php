<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PostModel extends CI_Model
{
    public function getAllPosts()
    {
        $this->db->select('*');
        $this->db->from('posts');
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get()->result_array();
    }

    public function getPostById($id)
    {
        return $this->db->get_where('posts', ['id' => $id])->row_array();
    }

    public function insertPost($data)
    {
        return $this->db->insert('posts', $data);
    }

    public function updatePost($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('posts', $data);
    }

    public function deletePost($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('posts');
    }
}
