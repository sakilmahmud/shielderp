<?php

class Clients extends MY_Controller
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
        $data['activePage'] = 'clients';
        $data['clients'] = $this->UserModel->get_all_clients();


        $this->render_admin('admin/clients/index', $data);
    }

    public function add()
    {
        $data['activePage'] = 'clients';

        //$this->form_validation->set_rules('username', 'Username', 'required');
        //$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        // Add more rules as needed
        $this->form_validation->set_rules('mobile', 'Mobile', 'required|is_unique[users.mobile]');
        $this->form_validation->set_rules('full_name', 'Full Name', 'required');

        if ($this->form_validation->run() === FALSE) {
            $data['isUpdate'] = false;


            $this->render_admin('admin/clients/add', $data);
        } else {
            $userData = array(
                'user_role' => 4, // client role
                //'username' => $this->input->post('username'),
                //'email' => $this->input->post('email'),
                'mobile' => $this->input->post('mobile'),
                'password' => password_hash('123456', PASSWORD_BCRYPT),
                'full_name' => $this->input->post('full_name'),
                'address' => $this->input->post('address'),
                'added_by' => $this->session->userdata('user_id')
            );

            $this->UserModel->insert_user($userData);

            // Set flash message for success
            $this->session->set_flashdata('message', 'New client added successfully!');
            redirect('admin/clients');
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
                'user_role' => 4, // client role
                'mobile' => $this->input->post('mobile'),
                'password' => password_hash('123456', PASSWORD_BCRYPT),
                'full_name' => $this->input->post('full_name'),
                'address' => $this->input->post('address'),
                'added_by' => $this->session->userdata('user_id'),
                'created_at' => date('Y-m-d H:i:s')
            );

            $clientId = $this->UserModel->insert_user($userData);

            if ($clientId) {
                echo json_encode(['success' => true, 'client' => ['id' => $clientId, 'full_name' => $this->input->post('full_name')]]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to add client.']);
            }
        }
    }


    public function edit($id)
    {
        $data['activePage'] = 'clients';

        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('mobile', 'Mobile', 'required');
        // Add more rules as needed

        if ($this->form_validation->run() === FALSE) {
            $data['isUpdate'] = true;
            $data['client'] = $this->UserModel->get_user($id);


            $this->render_admin('admin/clients/edit', $data);
        } else {
            $userData = array(
                'username' => $this->input->post('username'),
                'email' => $this->input->post('email'),
                'mobile' => $this->input->post('mobile'),
                'full_name' => $this->input->post('full_name'),
                'address' => $this->input->post('address'),
                'updated_at' => date('Y-m-d H:i:s'),
                'status' => $this->input->post('status')
            );

            if (!empty($this->input->post('password'))) {
                $userData['password'] = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
            }

            $this->UserModel->update_user($id, $userData);

            // Set flash message for success
            $this->session->set_flashdata('message', 'Client updated successfully!');
            redirect('admin/clients');
        }
    }

    public function delete($id)
    {
        $this->UserModel->delete_user($id);
        redirect('admin/clients');
    }
}
