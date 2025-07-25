<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

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

        if (empty($from_date) || empty($to_date) || empty($report_type)) {
            $this->session->set_flashdata('error', 'Please provide both dates and select a report type.');
            redirect('admin/reports/gstr');
        }

        $company_gstin = $this->GstrModel->get_company_gstin();
        $period = date('mY', strtotime($from_date));
        $timestamp = date('YmdHis');

        $json_data = [];
        $file_name_prefix = '';

        if ($report_type === 'gstr1') {
            $file_name_prefix = 'GSTR1';
            $b2b_data = $this->GstrModel->get_gstr1_b2b_data($from_date, $to_date);
            // Assuming CDNR, B2BA, CDNRA data fetching functions exist or return empty arrays if no data
            // If the model has functions for these, they should be called here.
            // For example:
            // $cdnr_data = $this->GstrModel->get_gstr1_cdnr_data($from_date, $to_date);
            // $b2ba_data = $this->GstrModel->get_gstr1_b2ba_data($from_date, $to_date);
            // $cdnra_data = $this->GstrModel->get_gstr1_cdnra_data($from_date, $to_date);

            $json_data = [
                'gstin' => $company_gstin,
                'fp' => $period,
                'gt' => 0.00, // Gross Turnover - needs to be calculated or fetched
                'cur_gt' => 0.00, // Current Gross Turnover - needs to be calculated or fetched
                'b2b' => $b2b_data,
                'cdnr' => [], // Placeholder for CDNR data
                'b2ba' => [], // Placeholder for B2BA data
                'cdnra' => []  // Placeholder for CDNRA data
            ];
        } elseif ($report_type === 'gstr3b') {
            $file_name_prefix = 'GSTR3B';
            $gstr3b_data = $this->GstrModel->get_gstr3b_data($from_date, $to_date);

            $json_data = [
                'gstin' => $company_gstin,
                'ret_period' => $period,
                'sup_details' => $gstr3b_data['sup_details'],
                'itc_elg' => $gstr3b_data['itc_elg'],
                'inter_sup' => [],
                'inward_sup' => [],
                'is_rev_chrg' => [],
                'elg_itc' => [],
                'tx_pay' => [],
                'intr_itc_adj' => [],
                'tds_tcs' => [],
                'gst_paid' => []
            ];
        } else {
            $this->session->set_flashdata('error', 'Invalid report type selected.');
            redirect('admin/reports/gstr');
            return;
        }

        $file_name = $file_name_prefix . '_' . $from_date . '_' . $to_date . '_' . $timestamp . '.json';
        $report_dir = FCPATH . 'gst_reports/';
        $full_file_path = $report_dir . $file_name;
        $relative_file_path = 'gst_reports/' . $file_name;

        if (!is_dir($report_dir)) {
            mkdir($report_dir, 0777, TRUE);
        }

        $json_output = json_encode($json_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        $status = 'failed';
        $error_message = null;

        if (file_put_contents($full_file_path, $json_output)) {
            $status = 'success';
            $this->session->set_flashdata('success', 'GST JSON report generated and saved to: ' . $relative_file_path);
        } else {
            $error_message = 'Failed to write JSON data to file system.';
            $this->session->set_flashdata('error', 'Failed to generate GST JSON report. Check server logs for details.');
        }

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

    public function generate_csv()
    {
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        $report_type = $this->input->post('report_type');

        log_message('debug', 'GstrController: generate_csv called.');
        log_message('debug', 'From Date: ' . $from_date . ', To Date: ' . $to_date . ', Report Type: ' . $report_type);

        if (empty($from_date) || empty($to_date) || empty($report_type)) {
            $this->session->set_flashdata('error', 'Please provide both dates and select a report type.');
            redirect('admin/reports/gstr');
            return;
        }

        $file_name_prefix = '';
        $timestamp = date('YmdHis');

        switch ($report_type) {
            case 'gstr1':
                $file_name_prefix = 'GSTR1';
                break;
            case 'gstr3b':
                $file_name_prefix = 'GSTR3B';
                break;
            default:
                $this->session->set_flashdata('error', 'Invalid report type selected.');
                redirect('admin/reports/gstr');
                return;
        }

        $file_name = $file_name_prefix . '_' . $from_date . '_' . $to_date . '_' . $timestamp . '.csv';
        $report_dir = FCPATH . 'gst_reports/';
        $full_file_path = $report_dir . $file_name;
        $relative_file_path = 'gst_reports/' . $file_name;

        if (!is_dir($report_dir)) {
            if (!mkdir($report_dir, 0777, TRUE)) {
                log_message('error', 'Failed to create directory: ' . $report_dir);
                $this->session->set_flashdata('error', 'Failed to generate GST CSV report. Could not create report directory.');
                redirect('admin/reports/gstr');
                return;
            }
            log_message('debug', 'Created directory: ' . $report_dir);
        }

        $output_handle = null;
        $status = 'failed';
        $error_message = null;

        try {
            $output_handle = fopen($full_file_path, 'w');
            if ($output_handle === FALSE) {
                log_message('error', 'Failed to open file for writing CSV: ' . $full_file_path);
                $this->session->set_flashdata('error', 'Failed to generate GST CSV report. Could not open file for writing. Check file permissions.');
                throw new Exception('File open failed.');
            }

            if (!is_resource($output_handle)) {
                log_message('error', 'CSV: $output_handle is NOT a resource after fopen. Path: ' . $full_file_path);
                $this->session->set_flashdata('error', 'Failed to generate GST CSV report. Internal file handle error after opening.');
                throw new Exception('Invalid file handle after open.');
            }

            switch ($report_type) {
                case 'gstr1':
                    log_message('debug', 'Fetching GSTR-1 data for CSV...');
                    $b2b = $this->GstrModel->get_gstr1_b2b_data($from_date, $to_date);
                    $b2cl = $this->GstrModel->get_gstr1_b2cl_data($from_date, $to_date);
                    $b2cs = $this->GstrModel->get_gstr1_b2cs_data($from_date, $to_date);
                    $hsn = $this->GstrModel->get_gstr1_hsn_data($from_date, $to_date);

                    $sections = [
                        'B2B' => $b2b,
                        'B2CL' => $b2cl,
                        'B2CS' => $b2cs,
                        'HSN' => $hsn
                    ];

                    foreach ($sections as $section_name => $section_data) {
                        fputcsv($output_handle, []); // Empty row for separation
                        fputcsv($output_handle, [$section_name . ' Data']); // Section header

                        $headers = [];
                        switch ($section_name) {
                            case 'B2B':
                                $headers = ['GSTIN/UIN of Recipient', 'Receiver Name', 'Invoice Number', 'Invoice date', 'Invoice Value', 'Place Of Supply', 'Reverse Charge', 'Applicable % of Tax Rate', 'Invoice Type', 'E-Commerce GSTIN', 'Rate', 'Taxable Value', 'Cess Amount'];
                                break;
                            case 'B2CL':
                                $headers = ['Invoice Number', 'Invoice date', 'Invoice Value', 'Place Of Supply', 'Applicable % of Tax Rate', 'Rate', 'Taxable Value', 'Cess Amount', 'E-Commerce GSTIN'];
                                break;
                            case 'B2CS':
                                $headers = ['Type', 'Place Of Supply', 'Rate', 'Applicable % of Tax Rate', 'Taxable Value', 'Cess Amount', 'E-Commerce GSTIN'];
                                break;
                            case 'HSN':
                                $headers = ['HSN', 'Description', 'UQC', 'Total Quantity', 'Total Value', 'Taxable Value', 'Integrated Tax Amount', 'Central Tax Amount', 'State/UT Tax Amount', 'Cess Amount', 'Rate'];
                                break;
                        }

                        if (!empty($section_data)) {
                            fputcsv($output_handle, $headers);
                            foreach ($section_data as $row) {
                                $csv_row = [];
                                switch ($section_name) {
                                    case 'B2B':
                                        $csv_row = [
                                            $row['gstin'] ?? '',
                                            $row['receiver_name'] ?? '',
                                            $row['invoice_number'] ?? '',
                                            date('d-M-y', strtotime($row['invoice_date'])) ?? '',
                                            $row['invoice_value'] ?? '',
                                            $row['place_of_supply'] ?? '',
                                            $row['reverse_charge'] ?? '',
                                            $row['applicable_tax_rate'] ?? '',
                                            $row['invoice_type'] ?? '',
                                            $row['e_commerce_gstin'] ?? '',
                                            $row['rate'] ?? '',
                                            $row['taxable_value'] ?? '',
                                            $row['cess_amount'] ?? ''
                                        ];
                                        break;
                                    case 'B2CL':
                                        $csv_row = [
                                            $row['invoice_number'] ?? '',
                                            date('d-M-y', strtotime($row['invoice_date'])) ?? '',
                                            $row['invoice_value'] ?? '',
                                            $row['place_of_supply'] ?? '',
                                            $row['applicable_tax_rate'] ?? '',
                                            $row['rate'] ?? '',
                                            $row['taxable_value'] ?? '',
                                            $row['cess_amount'] ?? '',
                                            $row['e_commerce_gstin'] ?? ''
                                        ];
                                        break;
                                    case 'B2CS':
                                        $csv_row = [
                                            $row['type'] ?? '',
                                            $row['place_of_supply'] ?? '',
                                            $row['rate'] ?? '',
                                            $row['applicable_tax_rate'] ?? '',
                                            $row['taxable_value'] ?? '',
                                            $row['cess_amount'] ?? '',
                                            $row['e_commerce_gstin'] ?? ''
                                        ];
                                        break;
                                    case 'HSN':
                                        $csv_row = [
                                            $row['hsn'] ?? '',
                                            $row['description'] ?? '',
                                            $row['uqc'] ?? '',
                                            $row['total_quantity'] ?? '',
                                            $row['total_value'] ?? '',
                                            $row['taxable_value'] ?? '',
                                            $row['integrated_tax_amount'] ?? '',
                                            $row['central_tax_amount'] ?? '',
                                            $row['state_ut_tax_amount'] ?? '',
                                            $row['cess_amount'] ?? '',
                                            $row['rate'] ?? ''
                                        ];
                                        break;
                                }
                                fputcsv($output_handle, $csv_row);
                            }
                        } else {
                            fputcsv($output_handle, ['No data found for ' . $section_name]);
                        }
                    }
                    break;
                case 'gstr3b':
                    log_message('debug', 'Fetching GSTR-3B data for CSV...');
                    $gstr3b_data = $this->GstrModel->get_gstr3b_data($from_date, $to_date);

                    fputcsv($output_handle, ['GSTR-3B Supply Details']);
                    if (!empty($gstr3b_data['sup_details'])) {
                        fputcsv($output_handle, array_keys((array) $gstr3b_data['sup_details'][0]));
                        foreach ($gstr3b_data['sup_details'] as $row) {
                            fputcsv($output_handle, (array) $row);
                        }
                    } else {
                        fputcsv($output_handle, ['No data found for GSTR-3B Supply Details']);
                    }
                    break;
            }
            $status = 'success';
            $this->session->set_flashdata('success', 'GST CSV report generated and saved to: ' . $relative_file_path);
        } catch (Exception $e) {
            log_message('error', 'Error during CSV generation: ' . $e->getMessage());
            $error_message = 'Error during CSV generation: ' . $e->getMessage();
            $this->session->set_flashdata('error', 'Failed to generate GST CSV report. An unexpected error occurred.');
        } finally {
            if (is_resource($output_handle)) {
                fclose($output_handle);
            }
        }

        // Insert record into database
        $this->db->insert('gst_report_exports', [
            'report_type' => $report_type . '_csv',
            'from_date' => $from_date,
            'to_date' => $to_date,
            'file_name' => $file_name,
            'file_path' => $relative_file_path,
            'status' => $status,
            'error_message' => $error_message
        ]);

        redirect('admin/reports/gstr');
    }

    public function generate_xlsx()
    {
        // Load PhpSpreadsheet classes
        require_once FCPATH . 'vendor/autoload.php';

        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        $report_type = $this->input->post('report_type');

        log_message('debug', 'GstrController: generate_xlsx called.');
        log_message('debug', 'From Date: ' . $from_date . ', To Date: ' . $to_date . ', Report Type: ' . $report_type);

        if (empty($from_date) || empty($to_date) || empty($report_type)) {
            $this->session->set_flashdata('error', 'Please provide both dates and select a report type.');
            redirect('admin/reports/gstr');
            return;
        }

        $file_name_prefix = '';
        $timestamp = date('YmdHis');

        switch ($report_type) {
            case 'gstr1':
                $file_name_prefix = 'GSTR1';
                break;
            case 'gstr3b':
                $file_name_prefix = 'GSTR3B';
                break;
            default:
                $this->session->set_flashdata('error', 'Invalid report type selected.');
                redirect('admin/reports/gstr');
                return;
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheetIndex = 0;

        $status = 'failed';
        $error_message = null;

        try {
            if ($report_type === 'gstr1') {
                // B2B Sheet
                $b2b_data = $this->GstrModel->get_gstr1_b2b_data($from_date, $to_date);

                $num_recipients = 0;
                $num_invoices = 0;
                $total_invoice_value = 0;
                $total_taxable_value = 0;
                $total_cess = 0;

                $unique_recipients = [];
                $unique_invoices = [];

                foreach ($b2b_data as $gstin_data) {
                    $unique_recipients[$gstin_data['ctin']] = true;
                    foreach ($gstin_data['inv'] as $inv) {
                        $unique_invoices[$inv['inum']] = true;
                        $total_invoice_value += (float)($inv['val'] ?? 0);
                        foreach ($inv['itms'] as $item) {
                            $item_details = $item['itm_det'];
                            $total_taxable_value += (float)($item_details['txval'] ?? 0);
                            $total_cess += (float)($item_details['csamt'] ?? 0);
                        }
                    }
                }

                $num_recipients = count($unique_recipients);
                $num_invoices = count($unique_invoices);

                $b2b_summary = [
                    'num_recipients' => $num_recipients,
                    'num_invoices' => $num_invoices,
                    'total_invoice_value' => round($total_invoice_value, 2),
                    'total_taxable_value' => round($total_taxable_value, 2),
                    'total_cess' => round($total_cess, 2)
                ];

                $b2b_headers = ['GSTIN/UIN of Recipient', 'Receiver Name', 'Invoice Number', 'Invoice date', 'Invoice Value', 'Place Of Supply', 'Reverse Charge', 'Invoice Type', 'Taxable Value', 'Integrated Tax Amount', 'Central Tax Amount', 'State/UT Tax Amount', 'Cess Amount'];
                log_message('debug', 'B2B Data for XLSX: ' . json_encode($b2b_data));
                $this->addSheetToSpreadsheet($spreadsheet, $sheetIndex++, 'b2b', $b2b_headers, $b2b_data, 'b2b', $b2b_summary);

                // B2CL Sheet
                $b2cl_data = $this->GstrModel->get_gstr1_b2cl_data($from_date, $to_date);
                $b2cl_headers = ['Invoice Number', 'Invoice date', 'Invoice Value', 'Place Of Supply', 'Applicable % of Tax Rate', 'Rate', 'Taxable Value', 'Cess Amount', 'E-Commerce GSTIN'];
                $this->addSheetToSpreadsheet($spreadsheet, $sheetIndex++, 'b2cl', $b2cl_headers, $b2cl_data, 'b2cl');

                // B2CS Sheet
                $b2cs_data = $this->GstrModel->get_gstr1_b2cs_data($from_date, $to_date);
                $b2cs_headers = ['Type', 'Place Of Supply', 'Rate', 'Applicable % of Tax Rate', 'Taxable Value', 'Cess Amount', 'E-Commerce GSTIN'];
                $this->addSheetToSpreadsheet($spreadsheet, $sheetIndex++, 'b2cs', $b2cs_headers, $b2cs_data, 'b2cs');

                // HSN Sheet
                $hsn_data = $this->GstrModel->get_gstr1_hsn_data($from_date, $to_date);

                $total_hsn_value = 0;
                $total_hsn_taxable_value = 0;
                $total_hsn_integrated_tax_amount = 0;
                $total_hsn_central_tax_amount = 0;
                $total_hsn_state_ut_tax_amount = 0;
                $total_hsn_cess_amount = 0;

                foreach ($hsn_data as $hsn_row) {
                    $total_hsn_value += (float)(($hsn_row['txval'] ?? 0) + ($hsn_row['iamt'] ?? 0) + ($hsn_row['camt'] ?? 0) + ($hsn_row['samt'] ?? 0) + ($hsn_row['csamt'] ?? 0));
                    $total_hsn_taxable_value += (float)($hsn_row['txval'] ?? 0);
                    $total_hsn_integrated_tax_amount += (float)($hsn_row['iamt'] ?? 0);
                    $total_hsn_central_tax_amount += (float)($hsn_row['camt'] ?? 0);
                    $total_hsn_state_ut_tax_amount += (float)($hsn_row['samt'] ?? 0);
                    $total_hsn_cess_amount += (float)($hsn_row['csamt'] ?? 0);
                }

                $hsn_summary = [
                    'num_hsn' => count($hsn_data),
                    'total_value' => round($total_hsn_value, 2),
                    'total_taxable_value' => round($total_hsn_taxable_value, 2),
                    'total_integrated_tax_amount' => round($total_hsn_integrated_tax_amount, 2),
                    'total_central_tax_amount' => round($total_hsn_central_tax_amount, 2),
                    'total_state_ut_tax_amount' => round($total_hsn_state_ut_tax_amount, 2),
                    'total_cess_amount' => round($total_hsn_cess_amount, 2)
                ];

                $hsn_headers = ['HSN', 'Description', 'UQC', 'Total Quantity', 'Total Value', 'Taxable Value', 'Integrated Tax Amount', 'Central Tax Amount', 'State/UT Tax Amount', 'Cess Amount', 'Rate'];
                $this->addSheetToSpreadsheet($spreadsheet, $sheetIndex++, 'hsn', $hsn_headers, $hsn_data, 'hsn', $hsn_summary);
            } elseif ($report_type === 'gstr3b') {
                // GSTR-3B Sheet
                $gstr3b_data = $this->GstrModel->get_gstr3b_data($from_date, $to_date);

                // Table 3.1
                $sheet3_1 = $spreadsheet->getActiveSheet();
                $sheet3_1->setTitle('Table 3.1');
                $sheetIndex++;

                $sheet3_1->getStyle('A1:F1')->getFont()->setBold(true);
                $sheet3_1->getColumnDimension('A')->setWidth(70);
                $sheet3_1->getColumnDimension('B')->setWidth(20);
                $sheet3_1->getColumnDimension('C')->setWidth(20);
                $sheet3_1->getColumnDimension('D')->setWidth(20);
                $sheet3_1->getColumnDimension('E')->setWidth(20);
                $sheet3_1->getColumnDimension('F')->setWidth(20);

                $sheet3_1->setCellValue('A1', 'Nature of Supplies');
                $sheet3_1->setCellValue('B1', 'Total Taxable Value');
                $sheet3_1->setCellValue('C1', 'Integrated Tax');
                $sheet3_1->setCellValue('D1', 'Central Tax');
                $sheet3_1->setCellValue('E1', 'State/UT Tax');
                $sheet3_1->setCellValue('F1', 'Cess');

                $osup_det = $gstr3b_data['sup_details']['osup_det'] ?? [];
                $sheet3_1->setCellValue('A2', '(a) Outward Taxable supplies (other than zero rated, nil rated and exempted)');
                $sheet3_1->setCellValue('B2', (float)($osup_det['txval'] ?? 0));
                $sheet3_1->setCellValue('C2', (float)($osup_det['iamt'] ?? 0));
                $sheet3_1->setCellValue('D2', (float)($osup_det['camt'] ?? 0));
                $sheet3_1->setCellValue('E2', (float)($osup_det['samt'] ?? 0));
                $sheet3_1->setCellValue('F2', (float)($osup_det['csamt'] ?? 0));

                $sheet3_1->setCellValue('A3', '(b) Outward Taxable supplies (zero rated )');

                $sheet3_1->setCellValue('A4', '(c) Other Outward Taxable supplies (Nil rated, exempted)');

                $rchrg_data = $gstr3b_data['sup_details']['inward_sup']['rchrg'] ?? [];
                $sheet3_1->setCellValue('A5', '(d) Inward supplies (liable to reverse charge)');
                $sheet3_1->setCellValue('B5', (float)($rchrg_data['txval'] ?? 0));
                $sheet3_1->setCellValue('C5', (float)($rchrg_data['iamt'] ?? 0));
                $sheet3_1->setCellValue('D5', (float)($rchrg_data['camt'] ?? 0));
                $sheet3_1->setCellValue('E5', (float)($rchrg_data['samt'] ?? 0));
                $sheet3_1->setCellValue('F5', (float)($rchrg_data['csamt'] ?? 0));

                $sheet3_1->setCellValue('A6', '(e) Non-GST Outward supplies');


                // Table 4
                $spreadsheet->createSheet();
                $sheet4 = $spreadsheet->getSheet($sheetIndex++);
                $sheet4->setTitle('Table 4');

                $sheet4->getStyle('A1:E1')->getFont()->setBold(true);
                $sheet4->getColumnDimension('A')->setWidth(50);
                $sheet4->getColumnDimension('B')->setWidth(20);
                $sheet4->getColumnDimension('C')->setWidth(20);
                $sheet4->getColumnDimension('D')->setWidth(20);
                $sheet4->getColumnDimension('E')->setWidth(20);

                $sheet4->setCellValue('A1', 'Details');
                $sheet4->setCellValue('B1', 'Integrated Tax');
                $sheet4->setCellValue('C1', 'Central Tax');
                $sheet4->setCellValue('D1', 'State/UT Tax');
                $sheet4->setCellValue('E1', 'Cess');

                $sheet4->setCellValue('A2', '(A) ITC Available (Whether in full or part)');
                $sheet4->getStyle('A2')->getFont()->setBold(true);
                $sheet4->setCellValue('A3', '  (1) Import of goods');
                $sheet4->setCellValue('A4', '  (2) Import of services');
                $sheet4->setCellValue('A5', '  (3) Inward supplies liable to reverse charge');
                $sheet4->setCellValue('A6', '  (4) Inward supplies from ISD');
                $sheet4->setCellValue('A7', '  (5) All other ITC');

                $itc_avl = $gstr3b_data['itc_elg']['itc_avl'] ?? [];
                $itc_map = [];
                foreach ($itc_avl as $itc) {
                    $itc_map[$itc['ty']] = $itc['val'];
                }

                $sheet4->setCellValue('B7', $itc_map['IGST'] ?? 0.00);
                $sheet4->setCellValue('C7', $itc_map['CGST'] ?? 0.00);
                $sheet4->setCellValue('D7', $itc_map['SGST'] ?? 0.00);
                $sheet4->setCellValue('E7', $itc_map['CESS'] ?? 0.00);

                $sheet4->setCellValue('A8', '(B) ITC Reversed');
                $sheet4->getStyle('A8')->getFont()->setBold(true);
                $sheet4->setCellValue('A9', '  (1) As per Rule 42 & 43 of SGST/CGST rules');
                $sheet4->setCellValue('A10', '  (2) Others');

                $sheet4->setCellValue('A11', '(C) Net ITC Available (A)-(B)');
                $sheet4->getStyle('A11')->getFont()->setBold(true);
                $sheet4->setCellValue('B11', $itc_map['IGST'] ?? 0.00); // Assuming B is 0
                $sheet4->setCellValue('C11', $itc_map['CGST'] ?? 0.00); // Assuming B is 0
                $sheet4->setCellValue('D11', $itc_map['SGST'] ?? 0.00); // Assuming B is 0
                $sheet4->setCellValue('E11', $itc_map['CESS'] ?? 0.00); // Assuming B is 0


                $sheet4->setCellValue('A12', '(D) Ineligible ITC');
                $sheet4->getStyle('A12')->getFont()->setBold(true);
                $sheet4->setCellValue('A13', '  (1) As per section 17(5) of CGST//SGST Act');
                $sheet4->setCellValue('A14', '  (2) Others');

                // Table 5
                $sheet5 = $spreadsheet->createSheet();
                $sheet5->setTitle('Table 5');
                $sheetIndex++;

                $sheet5->getStyle('A1:C1')->getFont()->setBold(true);
                $sheet5->getColumnDimension('A')->setWidth(50);
                $sheet5->getColumnDimension('B')->setWidth(20);
                $sheet5->getColumnDimension('C')->setWidth(20);

                $sheet5->setCellValue('A1', 'Nature of Supplies');
                $sheet5->setCellValue('B1', 'Inter-state Supplies');
                $sheet5->setCellValue('C1', 'Intra-state Supplies');

                $sheet5->setCellValue('A2', 'From a supplier under composition scheme, Exempt and Nil rated supply');
                $sheet5->setCellValue('A3', 'Non GST supply');
                $sheet5->setCellValue('A4', 'Total');
                $sheet5->getStyle('A4')->getFont()->setBold(true);
            }

            $file_name = $file_name_prefix . '_' . $from_date . '_' . $to_date . '_' . $timestamp . '.xlsx';
            $report_dir = FCPATH . 'gst_reports/';
            $full_file_path = $report_dir . $file_name;
            $relative_file_path = 'gst_reports/' . $file_name;

            if (!is_dir($report_dir)) {
                mkdir($report_dir, 0777, TRUE);
                log_message('debug', 'Created directory: ' . $report_dir);
            }

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save($full_file_path);

            $status = 'success';
            $this->session->set_flashdata('success', 'GST XLSX report generated and saved to: ' . $relative_file_path);
        } catch (Exception $e) {
            log_message('error', 'Error during XLSX generation: ' . $e->getMessage());
            $error_message = 'Error during XLSX generation: ' . $e->getMessage();
            $this->session->set_flashdata('error', 'Failed to generate GST XLSX report. An unexpected error occurred.');
        }

        // Insert record into database
        $this->db->insert('gst_report_exports', [
            'report_type' => $report_type . '_xlsx',
            'from_date' => $from_date,
            'to_date' => $to_date,
            'file_name' => $file_name,
            'file_path' => $relative_file_path,
            'status' => $status,
            'error_message' => $error_message
        ]);

        redirect('admin/reports/gstr');
    }

    private function addSheetToSpreadsheet($spreadsheet, $sheetIndex, $sheetName, $headers, $data, $dataType, $summaryData = null)
    {
        if ($sheetIndex > 0) {
            $spreadsheet->createSheet();
        }
        $spreadsheet->setActiveSheetIndex($sheetIndex);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($sheetName);

        $summaryHeaderStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0070C0']] // Blue
        ];

        $mainHeaderStyle = [
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D9D9D9']] // Light Gray
        ];

        $currentRow = 1;

        // Add summary data if provided
        if (($sheetName === 'b2b' || $sheetName === 'hsn') && is_array($summaryData)) {
            $summaryHeaders = [];
            $summaryValues = [];
            if ($sheetName === 'b2b') {
                $summaryHeaders = ['No. of Recipients', 'No. of Invoices', 'Total Invoice Value', 'Total Taxable Value', 'Total Cess'];
                $summaryValues = [
                    $summaryData['num_recipients'],
                    $summaryData['num_invoices'],
                    $summaryData['total_invoice_value'],
                    $summaryData['total_taxable_value'],
                    $summaryData['total_cess']
                ];
            } else { // hsn
                $summaryHeaders = ['No. of HSN', 'Total Value', 'Total Taxable Value', 'Total Integrated Tax', 'Total Central Tax', 'Total State/UT Tax', 'Total Cess'];
                $summaryValues = [
                    $summaryData['num_hsn'],
                    $summaryData['total_value'],
                    $summaryData['total_taxable_value'],
                    $summaryData['total_integrated_tax_amount'],
                    $summaryData['total_central_tax_amount'],
                    $summaryData['total_state_ut_tax_amount'],
                    $summaryData['total_cess_amount']
                ];
            }

            $sheet->fromArray([$summaryHeaders], NULL, 'A' . $currentRow);
            $summaryHeaderRange = 'A' . $currentRow . ':' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($summaryHeaders)) . $currentRow;
            $sheet->getStyle($summaryHeaderRange)->applyFromArray($summaryHeaderStyle);
            $currentRow++;
            $sheet->fromArray([$summaryValues], NULL, 'A' . $currentRow);
            $currentRow += 2;
        }

        // Add main headers
        $sheet->fromArray($headers, NULL, 'A' . $currentRow);
        $highestColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($headers));
        $headerRange = 'A' . $currentRow . ':' . $highestColumn . $currentRow;
        $sheet->getStyle($headerRange)->applyFromArray($mainHeaderStyle);
        $currentRow++;

        // Add data
        if (!empty($data)) {
            $rowData = [];
            foreach ($data as $row) {
                $csv_row = [];
                switch ($dataType) {
                    case 'b2b':
                        $gstin = $row['ctin'] ?? '';
                        $receiver_name = $row['receiver_name'] ?? '';

                        foreach ($row['inv'] as $inv) {
                            $invoice_number = $inv['inum'] ?? '';
                            $invoice_date = (isset($inv['idt']) && $inv['idt'] != '') ? date('d-M-y', strtotime($inv['idt'])) : '';
                            $invoice_value = $inv['val'] ?? '';
                            $pos = $inv['pos'] ?? '';
                            $rchrg = $inv['rchrg'] ?? '';
                            $inv_typ = $inv['inv_typ'] ?? '';

                            $total_invoice_taxable_value = 0;
                            $total_invoice_iamt = 0;
                            $total_invoice_camt = 0;
                            $total_invoice_samt = 0;
                            $total_invoice_csamt = 0;

                            foreach ($inv['itms'] as $item) {
                                $item_details = $item['itm_det'];
                                $total_invoice_taxable_value += (float)($item_details['txval'] ?? 0);
                                $total_invoice_iamt += (float)($item_details['iamt'] ?? 0);
                                $total_invoice_camt += (float)($item_details['camt'] ?? 0);
                                $total_invoice_samt += (float)($item_details['samt'] ?? 0);
                                $total_invoice_csamt += (float)($item_details['csamt'] ?? 0);
                            }

                            $rowData[] = [
                                $gstin,
                                $receiver_name,
                                $invoice_number,
                                $invoice_date,
                                $invoice_value,
                                $pos,
                                $rchrg,
                                $inv_typ,
                                round($total_invoice_taxable_value, 2),
                                round($total_invoice_iamt, 2),
                                round($total_invoice_camt, 2),
                                round($total_invoice_samt, 2),
                                round($total_invoice_csamt, 2)
                            ];
                        }
                        break;
                    case 'b2cl':
                        foreach ($data as $pos_data) {
                            $pos = $pos_data['pos'] ?? '';
                            foreach ($pos_data['inv'] as $inv) {
                                $invoice_number = $inv['inum'] ?? '';
                                $invoice_date = (isset($inv['idt']) && $inv['idt'] != '') ? date('d-M-y', strtotime($inv['idt'])) : '';
                                $invoice_value = $inv['val'] ?? '';

                                foreach ($inv['itms'] as $item) {
                                    $item_details = $item['itm_det'];
                                    $rowData[] = [
                                        $invoice_number,
                                        $invoice_date,
                                        $invoice_value,
                                        $pos,
                                        $item_details['rt'] ?? '', // Applicable % of Tax Rate
                                        $item_details['rt'] ?? '',
                                        $item_details['txval'] ?? '',
                                        $item_details['csamt'] ?? '',
                                        '' // E-Commerce GSTIN
                                    ];
                                }
                            }
                        }
                        break;
                    case 'b2cs':
                        foreach ($data as $summary_row) {
                            $rowData[] = [
                                $summary_row['sp_typ'] ?? '', // Type (Supply Type)
                                $summary_row['pos'] ?? '',
                                $summary_row['rt'] ?? '',
                                $summary_row['rt'] ?? '', // Applicable % of Tax Rate
                                $summary_row['txval'] ?? '',
                                $summary_row['csamt'] ?? '',
                                '' // E-Commerce GSTIN
                            ];
                        }
                        break;
                    case 'hsn':
                        foreach ($data as $hsn_row) {
                            $rowData[] = [
                                $hsn_row['num'] ?? '', // HSN
                                $hsn_row['desc'] ?? '', // Description
                                $hsn_row['uqc'] ?? '', // UQC
                                $hsn_row['qty'] ?? '', // Total Quantity
                                ($hsn_row['txval'] ?? 0) + ($hsn_row['iamt'] ?? 0) + ($hsn_row['camt'] ?? 0) + ($hsn_row['samt'] ?? 0) + ($hsn_row['csamt'] ?? 0), // Total Value (sum of all tax components and taxable value)
                                $hsn_row['txval'] ?? '', // Taxable Value
                                $hsn_row['iamt'] ?? '', // Integrated Tax Amount
                                $hsn_row['camt'] ?? '', // Central Tax Amount
                                $hsn_row['samt'] ?? '', // State/UT Tax Amount
                                $hsn_row['csamt'] ?? '', // Cess Amount
                                $hsn_row['rt'] ?? '' // Rate
                            ];
                        }
                        break;
                    case 'gstr3b_table3_1':
                    case 'gstr3b_table4':
                    case 'gstr3b_table5':
                        $rowData = $data;
                        break;
                }
            }
            if (!empty($rowData)) {
                $sheet->fromArray($rowData, NULL, 'A' . $currentRow);
            } else {
                $sheet->setCellValue('A' . $currentRow, 'No data found for ' . $sheetName);
            }
        } else {
            $sheet->setCellValue('A' . $currentRow, 'No data found for ' . $sheetName);
        }

        // Auto-size columns
        foreach (range('A', $highestColumn) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    }

    public function delete_report($report_id)
    {
        $this->output->set_content_type('application/json');

        // Fetch report details from the database
        $report = $this->db->get_where('gst_report_exports', ['id' => $report_id])->row_array();

        if (!$report) {
            echo json_encode(['status' => 'error', 'message' => 'Report not found.']);
            exit;
        }

        // Delete physical file if it exists
        $full_file_path = FCPATH . $report['file_path'];
        if (file_exists($full_file_path)) {
            if (unlink($full_file_path)) {
                log_message('debug', 'Physical file deleted: ' . $full_file_path);
            } else {
                log_message('error', 'Failed to delete physical file: ' . $full_file_path);
                // Even if file deletion fails, we might still want to remove the DB record
            }
        } else {
            log_message('debug', 'Physical file not found, skipping deletion: ' . $full_file_path);
        }

        // Delete record from the database
        if ($this->db->delete('gst_report_exports', ['id' => $report_id])) {
            echo json_encode(['status' => 'success', 'message' => 'Report deleted successfully.']);
        } else {
            log_message('error', 'Failed to delete report record from DB: ' . $report_id);
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete report from database.']);
        }
        exit;
    }
}
