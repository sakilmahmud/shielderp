<?php
defined('BASEPATH') or exit('No direct script access allowed');

class InventoryController extends MY_Controller
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

        $this->load->model('reports/InventoryModel');
        $this->load->model('CategoryModel');
        $this->load->model('BrandModel');
        $this->load->library('pdf'); // For Dompdf
    }

    public function index()
    {
        $data['activePage'] = 'inventory';
        $this->render_admin('admin/reports/inventory', $data);
    }

    public function stock_availability()
    {
        $data['activePage'] = 'stock_availability';
        $data['categories'] = $this->CategoryModel->get_all_categories();
        $data['brands'] = $this->BrandModel->get_all_brands();

        $this->render_admin('admin/reports/inventory/stock_availability', $data);
    }

    public function fast_moving_items()
    {
        $data['activePage'] = 'fast_moving_items';
        $data['fast_moving_items'] = $this->InventoryModel->get_fast_moving_items();

        $this->render_admin('admin/reports/inventory/fast_moving_items', $data);
    }

    public function items_not_moving()
    {
        $data['activePage'] = 'items_not_moving';
        $data['items_not_moving'] = $this->InventoryModel->get_items_not_moving();

        $this->render_admin('admin/reports/inventory/items_not_moving', $data);
    }

    public function fetch_stock_availability()
    {
        $draw = $this->input->post('draw');
        $start = $this->input->post('start');
        $length = $this->input->post('length');
        $searchValue = $this->input->post('search')['value'];
        $category_id = $this->input->post('category_id');
        $brand_id = $this->input->post('brand_id');

        $data = $this->InventoryModel->get_stock_availability_ajax($start, $length, $searchValue, $category_id, $brand_id);

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $this->InventoryModel->count_all_stock(),
            "recordsFiltered" => $this->InventoryModel->count_filtered_stock($searchValue, $category_id, $brand_id),
            "data" => $data
        );

        echo json_encode($output);
    }

    public function export_stock_availability($format)
    {
        $data = $this->InventoryModel->get_stock_availability_ajax(null, null, null, null, null); // Get all data for export

        if ($format == 'xlsx') {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set headers
            $headers = ['SNo.', 'Item Name', 'Current Stock', 'Unit', 'Valuation', 'Category', 'Brand'];
            $sheet->fromArray($headers, NULL, 'A1');

            // Make headers bold
            $sheet->getStyle('A1:G1')->getFont()->setBold(true);

            // Populate data
            $row = 2;
            foreach ($data as $sno => $item) {
                $sheet->setCellValue('A' . $row, $sno + 1);
                $sheet->setCellValue('B' . $row, $item['product_name']);
                $sheet->setCellValue('C' . $row, $item['quantity']);
                $sheet->setCellValue('D' . $row, 'Pcs'); // Assuming unit is Pcs, adjust if needed
                $sheet->setCellValue('E' . $row, $item['price']);
                $sheet->setCellValue('F' . $row, $item['category_name']);
                $sheet->setCellValue('G' . $row, $item['brand_name']);
                $row++;
            }

            // Auto-size columns
            foreach (range('A', 'G') as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $filename = 'stock_availability_' . date('YmdHis') . '.xlsx';

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
        } elseif ($format == 'pdf') {

            $html = '<h1 style="text-align: center;">Stock Availability Report</h1>';
            $html .= '<table border="1" width="100%" cellpadding="5" cellspacing="0">';
            $html .= '<thead><tr>';
            $html .= '<th style="font-weight: bold;">SNo.</th>';
            $html .= '<th style="font-weight: bold;">Item Name</th>';
            $html .= '<th style="font-weight: bold;">Current Stock</th>';
            $html .= '<th style="font-weight: bold;">Unit</th>';
            $html .= '<th style="font-weight: bold;">Valuation</th>';
            $html .= '<th style="font-weight: bold;">Category</th>';
            $html .= '<th style="font-weight: bold;">Brand</th>';
            $html .= '</tr></thead><tbody>';

            foreach ($data as $sno => $item) {
                $html .= '<tr>';
                $html .= '<td>' . ($sno + 1) . '</td>';
                $html .= '<td>' . $item['product_name'] . '</td>';
                $html .= '<td>' . $item['quantity'] . '</td>';
                $html .= '<td>Pcs</td>'; // Assuming unit is Pcs, adjust if needed
                $html .= '<td>' . $item['price'] . '</td>';
                $html .= '<td>' . $item['category_name'] . '</td>';
                $html .= '<td>' . $item['brand_name'] . '</td>';
                $html .= '</tr>';
            }
            $html .= '</tbody></table>';

            $this->pdf->loadHtml($html);
            $this->pdf->setPaper('A4', 'portrait');
            $this->pdf->render();
            $this->pdf->stream('stock_availability_' . date('YmdHis') . '.pdf', array('Attachment' => 0));
        }
    }

    public function export_fast_moving_items($format)
    {
        $data = $this->InventoryModel->get_fast_moving_items();

        if ($format == 'xlsx') {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set headers
            $headers = ['SNo.', 'Item Name', 'Total Sold'];
            $sheet->fromArray($headers, NULL, 'A1');

            // Make headers bold
            $sheet->getStyle('A1:C1')->getFont()->setBold(true);

            // Populate data
            $row = 2;
            foreach ($data as $sno => $item) {
                $sheet->setCellValue('A' . $row, $sno + 1);
                $sheet->setCellValue('B' . $row, $item['product_name']);
                $sheet->setCellValue('C' . $row, $item['total_sold']);
                $row++;
            }

            // Auto-size columns
            foreach (range('A', 'C') as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $filename = 'fast_moving_items_' . date('YmdHis') . '.xlsx';

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
        } elseif ($format == 'pdf') {
            $html = '<h1 style="text-align: center;">Fast Moving Items Report</h1>';
            $html .= '<table border="1" width="100%" cellpadding="5" cellspacing="0">';
            $html .= '<thead><tr>';
            $html .= '<th style="font-weight: bold;">SNo.</th>';
            $html .= '<th style="font-weight: bold;">Item Name</th>';
            $html .= '<th style="font-weight: bold;">Total Sold</th>';
            $html .= '</tr></thead><tbody>';

            foreach ($data as $sno => $item) {
                $html .= '<tr>';
                $html .= '<td>' . ($sno + 1) . '</td>';
                $html .= '<td>' . $item['product_name'] . '</td>';
                $html .= '<td>' . $item['total_sold'] . '</td>';
                $html .= '</tr>';
            }
            $html .= '</tbody></table>';

            $this->pdf->loadHtml($html);
            $this->pdf->setPaper('A4', 'portrait');
            $this->pdf->render();
            $this->pdf->stream('fast_moving_items_' . date('YmdHis') . '.pdf', array('Attachment' => 0));
        }
    }

    public function export_items_not_moving($format)
    {
        $data = $this->InventoryModel->get_items_not_moving();

        if ($format == 'xlsx') {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set headers
            $headers = ['SNo.', 'Item Name', 'Current Stock'];
            $sheet->fromArray($headers, NULL, 'A1');
            
            // Make headers bold
            $sheet->getStyle('A1:C1')->getFont()->setBold(true);

            // Populate data
            $row = 2;
            foreach ($data as $sno => $item) {
                $sheet->setCellValue('A' . $row, $sno + 1);
                $sheet->setCellValue('B' . $row, $item['product_name']);
                $sheet->setCellValue('C' . $row, $item['quantity']);
                $row++;
            }

            // Auto-size columns
            foreach (range('A', 'C') as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $filename = 'items_not_moving_' . date('YmdHis') . '.xlsx';

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
        } elseif ($format == 'pdf') {
            $html = '<h1 style="text-align: center;">Items Not Moving Report</h1>';
            $html .= '<table border="1" width="100%" cellpadding="5" cellspacing="0">';
            $html .= '<thead><tr>';
            $html .= '<th style="font-weight: bold;">SNo.</th>';
            $html .= '<th style="font-weight: bold;">Item Name</th>';
            $html .= '<th style="font-weight: bold;">Current Stock</th>';
            $html .= '</tr></thead><tbody>';

            foreach ($data as $sno => $item) {
                $html .= '<tr>';
                $html .= '<td>' . ($sno + 1) . '</td>';
                $html .= '<td>' . $item['product_name'] . '</td>';
                $html .= '<td>' . $item['quantity'] . '</td>';
                $html .= '</tr>';
            }
            $html .= '</tbody></table>';

            $this->pdf->loadHtml($html);
            $this->pdf->setPaper('A4', 'portrait');
            $this->pdf->render();
            $this->pdf->stream('items_not_moving_' . date('YmdHis') . '.pdf', array('Attachment' => 0));
        }
    }
}
