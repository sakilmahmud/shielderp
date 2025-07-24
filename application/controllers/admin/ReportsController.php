<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ReportsController extends MY_Controller
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

        $this->render_admin('admin/reports/sales_report', $data);
    }

    public function purchaseReport()
    {
        $data['activePage'] = 'purchase_report';
        // Fetch the purchase data from the model
        $data['purchase_data'] = $this->ReportModel->getPurchaseData();

        // Load the views

        $this->render_admin('admin/reports/purchase_report', $data);
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
            $data['from_date'] = $fromDate;
            $data['to_date'] = $toDate;
            $data['gst_type'] = $gstType;
        }

        // Load the views

        $this->render_admin('admin/reports/gst_report', $data);
    }

    public function exportGstJson()
    {
        $this->load->model('GstrModel');
        $fromDate = $this->input->get('from_date');
        $toDate = $this->input->get('to_date');
        $gstType = $this->input->get('gst_type');

        $data = [];
        $filename = 'gst_report.json';

        switch ($gstType) {
            case 'gstr1_b2b':
                $data = $this->GstrModel->get_gstr1_b2b_data($fromDate, $toDate);
                $filename = 'gstr1_b2b_' . $fromDate . '_to_' . $toDate . '.json';
                break;
            case 'gstr1_b2cl':
                $data = $this->GstrModel->get_gstr1_b2cl_data($fromDate, $toDate);
                $filename = 'gstr1_b2cl_' . $fromDate . '_to_' . $toDate . '.json';
                break;
            case 'gstr1_b2cs':
                $data = $this->GstrModel->get_gstr1_b2cs_data($fromDate, $toDate);
                $filename = 'gstr1_b2cs_' . $fromDate . '_to_' . $toDate . '.json';
                break;
            case 'gstr1_hsn':
                $data = $this->GstrModel->get_gstr1_hsn_data($fromDate, $toDate);
                $filename = 'gstr1_hsn_' . $fromDate . '_to_' . $toDate . '.json';
                break;
            case 'gstr3b':
                $data = $this->GstrModel->get_gstr3b_data($fromDate, $toDate);
                $filename = 'gstr3b_' . $fromDate . '_to_' . $toDate . '.json';
                break;
        }

        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
}
