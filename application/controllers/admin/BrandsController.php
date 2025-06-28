<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BrandsController extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('BrandModel');
        $this->load->library('form_validation');

        if (!$this->session->userdata('user_id')) {
            redirect('login');
        }
    }

    public function index()
    {
        $data['activePage'] = 'brands';
        $data['brands'] = $this->BrandModel->get_all_brands();

        $this->render_admin('admin/brands/index', $data);
    }

    public function add()
    {
        $data['activePage'] = 'brands';

        $this->form_validation->set_rules('brand_name', 'Brand Name', 'required');

        if ($this->form_validation->run() === FALSE) {
            $data['isUpdate'] = false;


            $this->render_admin('admin/brands/add', $data);
        } else {
            $brandData = array(
                'brand_name' => $this->input->post('brand_name'),
                'brand_descriptions' => $this->input->post('brand_descriptions'),
                'status' => 1
            );

            $this->BrandModel->insert_brand($brandData);

            // Set flash message and redirect
            $this->session->set_flashdata('message', 'Brand added successfully');
            redirect('admin/brands');
        }
    }

    public function edit($id)
    {
        $data['activePage'] = 'brands';

        $this->form_validation->set_rules('brand_name', 'Brand Name', 'required');

        if ($this->form_validation->run() === FALSE) {
            $data['isUpdate'] = true;
            $data['brand'] = $this->BrandModel->get_brand($id);


            $this->render_admin('admin/brands/add', $data);
        } else {
            $brandData = array(
                'brand_name' => $this->input->post('brand_name'),
                'brand_descriptions' => $this->input->post('brand_descriptions'),
                'updated_at' => date('Y-m-d H:i:s')
            );

            $this->BrandModel->update_brand($id, $brandData);
            // Set flash message and redirect
            $this->session->set_flashdata('message', 'Brand updated successfully');
            redirect('admin/brands');
        }
    }

    public function addAjax()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('brand_name', 'Brand Name', 'required|is_unique[brands.brand_name]');

        if ($this->form_validation->run() === FALSE) {
            echo json_encode(['success' => false, 'errors' => validation_errors()]);
        } else {
            $data = [
                'brand_name' => $this->input->post('brand_name'),
            ];

            $brand_id = $this->BrandModel->insert_brand($data);

            echo json_encode([
                'success' => true,
                'brand' => [
                    'id' => $brand_id,
                    'name' => $this->input->post('brand_name')
                ]
            ]);
        }
    }

    public function delete($id)
    {
        $this->BrandModel->delete_brand($id);
        // Set flash message and redirect
        $this->session->set_flashdata('message', 'Brand delete successfully');
        redirect('admin/brands');
    }

    public function export_import()
    {
        $data['activePage'] = 'brands-export-import';
        $this->render_admin('admin/brands/export_import', $data);
    }

    public function export_csv()
    {
        $this->load->helper('download');

        // Get data using model
        $brands = $this->BrandModel->get_all_brands();

        $delimiter = ",";
        $newline = "\r\n";
        $filename = "brands_export_" . date('Ymd_His') . ".csv";

        // Build CSV header
        $csv_data = "brand_name,brand_descriptions\n";

        // Loop through data
        foreach ($brands as $row) {
            $csv_data .= '"' . $row['brand_name'] . '","' . $row['brand_descriptions'] . '"' . $newline;
        }

        // Trigger file download
        force_download($filename, $csv_data);
    }

    public function import_csv()
    {
        $this->load->library('upload');

        $config['upload_path'] = './uploads/csv/';
        $config['allowed_types'] = 'csv';
        $config['max_size'] = 2048;
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('csv_file')) {
            $this->session->set_flashdata('error', $this->upload->display_errors());
            redirect('admin/brands-export-import');
        } else {
            $file_data = $this->upload->data();
            $file_path = $file_data['full_path'];

            $csv_array = array_map('str_getcsv', file($file_path));
            $header = array_map('trim', $csv_array[0]);

            if ($header !== ['brand_name', 'brand_descriptions']) {
                $this->session->set_flashdata('error', 'Invalid CSV format. Required headers: brand_name, brand_descriptions');
                redirect('admin/brands-export-import');
            }

            unset($csv_array[0]);

            $insert_data = [];
            $skipped = 0;
            $inserted = 0;

            foreach ($csv_array as $row) {
                if (count($row) < 1) continue;

                $brand_name = trim($row[0]);
                $brand_descriptions = isset($row[1]) && trim($row[1]) !== '' ? trim($row[1]) : $brand_name;

                $this->db->where('brand_name', $brand_name);
                $exists = $this->db->get('brands')->num_rows();

                if ($exists > 0) {
                    $skipped++;
                    continue;
                }

                $insert_data[] = [
                    'brand_name' => $brand_name,
                    'brand_descriptions' => $brand_descriptions,
                    'created_at' => date('Y-m-d H:i:s'),
                    'status' => 1
                ];
                $inserted++;
            }

            if (!empty($insert_data)) {
                $this->BrandModel->bulk_insert($insert_data);
            }

            $this->session->set_flashdata('message', "CSV Import Completed.<br>✅ Inserted: <strong>$inserted</strong><br>⚠️ Skipped (duplicate): <strong>$skipped</strong>");
            redirect('admin/brands-export-import');
        }
    }
}
