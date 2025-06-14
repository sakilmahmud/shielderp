<?php
class AdminUsers extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('UserModel');
        $this->load->model('TaskModel');
        $this->load->model('SettingsModel');
        $this->load->library('form_validation');
        $this->load->library('upload');
        $this->load->helper('custom_helper');

        $cookie_value = $this->input->cookie('remember_me');

        if ($cookie_value) {
            list($user_id, $cookie_hash) = explode(':', $cookie_value);
            $user = $this->UserModel->getUserById($user_id);
            if ($user) {
                $this->session->set_userdata('user_id', $user['id']);
                $this->session->set_userdata('username', $user['username']);
                $this->session->set_userdata('role', $user['user_role']);
            }
        }

        if (!$this->session->userdata('username')) {
            redirect('login');
        }
    }


    public function list()
    {
        // Method logic for admin user management
        $data['activePage'] = 'adminAccounts';

        // Get the list of admin users from the model
        $data['users'] = $this->UserModel->getUsers(2); // Assuming 2 is the role ID for admin users

        // Load the views

        $this->render_admin('admin/admin/list', $data);
    }

    public function addAdminUser()
    {
        // Method logic for adding a new admin user
        $data['activePage'] = 'adminAccounts';
        $data['isUpdate'] = 0;
        $data['error'] = ''; // Initialize the error message

        if ($this->session->userdata('role') != 1) :
            $data['error'] = "You don't have permission to do this action!";


            $this->render_admin('admin/admin/add', $data);

        endif;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Set form validation rules
            $this->form_validation->set_rules('username', 'Username', 'is_unique[users.username]');
            $this->form_validation->set_rules('full_name', 'Full Name', 'required');
            $this->form_validation->set_rules('mobile', 'Mobile Number', 'required|exact_length[10]|numeric|is_unique[users.mobile]');

            if ($this->form_validation->run() === FALSE) {
                // Validation failed or first load of the page, show the form

                $this->render_admin('admin/admin/add', $data);
            } else {
                $password = password_hash("123456", PASSWORD_DEFAULT);
                $username = $this->input->post('username');
                if ($username === "") {
                    $username = $this->input->post('mobile');
                }

                // Prepare the data for adding the admin user
                $data = array(
                    'user_role' => 2, // Assuming 2 is the role ID for admin users
                    'username' => $username,
                    'full_name' => $this->input->post('full_name'),
                    'mobile' => $this->input->post('mobile'),
                    'password' => $password
                );

                // Call the UserModel method to add the admin user
                $this->UserModel->insert_user($data);

                // Redirect back to the admin user management page
                redirect('admin/adminAccounts');
            }
        } else {

            $this->render_admin('admin/admin/add');
        }
    }

    public function updateAdminUser($user_id)
    {
        // Method logic for updating an admin user
        $data['activePage'] = 'adminAccounts';
        $data['isUpdate'] = 1;

        // Set validation rules
        //$this->form_validation->set_rules('username', 'Username', 'callback_unique_username['.$user_id.']');
        $this->form_validation->set_rules('full_name', 'Full Name', 'required|max_length[100]');
        //$this->form_validation->set_rules('mobile', 'Mobile Number', 'required|numeric|exact_length[10]|callback_unique_mobile['.$user_id.']');

        // Register the callback functions with the form validation library
        //$this->form_validation->set_message('unique_username', 'The {field} must contain a unique value.');
        //$this->form_validation->set_message('unique_mobile', 'The {field} must contain a unique value.');

        if ($this->form_validation->run() == FALSE) {
            // Form validation failed, show validation errors and load the view with admin user data

            // Get the admin user details from the database based on the user_id
            $userDetails = $this->UserModel->getUserById($user_id);

            // Pass the user data to the view
            $data['user'] = $userDetails;


            $this->render_admin('admin/admin/add', $data);
        } else {
            // Form validation passed, process the form submission and update the admin user

            // Prepare the data for update
            $data = array(
                'username' => $this->input->post('username'),
                'mobile' => $this->input->post('mobile'),
                'full_name' => $this->input->post('full_name')
            );

            // Call the UserModel method to update the admin user
            $this->UserModel->update_user($user_id, $data);

            // Redirect back to the admin user management page
            redirect("admin/adminAccounts/edit/$user_id");
        }
    }

    public function deleteAdminUser($user_id)
    {
        // Method logic for deleting an admin user

        // Delete the admin user from the database based on the user_id
        $this->UserModel->delete_user($user_id);

        // Redirect back to the admin user management page
        redirect('admin/adminAccounts');
    }
}
