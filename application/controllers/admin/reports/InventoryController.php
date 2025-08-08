<?php
defined('BASEPATH') or exit('No direct script access allowed');

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;

class InventoryController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('user_id')) {
            redirect('login');
        }
        $this->load->model('reports/InventoryModel', 'InventoryModel');
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
        $totalRecords = $this->InventoryModel->count_all_stock();
        $filteredRecords = $this->InventoryModel->count_filtered_stock($searchValue, $category_id, $brand_id);

        $response = [
            "draw" => intval($draw),
            "recordsTotal" => intval($totalRecords),
            "recordsFiltered" => intval($filteredRecords),
            "data" => $data
        ];

        echo json_encode($response);
    }

    public function export_stock_availability($format)
    {
        $searchValue = $this->input->get('search_value');
        $category_id = $this->input->get('category_id');
        $brand_id = $this->input->get('brand_id');

        $data = $this->InventoryModel->get_stock_availability_ajax(0, -1, $searchValue, $category_id, $brand_id);

        if ($format == 'xlsx') {
            $this->export_xlsx($data);
        } elseif ($format == 'pdf') {
            $this->export_pdf($data);
        }
    }

    private function export_xlsx($data)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Product Name');
        $sheet->setCellValue('B1', 'Quantity');
        $sheet->setCellValue('C1', 'Price');
        $sheet->setCellValue('D1', 'Valuation');
        $sheet->setCellValue('E1', 'Category');
        $sheet->setCellValue('F1', 'Brand');
        $sheet->setCellValue('G1', 'Unit');

        $row = 2;
        $total_valuation = 0;
        foreach ($data as $item) {
            $sheet->setCellValue('A' . $row, $item['product_name']);
            $sheet->setCellValue('B' . $row, $item['quantity']);
            $sheet->setCellValue('C' . $row, $item['price']);
            $sheet->setCellValue('D' . $row, $item['valuation']);
            $sheet->setCellValue('E' . $row, $item['category_name']);
            $sheet->setCellValue('F' . $row, $item['brand_name']);
            $sheet->setCellValue('G' . $row, $item['unit_name']);
            $total_valuation += $item['valuation'];
            $row++;
        }

        $sheet->setCellValue('C' . ($row + 1), 'Total Valuation:');
        $sheet->setCellValue('D' . ($row + 1), $total_valuation);

        $writer = new Xlsx($spreadsheet);
        $filename = 'stock_availability.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $writer->save('php://output');
    }

    private function export_pdf($data)
    {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);

        $html = '<h1>Stock Availability</h1>';
        $html .= '<table border="1" cellspacing="0" cellpadding="5">';
        $html .= '<thead><tr><th>Product Name</th><th>Quantity</th><th>Price</th><th>Valuation</th><th>Category</th><th>Brand</th><th>Unit</th></tr></thead>';
        $html .= '<tbody>';
        $total_valuation = 0;
        foreach ($data as $item) {
            $html .= '<tr>';
            $html .= '<td>' . $item['product_name'] . '</td>';
            $html .= '<td>' . $item['quantity'] . '</td>';
            $html .= '<td>' . $item['price'] . '</td>';
            $html .= '<td>' . $item['valuation'] . '</td>';
            $html .= '<td>' . $item['category_name'] . '</td>';
            $html .= '<td>' . $item['brand_name'] . '</td>';
            $html .= '<td>' . $item['unit_name'] . '</td>';
            $html .= '</tr>';
            $total_valuation += $item['valuation'];
        }
        $html .= '</tbody>';
        $html .= '<tfoot><tr><td colspan="3"></td><td>Total Valuation:</td><td colspan="3">' . $total_valuation . '</td></tr></tfoot>';
        $html .= '</table>';

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('stock_availability.pdf', ['Attachment' => 1]);
    }
}
