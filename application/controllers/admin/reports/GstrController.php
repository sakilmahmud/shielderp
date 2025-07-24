<?php
defined('BASEPATH') or exit('No direct script access allowed');

class GstrController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('GstrModel');
        $this->load->model('SettingsModel');
        $this->load->helper('download');
        if (!$this->session->userdata('username')) {
            redirect('login');
        }
    }

    public function index()
    {
        $data['activePage'] = 'gstr-reports';
        $data['generated_reports'] = $this->db->order_by('created_at', 'DESC')->get('gst_report_exports')->result_array();
        $this->render_admin('admin/reports/gstr', $data);
    }

    public function download_report($report_id)
    {
        $report = $this->db->get_where('gst_report_exports', ['id' => $report_id])->row_array();

        if ($report && file_exists(FCPATH . $report['file_path'])) {
            force_download(FCPATH . $report['file_path'], NULL);
        } else {
            $this->session->set_flashdata('error', 'Report file not found.');
            redirect('admin/reports/gstr');
        }
    }

    public function generate_json()
    {
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        $report_type = $this->input->post('report_type');

        log_message('debug', 'GstrController: generate_json called.');
        log_message('debug', 'From Date: ' . $from_date . ', To Date: ' . $to_date . ', Report Type: ' . $report_type);

        if (empty($from_date) || empty($to_date) || empty($report_type)) {
            $this->session->set_flashdata('error', 'Please provide both dates and select a report type.');
            redirect('admin/reports/gstr');
        }

        $company_gstin = $this->GstrModel->get_company_gstin();
        $company_state_code = $this->GstrModel->get_company_state_code();

        log_message('debug', 'Company GSTIN: ' . $company_gstin . ', Company State Code: ' . $company_state_code);

        if (empty($company_gstin) || empty($company_state_code)) {
            $this->session->set_flashdata('error', 'Please set your Company GSTIN and State in Settings > Company Details.');
            redirect('admin/reports/gstr');
        }

        $json_data = [];
        $period = date('mY', strtotime($from_date)); // Format: MMYYYY
        $timestamp = date('YmdHis'); // Current timestamp for unique file name

        if ($report_type === 'gstr1') {
            log_message('debug', 'Fetching GSTR-1 data...');
            $b2b = $this->GstrModel->get_gstr1_b2b_data($from_date, $to_date);
            $b2cl = $this->GstrModel->get_gstr1_b2cl_data($from_date, $to_date);
            $b2cs = $this->GstrModel->get_gstr1_b2cs_data($from_date, $to_date);
            $hsn = $this->GstrModel->get_gstr1_hsn_data($from_date, $to_date);
            log_message('debug', 'GSTR-1 data fetched. B2B count: ' . count($b2b) . ', B2CL count: ' . count($b2cl) . ', B2CS count: ' . count($b2cs) . ', HSN count: ' . count($hsn));

            $json_data = [
                'gstin' => $company_gstin,
                'fp' => $period,
                'gt' => 0, // Gross Turnover - needs to be calculated or fetched
                'cur_gt' => 0, // Current Gross Turnover - needs to be calculated or fetched
                'b2b' => $b2b,
                'b2cl' => $b2cl,
                'b2cs' => $b2cs,
                'hsn' => [
                    'data' => $hsn
                ]
            ];

            // Calculate gt and cur_gt (simplified for now, needs actual implementation)
            // For now, setting to 0.00 as per GSTN schema if not applicable or calculated separately
            $json_data['gt'] = 0.00;
            $json_data['cur_gt'] = 0.00;

            $file_name_prefix = 'GSTR1';
        } elseif ($report_type === 'gstr3b') {
            log_message('debug', 'Fetching GSTR-3B data...');
            $gstr3b_data = $this->GstrModel->get_gstr3b_data($from_date, $to_date);
            log_message('debug', 'GSTR-3B data fetched.');

            $json_data = [
                'gstin' => $company_gstin,
                'ret_period' => $period,
                'sup_details' => $gstr3b_data['sup_details'],
                'itc_elg' => $gstr3b_data['itc_elg'],
                // Add other sections as needed for GSTR-3B
                'inter_sup' => [], // Inter-state supplies - needs implementation
                'inward_sup' => [], // Inward supplies - needs implementation
                'is_rev_chrg' => [], // Inward supplies liable to reverse charge - needs implementation
                'elg_itc' => [], // Eligible ITC - needs implementation
                'tx_pay' => [], // Tax payable - needs implementation
                'intr_itc_adj' => [], // Inter-state ITC adjustment - needs implementation
                'tds_tcs' => [], // TDS/TCS credit - needs implementation
                'gst_paid' => [] // GST paid - needs implementation
            ];
            $file_name_prefix = 'GSTR3B';
        } else {
            $this->session->set_flashdata('error', 'Invalid report type selected.');
            redirect('admin/reports/gstr');
        }

        $file_name = $file_name_prefix . '_' . $from_date . '_' . $to_date . '_' . $timestamp . '.json';
        $report_dir = FCPATH . 'gst_reports/';
        $full_file_path = $report_dir . $file_name;
        $relative_file_path = 'gst_reports/' . $file_name; // Path to store in DB

        // Create directory if it doesn't exist
        if (!is_dir($report_dir)) {
            mkdir($report_dir, 0777, TRUE);
            log_message('debug', 'Created directory: ' . $report_dir);
        }

        log_message('debug', 'JSON Data: ' . json_encode($json_data));
        $json_output = json_encode($json_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        $status = 'failed';
        $error_message = null;

        if (file_put_contents($full_file_path, $json_output)) {
            log_message('debug', 'JSON data successfully written to: ' . $full_file_path);
            $status = 'success';
            $this->session->set_flashdata('success', 'GST JSON report generated and saved to: ' . $relative_file_path);
        } else {
            log_message('error', 'Failed to write JSON data to: ' . $full_file_path);
            $status = 'failed';
            $error_message = 'Failed to write JSON data to file system.';
            $this->session->set_flashdata('error', 'Failed to generate GST JSON report. Check server logs for details.');
        }

        // Insert record into database
        $this->db->insert('gst_report_exports', [
            'report_type' => $report_type,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'file_name' => $file_name,
            'file_path' => $relative_file_path,
            'status' => $status,
            'error_message' => $error_message
        ]);

        redirect('admin/reports/gstr');
    }
}
