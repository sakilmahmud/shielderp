<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PurchaseOrderModel extends CI_Model
{
    public function get_all_purchase_orders()
    {
        $this->db->select('po.*, s.supplier_name');
        $this->db->from('purchase_orders po');
        $this->db->join('suppliers s', 'po.supplier_id = s.id');
        $this->db->order_by('po.purchase_date', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getFilteredPurchases($from_date, $to_date, $payment_status, $type, $supplier_id, $search_value, $start, $length)
    {
        // Select columns including calculated paid_amount and due_amount
        $this->db->select('purchase_orders.*,suppliers.supplier_name,
        purchase_orders.total_amount,
        IFNULL(transactions_summary.paid_amount, 0) as paid_amount,
        (purchase_orders.total_amount - IFNULL(transactions_summary.paid_amount, 0)) as due_amount');
        $this->db->from('purchase_orders');
        $this->db->join('suppliers', 'purchase_orders.supplier_id = suppliers.id', 'left');

        // Subquery for transactions total amount
        $this->db->join(
            '(SELECT table_id, SUM(amount) as paid_amount FROM transactions 
                      WHERE transaction_for_table = "purchase_orders" AND trans_type = 2 AND status = 1 
                      GROUP BY table_id) as transactions_summary',
            'transactions_summary.table_id = purchase_orders.id',
            'left'
        );

        // Join purchase_order_products and products for product search
        $this->db->join('purchase_order_products', 'purchase_order_products.purchase_order_id = purchase_orders.id', 'left');
        $this->db->join('products', 'products.id = purchase_order_products.product_id', 'left');

        $this->db->where('DATE(purchase_orders.purchase_date) >=', $from_date);
        $this->db->where('DATE(purchase_orders.purchase_date) <=', $to_date);

        // Apply payment status filter if provided
        if ($type !== '' && $type !== null) {
            $this->db->where('purchase_orders.is_gst', $type);
        }
        // Apply payment status filter if provided
        if ($payment_status !== '' && $payment_status !== null) {
            //$this->db->where('purchase_orders.payment_status', $payment_status);
        }

        // Apply supplier_id filter if provided
        if (!empty($supplier_id)) {
            $this->db->where('purchase_orders.supplier_id', $supplier_id);
        }

        // Apply search filter
        if (!empty($search_value)) {
            $this->db->group_start();
            $this->db->like('purchase_orders.invoice_no', $search_value);
            $this->db->or_like('products.name', $search_value); // Search by product name
            $this->db->group_end();
        }

        // Group by invoice ID
        $this->db->group_by('purchase_orders.id');

        // Pagination
        if ($length != -1) {
            $this->db->limit($length, $start);
        }

        // Ordering
        $this->db->order_by('purchase_orders.purchase_date', 'DESC');

        $query = $this->db->get();

        // Fetch filtered data count for DataTables
        $this->db->select('COUNT(DISTINCT purchase_orders.id) as count');
        $this->db->from('purchase_orders');
        // Subquery for transactions total amount
        $this->db->join(
            '(SELECT table_id, SUM(amount) as paid_amount FROM transactions 
                      WHERE transaction_for_table = "purchase_orders" AND trans_type = 2 AND status = 1 
                      GROUP BY table_id) as transactions_summary',
            'transactions_summary.table_id = purchase_orders.id',
            'left'
        );

        $this->db->join('purchase_order_products', 'purchase_order_products.purchase_order_id = purchase_orders.id', 'left');
        $this->db->join('products', 'products.id = purchase_order_products.product_id', 'left');

        $this->db->where('DATE(purchase_orders.purchase_date) >=', $from_date);
        $this->db->where('DATE(purchase_orders.purchase_date) <=', $to_date);

        if ($payment_status !== '' && $payment_status !== null) {
            //$this->db->where('purchase_orders.payment_status', $payment_status);
        }

        if ($type !== '' && $type !== null) {
            $this->db->where('purchase_orders.is_gst', $type);
        }

        if (!empty($supplier_id)) {
            $this->db->where('purchase_orders.supplier_id', $supplier_id);
        }

        if (!empty($search_value)) {
            $this->db->group_start();
            $this->db->like('purchase_orders.invoice_no', $search_value);
            $this->db->or_like('products.name', $search_value); // Search by product name
            $this->db->group_end();
        }

        $count_query = $this->db->get();
        $count_result = $count_query->row_array();

        return [
            'data' => $query->result_array(),
            'recordsTotal' => $count_result['count'],
            'recordsFiltered' => $count_result['count'],
        ];
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

    // Get total amount of an purchase_order_id by its ID
    public function get_purchase_total($purchase_order_id)
    {
        $this->db->select('total_amount');
        $this->db->from('purchase_orders');
        $this->db->where('id', $purchase_order_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row()->total_amount;
        } else {
            return 0; // Return 0 if invoice not found
        }
    }
}
