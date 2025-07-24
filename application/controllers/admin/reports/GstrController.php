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
                $b2b_headers = ['GSTIN/UIN of Recipient', 'Receiver Name', 'Invoice Number', 'Invoice date', 'Invoice Value', 'Place Of Supply', 'Reverse Charge', 'Applicable % of Tax Rate', 'Invoice Type', 'E-Commerce GSTIN', 'Rate', 'Taxable Value', 'Cess Amount'];
                $this->addSheetToSpreadsheet($spreadsheet, $sheetIndex++, 'b2b', $b2b_headers, $b2b_data, 'b2b');

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
                $hsn_headers = ['HSN', 'Description', 'UQC', 'Total Quantity', 'Total Value', 'Taxable Value', 'Integrated Tax Amount', 'Central Tax Amount', 'State/UT Tax Amount', 'Cess Amount', 'Rate'];
                $this->addSheetToSpreadsheet($spreadsheet, $sheetIndex++, 'hsn', $hsn_headers, $hsn_data, 'hsn');

                // Placeholder for CDNR and CDNUR - requires headers and data fetching
                // If you provide headers for CDNR and CDNUR, I can add them here.
                // Example:
                // $cdnr_data = $this->GstrModel->get_gstr1_cdnr_data($from_date, $to_date);
                // $cdnr_headers = ['CDNR Header 1', 'CDNR Header 2', ...];
                // $this->addSheetToSpreadsheet($spreadsheet, $sheetIndex++, 'cdnr', $cdnr_headers, $cdnr_data, 'cdnr');

            } elseif ($report_type === 'gstr3b') {
                // GSTR-3B Sheet
                $gstr3b_data = $this->GstrModel->get_gstr3b_data($from_date, $to_date);
                $gstr3b_headers = array_keys((array) $gstr3b_data['sup_details'][0]); // Assuming sup_details has data
                $this->addSheetToSpreadsheet($spreadsheet, $sheetIndex++, 'gstr3b', $gstr3b_headers, $gstr3b_data['sup_details'], 'gstr3b');
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

    private function addSheetToSpreadsheet($spreadsheet, $sheetIndex, $sheetName, $headers, $data, $dataType)
    {
        if ($sheetIndex > 0) {
            $spreadsheet->createSheet();
        }
        $spreadsheet->setActiveSheetIndex($sheetIndex);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($sheetName);

        // Add headers
        $sheet->fromArray($headers, NULL, 'A1');

        // Add data
        if (!empty($data)) {
            $rowData = [];
            foreach ($data as $row) {
                $csv_row = [];
                switch ($dataType) {
                    case 'b2b':
                        $csv_row = [
                            $row['gstin'] ?? '',
                            $row['receiver_name'] ?? '',
                            $row['invoice_number'] ?? '',
                            (isset($row['invoice_date']) && $row['invoice_date'] != '') ? date('d-M-y', strtotime($row['invoice_date'])) : '',
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
                    case 'b2cl':
                        $csv_row = [
                            $row['invoice_number'] ?? '',
                            (isset($row['invoice_date']) && $row['invoice_date'] != '') ? date('d-M-y', strtotime($row['invoice_date'])) : '',
                            $row['invoice_value'] ?? '',
                            $row['place_of_supply'] ?? '',
                            $row['applicable_tax_rate'] ?? '',
                            $row['rate'] ?? '',
                            $row['taxable_value'] ?? '',
                            $row['cess_amount'] ?? '',
                            $row['e_commerce_gstin'] ?? ''
                        ];
                        break;
                    case 'b2cs':
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
                    case 'hsn':
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
                    case 'gstr3b':
                        // For GSTR-3B, assuming data is already flattened or needs specific mapping
                        // This is a placeholder, adjust based on actual GSTR-3B data structure
                        $csv_row = array_values((array) $row);
                        break;
                }
                $rowData[] = $csv_row;
            }
            $sheet->fromArray($rowData, NULL, 'A2');
        } else {
            $sheet->setCellValue('A2', 'No data found for ' . $sheetName);
        }
    }
}
