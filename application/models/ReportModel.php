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
}
