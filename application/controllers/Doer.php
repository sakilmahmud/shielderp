<?php
class Doer extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('UserModel');
        // Load form validation library
        $this->load->library('form_validation');

        // Check if the "remember_me" cookie exists
        $cookie_value = $this->input->cookie('remember_me');

        if ($cookie_value) {
            list($user_id, $cookie_hash) = explode(':', $cookie_value);
            // Validate the cookie hash here (e.g., against the user's stored hash)

            // Log in the user automatically using the user_id
            $user = $this->UserModel->getUserById($user_id);
            if ($user) {
                $this->session->set_userdata('user_id', $user['id']);
                $this->session->set_userdata('username', $user['username']);
                $this->session->set_userdata('role', $user['user_role']);
            }
        }

        // Check if the user is logged in
        if (!$this->session->userdata('username')) {
            redirect('login'); // Redirect to the login page if not logged in
        }

        //print_r($this->session->userdata); die;

        $this->load->model('SettingsModel');

        $this->load->helper('custom_helper');

        $this->load->model('TaskModel');
        // Load the upload library for handling file uploads
        $this->load->library('upload'); // Make sure 'upload' is the correct name of the library class
    }


    public function index()
    {
        if ($this->session->userdata('username')) {
            redirect('admin/dashboard');
        }
    }

    public function dashboard()
    {
        // Method logic for the dashboard
        $data['activePage'] = 'dashboard';

        $user_id = $this->session->userdata('user_id');
        $role = $this->session->userdata('role');

        $data['todaysTasks'] = $this->TaskModel->getTodaysTasks($user_id, $role);
        $data['weeksTasks'] = $this->TaskModel->getWeeksTasks($user_id, $role);
        $data['overdueTasks'] = $this->TaskModel->getOverdueTasks($user_id, $role);

        // Load the views
        $this->load->view('admin/header', $data);
        $this->load->view('admin/doer_dashboard/index', $data);
        $this->load->view('admin/footer');
    }

    public function tasks()
    {
        $data['activePage'] = 'tasks';


        $user_id = $this->session->userdata('user_id');

        $data['tasks'] = $this->TaskModel->get_all_tasks_doer($user_id);

        $this->load->view('admin/header', $data);
        $this->load->view('admin/doer_dashboard/tasks', $data);
        $this->load->view('admin/footer');
    }

    public function markTaskAsDone()
    {
        $task_id = $this->input->post('task_id');
        $note = $this->input->post('note');

        if ($task_id && $note) {
            $updateData = array(
                'status' => 1, // Assuming 1 is the status for 'Done'
                'done_time' => date('Y-m-d H:i:s'),
                'note' => $note
            );

            $this->TaskModel->updateTask($task_id, $updateData);

            // Set flash message for success
            $this->session->set_flashdata('message', 'Task marked as done successfully!');
        } else {
            // Set flash message for error
            $this->session->set_flashdata('error', 'Failed to mark the task as done.');
        }

        // Use AJAX to handle response, so no need to redirect
    }


    public function updateProfile()
    {
        // Logic to handle profile update form submission
        // Perform form validation and update the patient's profile

        $data['activePage'] = 'updateProfile';
        $patientId = $this->session->userdata('user_id');
        // Get the patient details from the model
        $data['patient'] = $this->PatientModel->getPatientById($patientId);

        // Form validation rules
        $this->form_validation->set_rules('full_name', 'Full Name', 'required');

        if ($this->form_validation->run() == FALSE) {
            // Form validation failed, reload the view with validation errors
            $this->load->view('patients/header', $data);
            $this->load->view('patients/update_profile', $data);
            $this->load->view('patients/footer');
        } else {
            //print_r($_POST); die;
            // Form validation passed, update the patient's profile
            $fullName = $this->input->post('full_name');
            $email = $this->input->post('email');

            // Prepare the data to be updated
            $patientData = array(
                'full_name' => $fullName,
                'email' => $email
                // Add other fields as necessary
            );

            // Update the patient's profile
            $updateResult = $this->PatientModel->updatePatient($patientId, $patientData);

            if ($updateResult) {
                // Profile update successful
                // Redirect to the profile page or any other appropriate page
                redirect('patient/updateProfile');
            } else {
                // Profile update failed
                // Handle the error accordingly (e.g., show an error message or redirect to an error page)
            }
        }
    }
}
