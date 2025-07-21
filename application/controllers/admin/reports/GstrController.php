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
        $this->render_admin('admin/reports/gstr', $data);
    }

    public function generate_json()
    {
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        $report_type = $this->input->post('report_type');

        if (empty($from_date) || empty($to_date) || empty($report_type)) {
            $this->session->set_flashdata('error', 'Please provide both dates and select a report type.');
            redirect('admin/reports/gstr');
        }

        $company_gstin = $this->GstrModel->get_company_gstin();
        $company_state_code = $this->GstrModel->get_company_state_code();

        if (empty($company_gstin) || empty($company_state_code)) {
            $this->session->set_flashdata('error', 'Please set your Company GSTIN and State in Settings > Company Details.');
            redirect('admin/reports/gstr');
        }

        $json_data = [];
        $period = date('mY', strtotime($from_date)); // Format: MMYYYY

        if ($report_type === 'gstr1') {
            $b2b = $this->GstrModel->get_gstr1_b2b_data($from_date, $to_date);
            $b2cl = $this->GstrModel->get_gstr1_b2cl_data($from_date, $to_date);
            $b2cs = $this->GstrModel->get_gstr1_b2cs_data($from_date, $to_date);
            $hsn = $this->GstrModel->get_gstr1_hsn_data($from_date, $to_date);

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

            $file_name = 'GSTR1_' . $company_gstin . '_' . $period . '.json';
        } elseif ($report_type === 'gstr3b') {
            $gstr3b_data = $this->GstrModel->get_gstr3b_data($from_date, $to_date);

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
            $file_name = 'GSTR3B_' . $company_gstin . '_' . $period . '.json';
        }

        $json_output = json_encode($json_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        force_download($file_name, $json_output);
    }
}