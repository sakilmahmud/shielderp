<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TaskCategoryModel extends CI_Model
{
    public function getAll()
    {
        return $this->db->where('status', 1)->order_by('cat_order', 'ASC')->get('task_categories')->result_array();
    }

    public function getAllExcept($id)
    {
        return $this->db->where('id !=', $id)->where('status', 1)->order_by('cat_order', 'ASC')->get('task_categories')->result_array();
    }

    public function getById($id)
    {
        return $this->db->where('id', $id)->get('task_categories')->row_array();
    }

    public function insert($data)
    {
        $this->db->insert('task_categories', $data);
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id)->update('task_categories', $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id)->delete('task_categories');
    }

    public function getDataTables()
    {
        $result = $this->db->get('task_categories')->result_array();
        $data = [];
        foreach ($result as $row) {
            $data[] = [
                $row['id'],
                $row['cat_name'],
                $row['cat_descriptions'],
                $row['parent_id'],
                $row['cat_order'],
                ($row['status']) ? 'Active' : 'Inactive',
                '<a href="' . base_url('admin/task-categories/edit/' . $row['id']) . '" class="btn btn-sm btn-warning">Edit</a>
                 <button class="btn btn-sm btn-danger delete-category" data-id="' . $row['id'] . '">Delete</button>'
            ];
        }
        return ['data' => $data];
    }
}
