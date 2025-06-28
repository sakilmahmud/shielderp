<?php
defined('BASEPATH') or exit('No direct script access allowed');

class HsnCodeModel extends CI_Model
{
    private $table = 'hsn_codes';
    private $column_order = ['id', 'hsn_code', 'description', 'gst_rate'];
    private $column_search = ['hsn_code', 'description'];
    private $order = ['id' => 'desc'];

    public function __construct()
    {
        parent::__construct();
    }

    /** Get HSN by ID */
    public function get($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row_array();
    }

    /** Insert new HSN code */
    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    /** Update existing HSN code */
    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    /** Delete HSN code */
    public function delete($id)
    {
        return $this->db->where('id', $id)->delete($this->table);
    }

    /** Datatables query builder */
    private function _get_datatables_query()
    {
        $this->db->from($this->table);

        $search_value = $_POST['search']['value'] ?? '';

        if (!empty($search_value)) {
            $this->db->group_start();
            foreach ($this->column_search as $item) {
                $this->db->or_like($item, $search_value);
            }
            $this->db->group_end();
        }

        if (isset($_POST['order'])) {
            $this->db->order_by(
                $this->column_order[$_POST['order']['0']['column']],
                $_POST['order']['0']['dir']
            );
        } else {
            $this->db->order_by(key($this->order), $this->order[key($this->order)]);
        }
    }

    /** Get filtered data for DataTables */
    public function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        return $this->db->get()->result();
    }

    /** Count filtered results for DataTables */
    public function count_filtered()
    {
        $this->_get_datatables_query();
        return $this->db->count_all_results();
    }

    /** Count all rows in table */
    public function count_all()
    {
        return $this->db->count_all($this->table);
    }

    /** Get all HSN codes */
    public function get_all()
    {
        return $this->db->order_by('hsn_code')->get($this->table)->result_array();
    }

    public function get_or_create($value)
    {
        $this->db->where('hsn_code', $value);
        $record = $this->db->get('hsn_codes')->row();
        if ($record) return $record->id;

        $insert = ['hsn_code' => $value, 'created_at' => date('Y-m-d H:i:s')]; // extend if needed
        $this->db->insert('hsn_codes', $insert);
        return $this->db->insert_id();
    }
}
