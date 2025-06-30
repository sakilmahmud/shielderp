<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ReminderModel extends CI_Model
{
    public function get_active_reminders()
    {
        return $this->db->where('is_done', 0)
            ->order_by('created_at', 'DESC')
            ->get('reminders')
            ->result_array();
    }

    public function add_reminder($content)
    {
        $this->db->insert('reminders', [
            'content' => $content,
            'is_done' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function get_reminder($id)
    {
        return $this->db->where('id', $id)->get('reminders')->row_array();
    }

    public function mark_done($id)
    {
        return $this->db->where('id', $id)->update('reminders', ['is_done' => 1]);
    }
}
