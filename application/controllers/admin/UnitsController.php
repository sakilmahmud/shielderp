<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UnitsController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('UnitModel');
        $this->load->library('form_validation');

        if (!$this->session->userdata('username')) {
            redirect('login');
        }
    }

    public function index()
    {
        $data['activePage'] = 'units';
        $data['units'] = $this->UnitModel->get_all_units();

        $this->render_admin('admin/units/index', $data);
    }

    public function add()
    {
        $data['activePage'] = 'units';
        $this->form_validation->set_rules('name', 'Unit Name', 'required');

        if ($this->form_validation->run() === FALSE) {
            $data['isUpdate'] = false;

            $this->render_admin('admin/units/add', $data);
        } else {
            $unitData = array(
                'name' => $this->input->post('name'),
                'symbol' => $this->input->post('symbol')
            );

            $this->UnitModel->insert_unit($unitData);
            $this->session->set_flashdata('message', 'Unit added successfully.');
            redirect('admin/units');
        }
    }

    public function edit($id)
    {
        $data['activePage'] = 'units';
        $this->form_validation->set_rules('name', 'Unit Name', 'required');

        if ($this->form_validation->run() === FALSE) {
            $data['isUpdate'] = true;
            $data['unit'] = $this->UnitModel->get_unit($id);

            $this->render_admin('admin/units/add', $data);
        } else {
            $unitData = array(
                'name' => $this->input->post('name'),
                'symbol' => $this->input->post('symbol')
            );

            $this->UnitModel->update_unit($id, $unitData);
            $this->session->set_flashdata('message', 'Unit updated successfully.');
            redirect('admin/units');
        }
    }

    public function delete($id)
    {
        $this->UnitModel->delete_unit($id);
        $this->session->set_flashdata('message', 'Unit deleted successfully.');
        redirect('admin/units');
    }
}
