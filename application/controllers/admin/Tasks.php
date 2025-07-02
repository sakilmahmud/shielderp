<?php

class Tasks extends MY_Controller
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

    public function taskManagement()
    {
        $data['activePage'] = 'tasks';

        $user_id = $this->session->userdata('user_id');
        $role = $this->session->userdata('role');

        $data['tasks'] = $this->TaskModel->get_all_tasks($user_id, $role);

        // Fetching clients and doers
        $data['clients'] = $this->UserModel->get_all_clients();
        $data['doers'] = $this->UserModel->get_all_doers();

        $this->render_admin('admin/tasks/index', $data);
    }

    public function addTask()
    {
        $data['activePage'] = 'tasks';

        // Fetching clients and doers
        $data['clients'] = $this->UserModel->get_all_clients();
        $data['all_staff'] = $this->UserModel->get_all_staff();
        $data['task_categories'] = $this->TaskModel->get_all_categories();
        /* echo "<pre>";
        print_r($data);
        die; */
        $this->form_validation->set_rules('title', 'Title', 'required');
        // Add more rules as needed

        if ($this->form_validation->run() === FALSE) {
            $data['isUpdate'] = false;


            $this->render_admin('admin/tasks/add', $data);
        } else {
            // Sanitize the title before inserting
            $title = htmlspecialchars($this->input->post('title'));
            $description = htmlspecialchars($this->input->post('description'));
            $client_id = $this->input->post('client_id');
            $doer_id = $this->input->post('doer_id');
            $start_date = date('Y-m-d H:i:s', strtotime($this->input->post('start_date')));
            $due_date = date('Y-m-d H:i:s', strtotime($this->input->post('due_date')));

            $taskData = array(
                'title' => $title,
                'description' => $description,
                'client_id' => $client_id,
                'doer_id' => $doer_id,
                'category_id' => $this->input->post('category_id'),
                'start_date' => $start_date,
                'due_date' => $due_date,
                'status' => $this->input->post('status')
            );
            /* echo "<pre>";
            print_r($taskData);
            die; */

            $this->TaskModel->insert_task($taskData);

            // Set flash message for success
            $this->session->set_flashdata('message', 'New task added successfully!');

            $client_details = getUserDetails($client_id);
            $doer_details = getUserDetails($doer_id);

            /* print_r($client_details);
            die; */

            if (!empty($client_details) && !empty($doer_details)) {
                $client_name = $client_details->full_name;
                $client_mobile = $client_details->mobile;

                $doer_name = $doer_details->full_name;
                $doer_mobile = $doer_details->mobile;

                $start_time = date('jS F gA', strtotime($start_date));
                $end_time = date('jS F gA', strtotime($due_date));

                /** Doer Message template*/

                //$message_body = "*New Task has been assigned*\n\n";
                $message_body = "Client: *$client_name*\n";
                $message_body .= "Mobile: $client_mobile\n";
                $message_body .= "Task: *$title*\n";
                $message_body .= "Time: *$start_time - $end_time*";

                //$doer_msg = "Hey $doer_name ! A new task has been assigned for $client_name";

                $doer_msg = "Hey *$doer_name*! A new task has been assigned for *$client_name*";
                $doer_msg .= "\n\n" . $message_body;

                /** end of Doer Message template*/

                //$client_msg = "Hello $client_name ! $doer_name has been assigned for $title on $start_date to $due_date";

                sendTextMsg($doer_mobile, $doer_msg);
                //sendTextMsg($client_mobile, $client_msg);
            }


            redirect('admin/tasks');
        }
    }

    public function edit($id)
    {
        $data['activePage'] = 'tasks';

        // Fetching clients and doers

        $data['clients'] = $this->UserModel->get_all_clients();
        $data['all_staff'] = $this->UserModel->get_all_staff();
        $data['task_categories'] = $this->TaskModel->get_all_categories();

        $this->form_validation->set_rules('title', 'Title', 'required');
        // Add more rules as needed

        if ($this->form_validation->run() === FALSE) {
            $data['isUpdate'] = true;
            $data['task'] = $this->TaskModel->get_task($id);

            $this->render_admin('admin/tasks/add', $data);
        } else {
            $title = htmlspecialchars($this->input->post('title'));
            $description = htmlspecialchars($this->input->post('description'));
            $client_id = $this->input->post('client_id');
            $doer_id = $this->input->post('doer_id');
            $start_date = date('Y-m-d H:i:s', strtotime($this->input->post('start_date')));
            $due_date = date('Y-m-d H:i:s', strtotime($this->input->post('due_date')));


            $taskData = array(
                'title' => $title,
                'description' => $description,
                'client_id' => $client_id,
                'doer_id' => $doer_id,
                'category_id' => $this->input->post('category_id'),
                'start_date' => $start_date,
                'due_date' => $due_date,
                'status' => $this->input->post('status'),
                'updated_at' => date('Y-m-d H:i:s')
            );

            $this->TaskModel->update_task($id, $taskData);

            // Set flash message for success
            $this->session->set_flashdata('message', 'Ttask updated successfully!');

            $client_details = getUserDetails($client_id);
            $doer_details = getUserDetails($doer_id);

            /* print_r($client_details);
            die; */

            if (!empty($client_details) && !empty($doer_details)) {
                $client_name = $client_details->full_name;
                $client_mobile = $client_details->mobile;

                $doer_name = $doer_details->full_name;
                $doer_mobile = $doer_details->mobile;

                $start_time = date('jS F gA', strtotime($start_date));
                $end_time = date('jS F gA', strtotime($due_date));

                /** Doer Message template*/

                //$message_body = "*New Task has been assigned*\n\n";
                $message_body = "Client: *$client_name*\n";
                $message_body .= "Mobile: $client_mobile\n";
                $message_body .= "Task: *$title*\n";
                $message_body .= "Time: *$start_time - $end_time*";

                //$doer_msg = "Hey $doer_name ! A new task has been assigned for $client_name";

                $doer_msg = "Hey *$doer_name*! A new task has been assigned for *$client_name*";
                $doer_msg .= "\n\n" . $message_body;

                /** end of Doer Message template*/

                sendTextMsg($doer_mobile, $doer_msg);
            }

            redirect('admin/tasks');
        }
    }

    public function delete($id)
    {
        $this->TaskModel->delete_task($id);
        redirect('admin/tasks');
    }

    /** for data table */
    public function getTasks()
    {
        $tasks = $this->TaskModel->get_all_tasks(); // Adjust this method as per your model

        $data = [];
        foreach ($tasks as $task) {
            $status_label = '';
            if ($task['status'] == 1) {
                if ($task['start_date'] > $task['done_time']) {
                    $status_label = '<span class="badge badge-primary">Advanced</span>';
                } elseif ($task['due_date'] > $task['done_time']) {
                    $status_label = '<span class="badge badge-success">On time</span>';
                } else {
                    $status_label = '<span class="badge badge-danger">Late</span>';
                }
            } elseif ($task['status'] == 0) {
                if ($task['due_date'] < date('Y-m-d H:i:s')) {
                    $status_label = '<span class="badge badge-danger">Overdue</span>';
                } elseif ($task['start_date'] < date('Y-m-d H:i:s') && $task['due_date'] > date('Y-m-d H:i:s')) {
                    $status_label = '<span class="badge badge-info">Ongoing</span>';
                } else {
                    $status_label = '<span class="badge badge-warning">Pending</span>';
                }
            }

            $action = '<div class="d-flex align-items-center" style="gap: 5px;">';

            if (!$task['done_time']) {
                $action .= '<button class="btn btn-warning btn-sm mark-as-done" data-task-id="' . $task['id'] . '" data-toggle="modal" data-target="#doneModal">Mark as done</button>';
            } else {
                $action .= '<button class="btn btn-info btn-sm view-note" data-task-id="' . $task['id'] . '" data-note="' . htmlspecialchars($task['note']) . '" data-toggle="modal" data-target="#noteModal">View Note</button>';
            }

            $action .= '<a href="' . base_url('admin/tasks/edit/') . $task['id'] . '" class="btn btn-warning btn-sm">Edit</a>';

            if ($this->session->userdata('role') == 1) {
                $action .= '<a href="' . base_url('admin/tasks/delete/') . $task['id'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to delete this task?\')">Delete</a>';
            }
            $action .= '</div>';

            $data[] = [
                'id' => $task['id'],
                'title' => $task['title'],
                'client_name' => $task['client_name'],
                'doer_name' => $task['doer_name'],
                'category_name' => $task['category_name'],
                'start_date' => date('M d, Y h:i A', strtotime($task['start_date'])),
                'due_date' => date('M d, Y h:i A', strtotime($task['due_date'])),
                'status' => $status_label, // Create a helper function for status
                'done_time' => $task['done_time'] ? date('M d, Y h:i A', strtotime($task['done_time'])) : 'N/A',
                'actions' => $action // Create a helper function for actions
            ];
        }

        echo json_encode(['data' => $data]);
    }
}
