<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PurchaseOrderModel extends CI_Model
{
    public function get_all_purchase_orders()
    {
        $this->db->select('po.*, s.supplier_name');
        $this->db->from('purchase_orders po');
        $this->db->join('suppliers s', 'po.supplier_id = s.id');
        $this->db->order_by('po.id', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function insert_purchase_order($data)
    {
        $this->db->insert('purchase_orders', $data);
        return $this->db->insert_id();
    }

    public function insert_purchase_order_product($data)
    {
        $this->db->insert('purchase_order_products', $data);
    }

    public function update_purchase_order($id, $purchaseOrderData)
    {
        $this->db->where('id', $id);
        $this->db->update('purchase_orders', $purchaseOrderData);

        return $this->db->affected_rows(); // Returns the number of rows affected by the update
    }

    public function delete_purchase_order($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('purchase_orders');
    }

    public function delete_purchase_order_products($purchase_order_id)
    {
        $this->db->where('purchase_order_id', $purchase_order_id);
        $this->db->delete('purchase_order_products');
    }

    public function delete_stocks($purchase_order_id)
    {
        $this->db->where('purchase_order_id', $purchase_order_id);
        $this->db->delete('stock_management');
    }

    public function get_purchase_order($id)
    {
        $this->db->select('*');
        $this->db->from('purchase_orders');
        $this->db->where('id', $id);
        $query = $this->db->get();

        return $query->row_array(); // Return a single row as an associative array
    }

    public function get_purchase_order_products($id)
    {
        $this->db->select('pop.*, p.name as product_name');
        $this->db->from('purchase_order_products pop');
        $this->db->join('products p', 'pop.product_id = p.id');
        $this->db->where('purchase_order_id', $id);
        return $this->db->get()->result_array();
    }
}
