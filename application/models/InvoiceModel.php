<?php
defined('BASEPATH') or exit('No direct script access allowed');

class InvoiceModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // Method to insert a new invoice
    public function insert_invoice($data)
    {
        $this->db->insert('invoices', $data);
        return $this->db->insert_id(); // Return the ID of the newly created invoice
    }

    // Method to insert products associated with an invoice
    public function insert_invoice_products($invoice_id, $products)
    {
        foreach ($products as $product) {
            $product['invoice_id'] = $invoice_id;
            $this->db->insert('invoice_products', $product);
        }
    }

    public function get_all_invoices()
    {
        $this->db->select('invoices.*, users.full_name as created_by_name');
        $this->db->from('invoices');
        $this->db->join('users', 'invoices.created_by = users.id', 'left'); // Use 'left' join if you want to include invoices without a corresponding user
        $this->db->order_by('invoices.created_at', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getFilteredInvoices($from_date, $to_date, $payment_status, $created_by, $search_value, $start, $length)
    {
        // Select columns including calculated paid_amount and due_amount
        $this->db->select('
        invoices.*,
        users.full_name as created_by_name,
        invoices.total_amount,
        IFNULL(transactions_summary.paid_amount, 0) as paid_amount,
        (invoices.total_amount - IFNULL(transactions_summary.paid_amount, 0)) as due_amount,
        GROUP_CONCAT(products.name SEPARATOR ", ") as product_names
        ');
        $this->db->from('invoices');
        $this->db->join('users', 'invoices.created_by = users.id', 'left');

        // Subquery for transactions total amount
        $this->db->join(
            '(SELECT table_id, SUM(amount) as paid_amount FROM transactions 
                      WHERE transaction_for_table = "invoices" AND trans_type = 1 AND status = 1 
                      GROUP BY table_id) as transactions_summary',
            'transactions_summary.table_id = invoices.id',
            'left'
        );

        // Join invoice_details and products for product search
        $this->db->join('invoice_details', 'invoice_details.invoice_id = invoices.id', 'left');
        $this->db->join('products', 'products.id = invoice_details.product_id', 'left');

        $this->db->where('DATE(invoices.invoice_date) >=', $from_date);
        $this->db->where('DATE(invoices.invoice_date) <=', $to_date);

        // Apply payment status filter if provided
        if ($payment_status !== '' && $payment_status !== null) {
            $this->db->where('invoices.payment_status', $payment_status);
        }

        // Apply created_by filter if provided
        if (!empty($created_by)) {
            $this->db->where('invoices.created_by', $created_by);
        }

        // Apply search filter
        if (!empty($search_value)) {
            $this->db->group_start();
            $this->db->like('invoices.invoice_no', $search_value);
            $this->db->or_like('invoices.customer_name', $search_value);
            $this->db->or_like('users.full_name', $search_value);
            $this->db->or_like('products.name', $search_value); // Search by product name
            $this->db->group_end();
        }

        // Group by invoice ID
        $this->db->group_by('invoices.id');

        // Pagination
        if ($length != -1) {
            $this->db->limit($length, $start);
        }

        // Ordering
        $this->db->order_by('invoices.invoice_date', 'DESC');

        $query = $this->db->get();

        // Fetch filtered data count for DataTables
        $this->db->select('COUNT(DISTINCT invoices.id) as count');
        $this->db->from('invoices');
        $this->db->join('users', 'invoices.created_by = users.id', 'left');

        // Use the same transactions subquery
        $this->db->join(
            '(SELECT table_id, SUM(amount) as paid_amount FROM transactions 
                      WHERE transaction_for_table = "invoices" AND trans_type = 1 AND status = 1 
                      GROUP BY table_id) as transactions_summary',
            'transactions_summary.table_id = invoices.id',
            'left'
        );

        $this->db->join('invoice_details', 'invoice_details.invoice_id = invoices.id', 'left');
        $this->db->join('products', 'products.id = invoice_details.product_id', 'left');

        $this->db->where('DATE(invoices.invoice_date) >=', $from_date);
        $this->db->where('DATE(invoices.invoice_date) <=', $to_date);

        if ($payment_status !== '' && $payment_status !== null) {
            $this->db->where('invoices.payment_status', $payment_status);
        }

        if (!empty($created_by)) {
            $this->db->where('invoices.created_by', $created_by);
        }

        if (!empty($search_value)) {
            $this->db->group_start();
            $this->db->like('invoices.invoice_no', $search_value);
            $this->db->or_like('invoices.customer_name', $search_value);
            $this->db->or_like('users.full_name', $search_value);
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


    // Method to get a specific invoice by ID
    public function get_invoice_by_id($invoice_id)
    {
        $this->db->select('*');
        $this->db->from('invoices');
        $this->db->where('id', $invoice_id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function get_invoice_details($invoice_id)
    {
        $this->db->select('invoice_details.*, products.name as product_name');
        $this->db->from('invoice_details');
        $this->db->join('products', 'invoice_details.product_id = products.id');
        $this->db->where('invoice_details.invoice_id', $invoice_id);
        $query = $this->db->get();
        return $query->result_array();
    }


    // Method to get products associated with a specific invoice
    public function get_invoice_products($invoice_id)
    {
        $this->db->select('invoice_products.*, products.name as product_name');
        $this->db->from('invoice_products');
        $this->db->join('products', 'invoice_products.product_id = products.id');
        $this->db->where('invoice_products.invoice_id', $invoice_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    // Method to delete an invoice and its products
    public function delete_invoice($invoice_id)
    {
        // Delete products associated with the invoice
        $this->db->where('invoice_id', $invoice_id);
        $this->db->delete('invoice_products');

        // Delete the invoice itself
        $this->db->where('id', $invoice_id);
        $this->db->delete('invoices');
    }

    public function get_last_invoice_no($is_gst_bill)
    {
        if ($is_gst_bill) {
            // For GST invoices, check for the last invoice in the current financial year
            $this->db->like('invoice_no', '2024-25', 'both');
        } else {
            // For non-GST invoices, check for the last non-GST invoice
            $this->db->like('invoice_no', 'INV', 'both');
        }

        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('invoices', 1); // Assuming 'invoices' is your table

        if ($query->num_rows() > 0) {
            $result = $query->row();
            return $result->invoice_no;
        }

        return null;
    }

    // Get total amount of an invoice by its ID
    public function get_invoice_total($invoice_id)
    {
        $this->db->select('total_amount');
        $this->db->from('invoices');
        $this->db->where('id', $invoice_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row()->total_amount;
        } else {
            return 0; // Return 0 if invoice not found
        }
    }

    public function get_invoice_transactions($invoice_id)
    {
        $this->db->select('transactions.amount, transactions.trans_type, transactions.payment_method_id, transactions.descriptions, transactions.trans_date, payment_methods.title');
        $this->db->from('transactions');
        $this->db->join('payment_methods', 'transactions.payment_method_id = payment_methods.id', 'left');
        $this->db->where('transactions.transaction_for_table', 'invoices');
        $this->db->where('transactions.table_id', $invoice_id);
        $this->db->where('transactions.status', 1); // Assuming you only want active transactions
        $query = $this->db->get();

        return $query->result_array();
    }
}
