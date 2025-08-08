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

    public function index()
    {
        $data['activePage'] = 'reports';
        $this->render_admin('admin/reports/index', $data);
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
        $data['activePage'] = 'gstr-reports';

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

    public function sales()
    {
        $data['activePage'] = 'sales';
        $this->render_admin('admin/reports/sales', $data);
    }

    public function customers()
    {
        $data['activePage'] = 'customers';
        $this->render_admin('admin/reports/customers', $data);
    }

    public function purchases()
    {
        $data['activePage'] = 'purchases';
        $this->render_admin('admin/reports/purchases', $data);
    }

    public function suppliers()
    {
        $data['activePage'] = 'suppliers';
        $this->render_admin('admin/reports/suppliers', $data);
    }

    public function expenses()
    {
        $data['activePage'] = 'expenses';
        $this->render_admin('admin/reports/expenses', $data);
    }

    public function staff()
    {
        $data['activePage'] = 'staff';
        $this->render_admin('admin/reports/staff', $data);
    }

    public function fetch_sales()
    {
        $draw = $this->input->post('draw');
        $start = $this->input->post('start');
        $length = $this->input->post('length');
        $searchValue = $this->input->post('search')['value'];
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');

        $data = $this->ReportModel->get_sales_ajax($start, $length, $searchValue, $from_date, $to_date);
        $totalRecords = $this->ReportModel->count_all_sales($from_date, $to_date);
        $filteredRecords = $this->ReportModel->count_filtered_sales($searchValue, $from_date, $to_date);

        $response = [
            "draw" => intval($draw),
            "recordsTotal" => intval($totalRecords),
            "recordsFiltered" => intval($filteredRecords),
            "data" => $data
        ];

        echo json_encode($response);
    }

    public function fetch_customers()
    {
        $draw = $this->input->post('draw');
        $start = $this->input->post('start');
        $length = $this->input->post('length');
        $searchValue = $this->input->post('search')['value'];

        $data = $this->ReportModel->get_customers_ajax($start, $length, $searchValue);
        $totalRecords = $this->ReportModel->count_all_customers();
        $filteredRecords = $this->ReportModel->count_filtered_customers($searchValue);

        $response = [
            "draw" => intval($draw),
            "recordsTotal" => intval($totalRecords),
            "recordsFiltered" => intval($filteredRecords),
            "data" => $data
        ];

        echo json_encode($response);
    }

    public function fetch_purchases()
    {
        $draw = $this->input->post('draw');
        $start = $this->input->post('start');
        $length = $this->input->post('length');
        $searchValue = $this->input->post('search')['value'];
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');

        $data = $this->ReportModel->get_purchases_ajax($start, $length, $searchValue, $from_date, $to_date);
        $totalRecords = $this->ReportModel->count_all_purchases($from_date, $to_date);
        $filteredRecords = $this->ReportModel->count_filtered_purchases($searchValue, $from_date, $to_date);

        $response = [
            "draw" => intval($draw),
            "recordsTotal" => intval($totalRecords),
            "recordsFiltered" => intval($filteredRecords),
            "data" => $data
        ];

        echo json_encode($response);
    }

    public function fetch_suppliers()
    {
        $draw = $this->input->post('draw');
        $start = $this->input->post('start');
        $length = $this->input->post('length');
        $searchValue = $this->input->post('search')['value'];

        $data = $this->ReportModel->get_suppliers_ajax($start, $length, $searchValue);
        $totalRecords = $this->ReportModel->count_all_suppliers();
        $filteredRecords = $this->ReportModel->count_filtered_suppliers($searchValue);

        $response = [
            "draw" => intval($draw),
            "recordsTotal" => intval($totalRecords),
            "recordsFiltered" => intval($filteredRecords),
            "data" => $data
        ];

        echo json_encode($response);
    }

    public function fetch_expenses()
    {
        $draw = $this->input->post('draw');
        $start = $this->input->post('start');
        $length = $this->input->post('length');
        $searchValue = $this->input->post('search')['value'];
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');

        $data = $this->ReportModel->get_expenses_ajax($start, $length, $searchValue, $from_date, $to_date);
        $totalRecords = $this->ReportModel->count_all_expenses($from_date, $to_date);
        $filteredRecords = $this->ReportModel->count_filtered_expenses($searchValue, $from_date, $to_date);

        $response = [
            "draw" => intval($draw),
            "recordsTotal" => intval($totalRecords),
            "recordsFiltered" => intval($filteredRecords),
            "data" => $data
        ];

        echo json_encode($response);
    }

    public function fetch_staff()
    {
        $draw = $this->input->post('draw');
        $start = $this->input->post('start');
        $length = $this->input->post('length');
        $searchValue = $this->input->post('search')['value'];

        $data = $this->ReportModel->get_staff_ajax($start, $length, $searchValue);
        $totalRecords = $this->ReportModel->count_all_staff();
        $filteredRecords = $this->ReportModel->count_filtered_staff($searchValue);

        $response = [
            "draw" => intval($draw),
            "recordsTotal" => intval($totalRecords),
            "recordsFiltered" => intval($filteredRecords),
            "data" => $data
        ];

        echo json_encode($response);
    }
}
