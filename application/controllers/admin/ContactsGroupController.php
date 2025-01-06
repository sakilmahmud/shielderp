<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ContactsGroupController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ContactsGroupModel');
        $this->load->library('form_validation');

        if (!$this->session->userdata('user_id')) {
            redirect('login');
        }
    }

    // List all contact groups
    public function index()
    {
        $data['activePage'] = 'contacts_group';
        $data['groups'] = $this->ContactsGroupModel->getAll();

        $this->load->view('admin/header', $data);
        $this->load->view('admin/contacts/group/index', $data);
        $this->load->view('admin/footer');
    }

    // Add a new contact group
    public function add()
    {
        $data['activePage'] = 'contacts_group';

        $this->form_validation->set_rules('title', 'Group Title', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('admin/header', $data);
            $this->load->view('admin/contacts/group/add', $data);
            $this->load->view('admin/footer');
        } else {
            $postData = [
                'title' => $this->input->post('title'),
                'status' => $this->input->post('status') ? 1 : 0,
            ];

            $this->ContactsGroupModel->save($postData);
            $this->session->set_flashdata('message', 'Contact group added successfully.');
            redirect('admin/contacts/group');
        }
    }
}
