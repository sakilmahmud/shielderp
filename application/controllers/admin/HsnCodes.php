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
