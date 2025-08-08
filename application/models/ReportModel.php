<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ReportModel extends CI_Model
{
    public function getSalesData()
    {
        // Select the relevant columns from the invoices table
        $this->db->select('
            id,
            invoice_no,
            invoice_date,
            customer_name,
            mobile,
            total_amount,
            payment_status
        ');
        $this->db->from('invoices'); // Specify the invoices table
        $this->db->where('status', 1); // Only get active invoices
        $query = $this->db->get();

        return $query->result_array(); // Return the result as an array
    }

    public function getPurchaseData()
    {
        // Select the relevant columns from the purchase_orders and suppliers tables
        $this->db->select('
            purchase_orders.id,
            purchase_orders.purchase_date,
            purchase_orders.invoice_no,
            suppliers.supplier_name,
            purchase_orders.total_amount,
            purchase_orders.is_gst
        ');
        $this->db->from('purchase_orders');
        $this->db->join('suppliers', 'suppliers.id = purchase_orders.supplier_id', 'left');
        $this->db->where('purchase_orders.status', 1); // Only get active purchase orders
        $query = $this->db->get();

        return $query->result_array(); // Return the result as an array
    }

    public function getGSTReportData($fromDate, $toDate, $gstType)
    {
        // Selecting necessary fields from the invoices table
        $this->db->select('invoice_no, invoice_date, customer_name, total_amount, total_gst');
        $this->db->from('invoices');
        $this->db->where('invoice_date >=', $fromDate);
        $this->db->where('invoice_date <=', $toDate);

        if ($gstType == 'GSTR-1') {
            // Fetch GSTR-1 specific data (Sales data)
            $this->db->where('is_gst', 1); // Assuming this condition for GSTR-1
        } elseif ($gstType == 'GSTR3B') {
            // Fetch GSTR3B specific data (Input tax & Output tax)
            // Example condition: Fetch invoices with GST
        }

        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_sales_ajax($start, $length, $searchValue, $from_date, $to_date)
    {
        $this->db->select('invoices.id as invoice_id, invoices.invoice_date, invoices.total_amount, customers.customer_name as customer_name');
        $this->db->from('invoices');
        $this->db->join('customers', 'customers.id = invoices.customer_id');

        if (!empty($searchValue)) {
            $this->db->like('customers.customer_name', $searchValue);
            $this->db->or_like('invoices.invoice_no', $searchValue);
        }

        if (!empty($from_date)) {
            $this->db->where('invoices.invoice_date >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('invoices.invoice_date <=', $to_date);
        }

        $this->db->limit($length, $start);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function count_all_sales($from_date, $to_date)
    {
        $this->db->from('invoices');
        if (!empty($from_date)) {
            $this->db->where('invoices.invoice_date >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('invoices.invoice_date <=', $to_date);
        }
        return $this->db->count_all_results();
    }

    public function count_filtered_sales($searchValue, $from_date, $to_date)
    {
        $this->db->from('invoices');
        $this->db->join('customers', 'customers.id = invoices.customer_id');

        if (!empty($searchValue)) {
            $this->db->like('customers.customer_name', $searchValue);
            $this->db->or_like('invoices.invoice_no', $searchValue);
        }

        if (!empty($from_date)) {
            $this->db->where('invoices.invoice_date >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('invoices.invoice_date <=', $to_date);
        }

        return $this->db->count_all_results();
    }

    public function get_customers_ajax($start, $length, $searchValue)
    {
        $this->db->select('c.customer_name as name, c.email, c.phone, COUNT(i.id) as total_orders, SUM(i.total_amount) as total_spent');
        $this->db->from('customers c');
        $this->db->join('invoices i', 'c.id = i.customer_id', 'left');
        $this->db->group_by('c.id');

        if (!empty($searchValue)) {
            $this->db->like('c.customer_name', $searchValue);
            $this->db->or_like('c.email', $searchValue);
            $this->db->or_like('c.phone', $searchValue);
        }

        $this->db->limit($length, $start);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function count_all_customers()
    {
        return $this->db->count_all('customers');
    }

    public function count_filtered_customers($searchValue)
    {
        $this->db->from('customers');
        if (!empty($searchValue)) {
            $this->db->like('name', $searchValue);
            $this->db->or_like('email', $searchValue);
            $this->db->or_like('phone', $searchValue);
        }
        return $this->db->count_all_results();
    }

    public function get_purchases_ajax($start, $length, $searchValue, $from_date, $to_date)
    {
        $this->db->select('po.id as purchase_id, po.purchase_date, po.total_amount, s.supplier_name');
        $this->db->from('purchase_orders po');
        $this->db->join('suppliers s', 's.id = po.supplier_id');

        if (!empty($searchValue)) {
            $this->db->like('s.supplier_name', $searchValue);
            $this->db->or_like('po.invoice_no', $searchValue);
        }

        if (!empty($from_date)) {
            $this->db->where('po.purchase_date >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('po.purchase_date <=', $to_date);
        }

        $this->db->limit($length, $start);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function count_all_purchases($from_date, $to_date)
    {
        $this->db->from('purchase_orders');
        if (!empty($from_date)) {
            $this->db->where('purchase_date >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('purchase_date <=', $to_date);
        }
        return $this->db->count_all_results();
    }

    public function count_filtered_purchases($searchValue, $from_date, $to_date)
    {
        $this->db->from('purchase_orders po');
        $this->db->join('suppliers s', 's.id = po.supplier_id');

        if (!empty($searchValue)) {
            $this->db->like('s.supplier_name', $searchValue);
            $this->db->or_like('po.invoice_no', $searchValue);
        }

        if (!empty($from_date)) {
            $this->db->where('po.purchase_date >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('po.purchase_date <=', $to_date);
        }

        return $this->db->count_all_results();
    }

    public function get_suppliers_ajax($start, $length, $searchValue)
    {
        $this->db->select('s.supplier_name, s.email, s.phone, COUNT(po.id) as total_orders, SUM(po.total_amount) as total_spent');
        $this->db->from('suppliers s');
        $this->db->join('purchase_orders po', 's.id = po.supplier_id', 'left');
        $this->db->group_by('s.id');

        if (!empty($searchValue)) {
            $this->db->like('s.supplier_name', $searchValue);
            $this->db->or_like('s.email', $searchValue);
            $this->db->or_like('s.phone', $searchValue);
        }

        $this->db->limit($length, $start);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function count_all_suppliers()
    {
        return $this->db->count_all('suppliers');
    }

    public function count_filtered_suppliers($searchValue)
    {
        $this->db->from('suppliers');
        if (!empty($searchValue)) {
            $this->db->like('supplier_name', $searchValue);
            $this->db->or_like('email', $searchValue);
            $this->db->or_like('phone', $searchValue);
        }
        return $this->db->count_all_results();
    }

    public function get_expenses_ajax($start, $length, $searchValue, $from_date, $to_date)
    {
        $this->db->select('e.expense_title, eh.head_title as expense_head, e.transaction_date as expense_date, e.transaction_amount as amount');
        $this->db->from('expenses e');
        $this->db->join('expense_heads eh', 'eh.id = e.expense_head_id');

        if (!empty($searchValue)) {
            $this->db->like('eh.head_title', $searchValue);
            $this->db->or_like('e.expense_title', $searchValue);
        }

        if (!empty($from_date)) {
            $this->db->where('e.transaction_date >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('e.transaction_date <=', $to_date);
        }

        $this->db->limit($length, $start);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function count_all_expenses($from_date, $to_date)
    {
        $this->db->from('expenses');
        if (!empty($from_date)) {
            $this->db->where('transaction_date >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('transaction_date <=', $to_date);
        }
        return $this->db->count_all_results();
    }

    public function count_filtered_expenses($searchValue, $from_date, $to_date)
    {
        $this->db->from('expenses e');
        $this->db->join('expense_heads eh', 'eh.id = e.expense_head_id');

        if (!empty($searchValue)) {
            $this->db->like('eh.head_title', $searchValue);
        }

        if (!empty($from_date)) {
            $this->db->where('e.transaction_date >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('e.transaction_date <=', $to_date);
        }

        return $this->db->count_all_results();
    }

    public function get_staff_ajax($start, $length, $searchValue)
    {
        $this->db->select('username, email, user_role as role');
        $this->db->from('users');

        if (!empty($searchValue)) {
            $this->db->like('username', $searchValue);
            $this->db->or_like('email', $searchValue);
        }

        $this->db->limit($length, $start);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function count_all_staff()
    {
        return $this->db->count_all('users');
    }

    public function count_filtered_staff($searchValue)
    {
        $this->db->from('users');
        if (!empty($searchValue)) {
            $this->db->like('username', $searchValue);
            $this->db->or_like('email', $searchValue);
        }
        return $this->db->count_all_results();
    }
}
