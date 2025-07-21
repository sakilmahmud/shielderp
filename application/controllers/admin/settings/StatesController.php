<?php
defined('BASEPATH') or exit('No direct script access allowed');

class StatesController extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('StateModel');
        $this->load->library('form_validation');

        if (!$this->session->userdata('username')) {
            redirect('login');
        }
    }

    public function index()
    {
        $data['activePage'] = 'states';
        $data['states'] = $this->StateModel->get_all_states();
        $this->render_admin('admin/settings/states/index', $data);
    }

    public function add()
    {
        $data['activePage'] = 'states';

        $this->form_validation->set_rules('state_name', 'State Name', 'required');
        $this->form_validation->set_rules('state_code', 'State Code', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->render_admin('admin/settings/states/add', $data);
        } else {
            $data = [
                'state_name' => $this->input->post('state_name'),
                'state_code' => $this->input->post('state_code'),
            ];

            $this->StateModel->insert_state($data);
            $this->session->set_flashdata('message', 'State added successfully');
            redirect('admin/settings/states');
        }
    }

    public function edit($id)
    {
        $data['activePage'] = 'states';
        $data['state'] = $this->StateModel->get_state($id);

        $this->form_validation->set_rules('state_name', 'State Name', 'required');
        $this->form_validation->set_rules('state_code', 'State Code', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->render_admin('admin/settings/states/edit', $data);
        } else {
            $data = [
                'state_name' => $this->input->post('state_name'),
                'state_code' => $this->input->post('state_code'),
            ];

            $this->StateModel->update_state($id, $data);
            $this->session->set_flashdata('message', 'State updated successfully');
            redirect('admin/settings/states');
        }
    }

    public function delete($id)
    {
        $this->StateModel->delete_state($id);
        $this->session->set_flashdata('message', 'State deleted successfully');
        redirect('admin/settings/states');
    }
}