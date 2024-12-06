<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ReportsController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Check if the user is logged in
        if (!$this->session->userdata('user_id')) {
            redirect('login'); // Redirect to login if not logged in
        } else if ($this->session->userdata('role') != 1) { // Only allow admin access
            $this->session->set_flashdata('error', 'You are not allowed for admin access!');
            $this->session->unset_userdata('username');
            $this->session->unset_userdata('role');
            $this->session->unset_userdata('user_id');
            redirect('login');
        }

        $this->load->model('ReportModel'); // Load the ReportModel for all report types

    }

    public function salesReport()
    {
        $data['activePage'] = 'sales_report';
        $data['sales_data'] = $this->ReportModel->getSalesData(); // Fetch sales data

        // Load header, content, and footer views
        $this->load->view('admin/header', $data);
        $this->load->view('admin/reports/sales_report', $data);
        $this->load->view('admin/footer');
    }

    public function purchaseReport()
    {
        $data['activePage'] = 'purchase_report';
        // Fetch the purchase data from the model
        $data['purchase_data'] = $this->ReportModel->getPurchaseData();

        // Load the views
        $this->load->view('admin/header', $data);
        $this->load->view('admin/reports/purchase_report', $data);
        $this->load->view('admin/footer');
    }

    public function gstReport()
    {
        $data['activePage'] = 'gst_report';

        if ($this->input->post()) {
            $fromDate = $this->input->post('from_date');
            $toDate = $this->input->post('to_date');
            $gstType = $this->input->post('gst_type');

            // Fetch GST report data from model
            $data['gst_report_data'] = $this->ReportModel->getGSTReportData($fromDate, $toDate, $gstType);
        }

        // Load the views
        $this->load->view('admin/header', $data);
        $this->load->view('admin/reports/gst_report', $data);
        $this->load->view('admin/footer');
    }
}
