<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DashboardModel extends CI_Model
{
    public function get_total_payin($from, $to)
    {
        return $this->db->select_sum('amount')
            ->from('transactions')
            ->where('trans_type', 1)
            ->where('status', 1)
            ->where('trans_date >=', $from)
            ->where('trans_date <=', $to)
            ->get()
            ->row()
            ->amount ?? 0;
    }

    public function get_total_payout($from, $to)
    {
        return $this->db->select_sum('amount')
            ->from('transactions')
            ->where('trans_type', 2)
            ->where('status', 1)
            ->where('trans_date >=', $from)
            ->where('trans_date <=', $to)
            ->get()
            ->row()
            ->amount ?? 0;
    }

    public function get_total_sales($from, $to)
    {
        return $this->db
            ->select('SUM(total_amount) as total_amount, COUNT(*) as invoice_count')
            ->from('invoices')
            ->where('status', 1)
            ->where('invoice_date >=', $from)
            ->where('invoice_date <=', $to)
            ->get()
            ->row_array();
    }

    public function get_total_purchase($from, $to)
    {
        return $this->db
            ->select('SUM(total_amount) as total_amount, COUNT(*) as purchase_count')
            ->from('purchase_orders')
            ->where('status', 1)
            ->where('purchase_date >=', $from)
            ->where('purchase_date <=', $to)
            ->get()
            ->row_array();
    }
}
