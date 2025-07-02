<?php

class Doers extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('UserModel');
        $this->load->library('form_validation');

        if (!$this->session->userdata('username')) {
            redirect('login');
        }
    }

    public function index()
    {
        $data['activePage'] = 'doers';
        $data['doers'] = $this->UserModel->get_all_doers();


        $this->render_admin('admin/doers/index', $data);
    }

    public function add()
    {
        $data['activePage'] = 'doers';

        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('mobile', 'Mobile', 'required');
        // Add more rules as needed

        if ($this->form_validation->run() === FALSE) {
            $data['isUpdate'] = false;


            $this->render_admin('admin/doers/add', $data);
        } else {
            $userData = array(
                'user_role' => 3, // doer role
                'username' => $this->input->post('username'),
                'email' => $this->input->post('email'),
                'mobile' => $this->input->post('mobile'),
                'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
                'full_name' => $this->input->post('full_name'),
                'added_by' => $this->session->userdata('user_id'),
                'created_at' => date('Y-m-d H:i:s'),
                'status' => $this->input->post('status')
            );

            $this->UserModel->insert_user($userData);
            redirect('admin/doers');
        }
    }
    public function add_ajax()
    {
        $this->form_validation->set_rules('mobile', 'Mobile', 'required|is_unique[users.mobile]');
        $this->form_validation->set_rules('full_name', 'Full Name', 'required');

        if ($this->form_validation->run() === FALSE) {
            echo json_encode(['success' => false, 'message' => validation_errors()]);
        } else {
            $userData = array(
                'user_role' => 3, // doer role
                'mobile' => $this->input->post('mobile'),
                'password' => password_hash('123456', PASSWORD_BCRYPT),
                'full_name' => $this->input->post('full_name'),
                'address' => $this->input->post('address'),
                'added_by' => $this->session->userdata('user_id'),
                'created_at' => date('Y-m-d H:i:s')
            );

            $doerId = $this->UserModel->insert_user($userData);

            if ($doerId) {
                echo json_encode(['success' => true, 'doer' => ['id' => $doerId, 'full_name' => $this->input->post('full_name')]]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to add doer.']);
            }
        }
    }


    public function edit($id)
    {
        $data['activePage'] = 'doers';

        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('mobile', 'Mobile', 'required');
        // Add more rules as needed

        if ($this->form_validation->run() === FALSE) {
            $data['isUpdate'] = true;
            $data['doer'] = $this->UserModel->get_user($id);


            $this->render_admin('admin/doers/add', $data);
        } else {
            $userData = array(
                'username' => $this->input->post('username'),
                'email' => $this->input->post('email'),
                'mobile' => $this->input->post('mobile'),
                'full_name' => $this->input->post('full_name'),
                'updated_at' => date('Y-m-d H:i:s'),
                'status' => $this->input->post('status')
            );

            if (!empty($this->input->post('password'))) {
                $userData['password'] = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
            }

            $this->UserModel->update_user($id, $userData);
            redirect('admin/doers');
        }
    }

    public function delete($id)
    {
        $this->UserModel->delete_user($id);
        redirect('admin/doers');
    }
}
