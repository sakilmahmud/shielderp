<?php
defined('BASEPATH') or exit('No direct script access allowed');

class HsnCodes extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('HsnCodeModel');
        $this->load->library('form_validation');

        if (!$this->session->userdata('username')) {
            redirect('login');
        }
    }

    public function index()
    {
        $data['activePage'] = 'hsn_codes';
        $this->render_admin('admin/hsn_codes/index', $data);
    }

    public function ajax_list()
    {
        $list = $this->HsnCodeModel->get_datatables();
        $data = [];
        $no = $this->input->post('start');

        foreach ($list as $row) {
            $no++;
            $data[] = [
                $no,
                $row->hsn_code,
                $row->description,
                $row->gst_rate . '%',
                '<a href="' . base_url("admin/hsn-codes/edit/{$row->id}") . '" class="btn btn-sm btn-warning">Edit</a>
                 <a href="' . base_url("admin/hsn-codes/delete/{$row->id}") . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')">Delete</a>'
            ];
        }

        echo json_encode([
            "draw" => intval($this->input->post('draw')),
            "recordsTotal" => $this->HsnCodeModel->count_all(),
            "recordsFiltered" => $this->HsnCodeModel->count_filtered(),
            "data" => $data
        ]);
    }

    public function create()
    {
        if ($_POST) {
            $this->form_validation->set_rules('hsn_code', 'HSN Code', 'required|is_unique[hsn_codes.hsn_code]');
            $this->form_validation->set_rules('gst_rate', 'GST Rate', 'required|numeric');

            if ($this->form_validation->run()) {
                $data = $this->input->post();
                $this->HsnCodeModel->insert($data);
                redirect('admin/hsn-codes');
            }
        }

        $data['activePage'] = 'hsn_codes';
        $this->render_admin('admin/hsn_codes/add', $data);
    }

    public function edit($id)
    {
        $hsn = $this->HsnCodeModel->get($id);
        if (!$hsn) show_404();

        if ($_POST) {
            $this->form_validation->set_rules('hsn_code', 'HSN Code', 'required');
            $this->form_validation->set_rules('gst_rate', 'GST Rate', 'required|numeric');

            if ($this->form_validation->run()) {
                $data = $this->input->post();
                $this->HsnCodeModel->update($id, $data);
                redirect('admin/hsn-codes');
            }
        }

        $data['hsn'] = $hsn;
        $data['activePage'] = 'hsn_codes';
        $this->render_admin('admin/hsn_codes/add', $data);
    }

    public function delete($id)
    {
        $this->HsnCodeModel->delete($id);
        redirect('admin/hsn-codes');
    }

    public function export_import()
    {
        $data['activePage'] = 'hsn_codes_export_import';
        $this->render_admin('admin/hsn_codes/export_import', $data);
    }

    public function export_csv()
    {
        $this->load->helper('download');
        $this->load->dbutil();

        $hsn_codes = $this->HsnCodeModel->get_all();

        $delimiter = ",";
        $newline = "\r\n";
        $filename = "hsn_codes_export_" . date('Ymd_His') . ".csv";

        $csv_data = "hsn_code,description,gst_rate\n";
        foreach ($hsn_codes as $row) {
            $csv_data .= '"' . $row['hsn_code'] . '","' . $row['description'] . '","' . $row['gst_rate'] . '"' . $newline;
        }

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
            redirect('admin/hsn-codes-export-import');
        } else {
            $file_data = $this->upload->data();
            $file_path = $file_data['full_path'];

            $csv_array = array_map('str_getcsv', file($file_path));
            $header = array_map('trim', $csv_array[0]);

            if ($header !== ['hsn_code', 'description', 'gst_rate']) {
                $this->session->set_flashdata('error', 'Invalid CSV format. Required headers: hsn_code, description, gst_rate');
                redirect('admin/hsn-codes-export-import');
            }

            unset($csv_array[0]); // Remove header

            $insert_data = [];
            $skipped = 0;
            $inserted = 0;

            foreach ($csv_array as $row) {
                if (count($row) < 3) continue;

                $hsn_code = trim($row[0]);
                $description = trim($row[1]);
                $gst_rate = trim($row[2]);

                // Check for duplicate hsn_code
                $this->db->where('hsn_code', $hsn_code);
                $existing = $this->db->get('hsn_codes')->row();

                if ($existing) {
                    $skipped++;
                    continue;
                }

                $insert_data[] = [
                    'hsn_code'    => $hsn_code,
                    'description' => $description,
                    'gst_rate'    => $gst_rate,
                    'created_at'  => date('Y-m-d H:i:s')
                ];
                $inserted++;
            }

            if (!empty($insert_data)) {
                $this->db->insert_batch('hsn_codes', $insert_data);
            }

            $this->session->set_flashdata('message', "Import completed. Inserted: <strong>$inserted</strong>, Skipped (duplicates): <strong>$skipped</strong>");
            redirect('admin/hsn-codes-export-import');
        }
    }

    public function ajax_add()
    {
        $data = [
            'hsn_code' => $this->input->post('hsn_code'),
            'gst_rate' => $this->input->post('gst_rate'),
            'description' => $this->input->post('description'),
        ];

        if ($this->db->insert('hsn_codes', $data)) {
            $data['id'] = $this->db->insert_id();
            echo json_encode(['success' => true, 'data' => $data]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Insert failed']);
        }
    }
}
