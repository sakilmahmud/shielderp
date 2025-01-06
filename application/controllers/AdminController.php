<?php

class AdminController extends CI_Controller
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
        $this->load->view('admin/dashboard', $data);
        $this->load->view('admin/footer');
    }

    public function taskDetails()
    {
        $task_id = $this->input->post('task_id');

        $this->db->select('T.id, T.title, T.description, TC.cat_name, T.start_date, T.due_date, T.status, 
                       C.full_name as client_name, 
                       D.full_name as doer_name');
        $this->db->from('tasks AS T');
        $this->db->join('task_categories as TC', 'T.category_id = TC.id', 'left');
        $this->db->join('users as C', 'T.client_id = C.id', 'left');
        $this->db->join('users as D', 'T.doer_id = D.id', 'left');
        $this->db->where('T.id', $task_id);

        $query = $this->db->get();
        $task = $query->row();

        if ($task) {
            $new_task_array = array(
                'title' => $task->title,
                'description' => $task->description,
                'cat_name' => $task->cat_name,
                'start_date' => date("h:i A jS F, Y", strtotime($task->start_date)),
                'due_date' =>  date("h:i A jS F, Y", strtotime($task->due_date)),
                'status' => ($task->status == 1) ? "Completed" : "Pending",
                'client_name' => $task->client_name,
                'doer_name' => $task->doer_name,
            );
            echo json_encode($new_task_array);
        } else {
            echo json_encode(['error' => 'Task not found']);
        }
    }


    public function whatsapp()
    {
        // Method logic for general settings
        $data['activePage'] = 'whatsapp';
        $data['error'] = ''; // Initialize the error message

        $this->load->model('ContactsGroupModel');
        $this->load->model('PostModel');

        $data['groups'] = $this->ContactsGroupModel->getAll();
        // Load the model to get all posts
        $data['posts'] = $this->PostModel->getAllPosts();

        $this->load->view('admin/header', $data);
        $this->load->view('admin/whatsapp', $data);
        $this->load->view('admin/footer');
    }

    public function whatsappPost()
    {
        /* echo "<pre>";
        print_r($_POST);
        die; */
        $source_contacts = $this->input->post('source_contacts');
        $sender_number = $this->input->post('sender_number');
        $message = $this->input->post('message');
        $post_id = $this->input->post('posts_id'); // Get the selected post ID

        $file_uploaded = false; // Default to no file uploaded
        $file_path = '';
        $file_type = '';
        $media_type = '';

        // If a post is selected, get the media details from the post
        if (!empty($post_id)) {
            $post = $this->db->get_where('posts', ['id' => $post_id])->row_array();

            if ($post) {
                $message = $post['post_content'];
                $file_path = $post['post_media_url'];
                $file_path = base_url($post['post_media_url']);
                $file_type = $post['media_type'];
            }
        } else {
            // Check if a file has been uploaded
            if (isset($_FILES['attached_file']) && !empty($_FILES['attached_file']['name'])) {
                // Ensure the upload directory exists, if not, create it
                $upload_path = 'assets/uploads/whatsapp_files/';
                if (!is_dir($upload_path)) {
                    mkdir($upload_path, 0755, true);
                }

                $config['upload_path'] = $upload_path; // Set your upload directory
                $config['allowed_types'] = 'jpg|png|gif|pdf|mp3|mp4'; // Allowed file types
                $config['max_size'] = 2048; // Maximum file size in KB (2MB)
                $config['file_name'] = time() . '_' . $_FILES['attached_file']['name']; // Rename file with timestamp

                $this->upload->initialize($config);

                // Attempt to upload the file
                if ($this->upload->do_upload('attached_file')) {
                    // Upload success, get the uploaded file data
                    $file_data = $this->upload->data();
                    $file_path = base_url('assets/uploads/whatsapp_files/' . $file_data['file_name']);
                    $file_type = $file_data['file_type'];
                    $file_uploaded = true;

                    // Determine the media type based on the file type

                } else {
                    // Handle upload error, if necessary
                    $upload_error = $this->upload->display_errors();
                    $this->session->set_flashdata('error_message', 'File upload failed: ' . $upload_error);
                    redirect('admin/wa');
                    return;
                }
            }
        }

        $message_type = $file_uploaded || !empty($file_path) ? "file" : "text"; // Determine message type
        $receiver_numbers = !empty($source_contacts) ? $source_contacts : explode(",", $sender_number);

        if (!empty($receiver_numbers)) {
            foreach ($receiver_numbers as $receiver_number) {
                if ($message_type === "file") {
                    $media_type = ge_media_type($file_type);
                    // Send message with the attached file
                    $response = sendTextMsg($receiver_number, $message, $file_path, $media_type);
                } else {
                    // Send message without any attachment
                    $response = sendTextMsg($receiver_number, $message);
                }

                $decrypt_response = json_decode($response);
                $status = $decrypt_response->status;
                $res_msg = $decrypt_response->msg;
                $res_errors = isset($decrypt_response->errors) ? json_encode($decrypt_response->errors) : null;

                // Prepare data for database insertion
                $data = [
                    'receiver_number' => $receiver_number,
                    'message' => $message,
                    'message_type' => $message_type,
                    'file_path' => $file_uploaded || !empty($file_path) ? $file_path : null,
                    'file_type' => $file_uploaded || !empty($file_type) ? $file_type : null,
                    'msg_status' => $status,
                    'response_msg' => $res_msg,
                    'error_log' => $res_errors,
                    'post_id' => !empty($post_id) ? $post_id : null, // Save post ID if used
                    'created_at' => date('Y-m-d H:i:s')
                ];

                // Insert the message details into the database
                $this->db->insert('whatsapp_message', $data);
            }
        }

        // Set flash message and redirect
        $this->session->set_flashdata('message', 'Message sent successfully!');
        redirect('admin/wa');
    }


    public function whatsappLog()
    {
        $data['activePage'] = 'whatsapp';
        // Fetch the logs from the database with a join on the posts table to get the post title
        $this->db->select('whatsapp_message.*, posts.post_title');
        $this->db->from('whatsapp_message');
        $this->db->join('posts', 'whatsapp_message.post_id = posts.id', 'left');
        $this->db->order_by('whatsapp_message.created_at', 'DESC');
        $data['logs'] = $this->db->get()->result_array();

        // Load the view and pass the logs
        $this->load->view('admin/header', $data);
        $this->load->view('admin/whatsapp_log', $data);
        $this->load->view('admin/footer');
    }





    public function getContactsBySource()
    {
        $source = $this->input->post('source');

        $this->load->model('ContactsModel');

        $contacts = $this->ContactsModel->getContactsByGroupId($source);

        /* if ($source == 1) {
            $this->db->select('id, full_name, mobile');
            $this->db->from('contacts_zenith');
            $this->db->where('user_role', 3); // Add condition for user_role = 3
            $query = $this->db->get();
        } elseif ($source == 2) {
            $this->db->select('id, full_name, mobile');
            $this->db->from('contacts_ayan');
            $this->db->where('user_role', 3); // Add condition for user_role = 3
            $query = $this->db->get();
        }

        $contacts = $query->result_array(); */
        echo json_encode($contacts);
    }



    public function generalSettings()
    {
        // Method logic for general settings
        $data['activePage'] = 'settings';
        $data['error'] = ''; // Initialize the error message

        $data['settings'] = $this->SettingsModel->getSettings();

        $this->load->view('admin/header', $data);
        $this->load->view('admin/general_settings', $data);
        $this->load->view('admin/footer');
    }

    public function updateSettings()
    {
        // Process the form submission and update the settings
        $settings = $this->input->post();
        unset($settings['submit']); // Remove the submit button from the settings array

        // Handle file uploads for frontend_logo and admin_logo
        $config['upload_path'] = './assets/uploads/'; // Specify the upload directory path
        $config['allowed_types'] = 'gif|jpg|png'; // Allowed file types
        $config['max_size'] = 2048; // Maximum file size in KB (2MB)

        // Frontend Logo
        if (!empty($_FILES['frontend_logo']['name'])) {
            // Remove the previous frontend logo if exists
            $existing_frontend_logo = $settings['frontend_logo'];
            if (file_exists($existing_frontend_logo)) {
                unlink($existing_frontend_logo);
            }

            $config['file_name'] = 'frontend_logo'; // Set a custom name for the uploaded file
            $this->upload->initialize($config);
            if ($this->upload->do_upload('frontend_logo')) {
                $data = $this->upload->data();
                $settings['frontend_logo'] = 'assets/uploads/' . $data['file_name'];
            }
        }

        // Admin Logo
        if (!empty($_FILES['admin_logo']['name'])) {
            // Remove the previous admin logo if exists
            $existing_admin_logo = $settings['admin_logo'];
            if (file_exists($existing_admin_logo)) {
                unlink($existing_admin_logo);
            }

            $config['file_name'] = 'admin_logo'; // Set a custom name for the uploaded file
            $this->upload->initialize($config);
            if ($this->upload->do_upload('admin_logo')) {
                $data = $this->upload->data();
                $settings['admin_logo'] = 'assets/uploads/' . $data['file_name'];
            }
        }

        // Update the settings in the database
        $this->SettingsModel->updateSettings($settings);

        // Redirect back to the settings page with a success message
        $this->session->set_flashdata('success', 'Settings updated successfully.');
        redirect('admin/settings');
    }



    public function companyDetails()
    {
        $data['activePage'] = 'company_details';
        $data['settings'] = $this->SettingsModel->getSettings();
        $this->load->view('admin/header', $data);
        $this->load->view('admin/company_details', $data);
        $this->load->view('admin/footer');
    }

    public function bankDetails()
    {
        $data['activePage'] = 'bank_details';
        $data['settings'] = $this->SettingsModel->getSettings();
        $this->load->view('admin/header', $data);
        $this->load->view('admin/bank_details', $data);
        $this->load->view('admin/footer');
    }

    public function passwordChange()
    {
        // Method logic for password change
        $data['activePage'] = 'password';
        $data['error'] = ''; // Initialize the error message
        $this->load->view('admin/header', $data);
        $this->load->view('admin/password_change', $data);
        $this->load->view('admin/footer');
    }

    public function updatePassword()
    {
        $this->form_validation->set_rules('current_password', 'Current Password', 'trim|required');
        $this->form_validation->set_rules('new_password', 'New Password', 'trim|required|min_length[5]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[new_password]');

        $data['activePage'] = 'password';

        if ($this->form_validation->run() == FALSE) {
            // Form validation failed, reload the change password page with validation errors
            $this->load->view('admin/header', $data);
            $this->load->view('admin/password_change', $data);
            $this->load->view('admin/footer');
        } else {
            // Form validation succeeded, update the user's password
            $current_password = $this->input->post('current_password');
            $new_password = $this->input->post('new_password');

            // Check if the current password matches the logged-in user's password
            $user_id = $this->session->userdata('user_id');
            $user = $this->UserModel->getUserById($user_id);

            if (password_verify($current_password, $user['password'])) {
                // Current password is correct, update the password
                $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
                $this->UserModel->updatePassword($user_id, $hashed_password);

                // Set flash message for success
                $this->session->set_flashdata('message', 'Password updated successfully!');
                redirect('admin/password'); // Redirect to avoid form resubmission
            } else {
                // Current password is incorrect, show an error message
                $this->session->set_flashdata('error', 'Current password is incorrect.');
                redirect('admin/password'); // Redirect to avoid form resubmission
            }
        }
    }
}
