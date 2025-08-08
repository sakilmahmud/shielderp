<?php
defined('BASEPATH') or exit('No direct script access allowed');

class InventoryModel extends CI_Model
{
    public function get_stock_availability()
    {
        $this->db->select('p.name as product_name, sm.available_stock as quantity, p.sale_price as price, c.name as category_name, u.name as unit_name');
        $this->db->from('products p');
        $this->db->join('stock_management sm', 'p.id = sm.product_id');
        $this->db->join('categories c', 'c.id = p.category_id', 'left');
        $this->db->join('units u', 'u.id = p.unit_id', 'left');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_fast_moving_items()
    {
        $this->db->select('p.name as product_name, SUM(id.quantity) as total_sold');
        $this->db->from('products p');
        $this->db->join('invoice_details id', 'p.id = id.product_id');
        $this->db->group_by('p.id');
        $this->db->order_by('total_sold', 'DESC');
        $this->db->limit(25);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_items_not_moving()
    {
        $this->db->select('p.name as product_name, sm.available_stock as quantity');
        $this->db->from('products p');
        $this->db->join('stock_management sm', 'p.id = sm.product_id');
        $this->db->join('invoice_details id', 'p.id = id.product_id', 'left');
        $this->db->where('id.product_id IS NULL');
        $query = $this->db->get();
        return $query->result_array();
    }

    private function _get_stock_query($searchValue, $category_id, $brand_id)
    {
        $this->db->select('p.name as product_name, SUM(sm.available_stock) as quantity, p.sale_price as price, (SUM(sm.available_stock) * p.sale_price) as valuation, c.name as category_name, b.brand_name, u.name as unit_name');
        $this->db->from('products p');
        $this->db->join('stock_management sm', 'p.id = sm.product_id');
        $this->db->join('categories c', 'c.id = p.category_id', 'left');
        $this->db->join('brands b', 'b.id = p.brand_id', 'left');
        $this->db->join('units u', 'u.id = p.unit_id', 'left');

        if ($searchValue) {
            $this->db->like('p.name', $searchValue);
        }

        if ($category_id) {
            $this->db->where('p.category_id', $category_id);
        }

        if ($brand_id) {
            $this->db->where('p.brand_id', $brand_id);
        }

        $this->db->group_by('p.id');
        $this->db->having('quantity >', 0);
    }

    public function get_stock_availability_ajax($start, $length, $searchValue, $category_id, $brand_id)
    {
        $this->_get_stock_query($searchValue, $category_id, $brand_id);
        if ($length != -1) {
            $this->db->limit($length, $start);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function count_all_stock()
    {
        $this->db->select('p.id');
        $this->db->from('products p');
        $this->db->join('stock_management sm', 'p.id = sm.product_id');
        $this->db->where('sm.available_stock >', 0);
        $this->db->group_by('p.id');
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_filtered_stock($searchValue, $category_id, $brand_id)
    {
        $this->_get_stock_query($searchValue, $category_id, $brand_id);
        $query = $this->db->get();
        return $query->num_rows();
    }
}
