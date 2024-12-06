<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TaskModel extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_tasks()
    {
        // Perform SQL joins to fetch tasks along with client name, doer name, and category name
        $this->db->select('tasks.*, c.full_name AS client_name, d.full_name AS doer_name, tc.cat_name AS category_name');
        $this->db->from('tasks');
        $this->db->join('users c', 'tasks.client_id = c.id', 'left');
        $this->db->join('users d', 'tasks.doer_id = d.id', 'left');
        $this->db->join('task_categories tc', 'tasks.category_id = tc.id', 'left');
        $this->db->order_by('tasks.start_date', 'DESC');
        $query = $this->db->get();

        return $query->result_array();
    }

    public function get_all_tasks_doer($user_id)
    {
        // Perform SQL joins to fetch tasks along with client name, doer name, and category name
        $this->db->select('tasks.*, c.full_name AS client_name, tc.cat_name AS category_name');
        $this->db->from('tasks');
        $this->db->join('users c', 'tasks.client_id = c.id', 'left');
        $this->db->join('task_categories tc', 'tasks.category_id = tc.id', 'left');
        $this->db->where('tasks.doer_id', $user_id);
        $this->db->order_by('tasks.start_date', 'DESC');
        $query = $this->db->get();

        return $query->result_array();
    }


    public function get_task($id)
    {
        $query = $this->db->get_where('tasks', array('id' => $id));
        return $query->row_array();
    }

    public function insert_task($data)
    {

        /* echo "<pre>";
        print_r($data);
        die; */
        return $this->db->insert('tasks', $data);
    }

    public function update_task($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('tasks', $data);
    }

    public function delete_task($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('tasks');
    }

    public function get_all_categories()
    {
        $query = $this->db->get('task_categories');
        return $query->result_array();
    }

    public function getTodaysTasks($user_id, $role)
    {
        $this->db->select('tasks.*, 
                           client.full_name as client_name, 
                           doer.full_name as doer_name');
        $this->db->from('tasks');
        $this->db->join('users as client', 'tasks.client_id = client.id', 'left');
        $this->db->join('users as doer', 'tasks.doer_id = doer.id', 'left');
        $this->db->where('DATE(tasks.start_date)', date('Y-m-d'));

        if ($role != 1 && $role != 2) {
            $this->db->where('tasks.doer_id', $user_id);
        }

        $this->db->order_by('tasks.start_date', 'ASC');

        $query = $this->db->get();
        return $query->result();
    }
    public function getWeeksTasks($user_id, $role)
    {
        $this->db->select('tasks.*, 
                           client.full_name as client_name, 
                           doer.full_name as doer_name');
        $this->db->from('tasks');
        $this->db->join('users as client', 'tasks.client_id = client.id', 'left');
        $this->db->join('users as doer', 'tasks.doer_id = doer.id', 'left');
        $this->db->where('YEARWEEK(tasks.start_date, 1) = YEARWEEK(CURDATE(), 1)');

        if ($role != 1 && $role != 2) {
            $this->db->where('tasks.doer_id', $user_id);
        }

        $this->db->order_by('tasks.start_date', 'ASC');

        $query = $this->db->get();
        return $query->result();
    }

    public function getOverdueTasks($user_id, $role)
    {
        $this->db->select('tasks.*, 
                           client.full_name as client_name, 
                           doer.full_name as doer_name');
        $this->db->from('tasks');
        $this->db->join('users as client', 'tasks.client_id = client.id', 'left');
        $this->db->join('users as doer', 'tasks.doer_id = doer.id', 'left');
        $this->db->where('tasks.due_date <', date('Y-m-d'));

        if ($role != 1 && $role != 2) {
            $this->db->where('tasks.doer_id', $user_id);
        }

        $this->db->order_by('tasks.due_date', 'DSC');

        $query = $this->db->get();
        return $query->result();
    }

    public function updateTask($task_id, $updateData)
    {
        $this->db->where('id', $task_id);
        return $this->db->update('tasks', $updateData);
    }
}
