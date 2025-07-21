<?php

class AdminController extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('UserModel');
        $this->load->library('form_validation');
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
            redirect('login'); // Redirect to the login page if not logged in
        }
        $this->load->model('SettingsModel');
        $this->load->helper('custom_helper');
        $this->load->model('TaskModel');
        $this->load->model('DashboardModel');
        $this->load->model('ReminderModel');
        $this->load->library('upload'); // Make sure 'upload' is the correct name of the library class
    }

    public function index()
    {
        if ($this->session->userdata('username')) {
            redirect('admin/dashboard');
        }
    }

    public function demoDashboard()
    {
        $data = [
            'title' => 'Admin Dashboard',
            'activePage' => 'demo_dashboard',
            'something' => 'Welcome to Admin Panel'
        ];

        $this->render_admin('admin/dashboard-demo', $data);
    }


    public function dashboard()
    {
        $data['activePage'] = 'dashboard';

        $filter = $this->input->get('filter') ?? 'today';
        $from = $to = date('Y-m-d');

        if ($filter === 'last_7') {
            $from = date('Y-m-d', strtotime('-6 days'));
        } elseif ($filter === 'last_30') {
            $from = date('Y-m-d', strtotime('-29 days'));
        } elseif ($filter === 'custom') {
            $from = $this->input->get('from_date');
            $to = $this->input->get('to_date');
        }

        $data['filter'] = $filter;
        $data['from_date'] = $from;
        $data['to_date'] = $to;

        $data['payin'] = $this->DashboardModel->get_total_payin($from, $to);
        $data['payout'] = $this->DashboardModel->get_total_payout($from, $to);
        $data['sales'] = $this->DashboardModel->get_total_sales($from, $to);
        $data['purchase'] = $this->DashboardModel->get_total_purchase($from, $to);

        $data['total_customer_due'] = $this->get_total_customer_due();
        $data['total_supplier_due'] = $this->get_total_supplier_due();

        $data['reminders'] = $this->ReminderModel->get_active_reminders();


        // keep existing dashboard logic
        /* $data['sales_report'] = [
            'daily' => $this->get_sales_total('daily'),
            'weekly' => $this->get_sales_total('weekly'),
            'monthly' => $this->get_sales_total('monthly'),
        ];
        $data['purchase_report'] = [
            'daily' => $this->get_purchase_total('daily'),
            'weekly' => $this->get_purchase_total('weekly'),
            'monthly' => $this->get_purchase_total('monthly'),
        ]; */
        $data['top_categories'] = $this->get_top_categories_sales();
        $data['monthly_sales'] = $this->get_monthly_sales_data();
        $data['monthly_purchases'] = $this->get_monthly_purchase_data();
        $stock_summary = $this->get_low_stock_summary();
        $data['low_stock_count'] = $stock_summary['low'];
        $data['total_products_count'] = $stock_summary['total'];

        $this->render_admin('admin/dashboard', $data);
    }


    private function get_low_stock_summary()
    {
        $this->db->select('id, low_stock_alert');
        $products = $this->db->get('products')->result_array();
        $total = count($products);
        $low = 0;

        foreach ($products as $product) {
            $product_id = $product['id'];

            $this->db->select_sum('quantity');
            $this->db->where('product_id', $product_id);
            $stock_in = $this->db->get('stock_management')->row()->quantity ?? 0;

            $this->db->select_sum('quantity');
            $this->db->where('product_id', $product_id);
            $stock_out = $this->db->get('invoice_details')->row()->quantity ?? 0;

            $final_stock = $stock_in - $stock_out;

            if ($final_stock < $product['low_stock_alert']) {
                $low++;
            }
        }

        return ['low' => $low, 'total' => $total];
    }

    private function get_total_customer_due()
    {
        $due_list = $this->due_customer_list();
        $total = array_sum(array_column($due_list, 'due_amount'));
        return $total;
    }

    private function get_total_supplier_due()
    {
        $due_list = $this->due_supplier_list();
        $total = array_sum(array_column($due_list, 'due_amount'));
        return $total;
    }

    private function get_monthly_sales_data()
    {
        $year = date('Y');
        $monthly_sales = [];

        for ($m = 1; $m <= 12; $m++) {
            $start = date("{$year}-{$m}-01");
            $end = date("{$year}-{$m}-t");

            $this->db->select('SUM(total_amount) as total');
            $this->db->from('invoices');
            $this->db->where('invoice_date >=', $start);
            $this->db->where('invoice_date <=', $end);
            $result = $this->db->get()->row_array();

            $monthly_sales[] = round($result['total'] ?? 0, 2);
        }

        return $monthly_sales;
    }

    private function get_monthly_purchase_data()
    {
        $year = date('Y');
        $monthly_purchases = [];

        for ($m = 1; $m <= 12; $m++) {
            $start = date("{$year}-{$m}-01");
            $end = date("{$year}-{$m}-t");

            $this->db->select('SUM(total_amount) as total');
            $this->db->from('purchase_orders');
            $this->db->where('purchase_date >=', $start);
            $this->db->where('purchase_date <=', $end);
            $result = $this->db->get()->row_array();

            $monthly_purchases[] = round($result['total'] ?? 0, 2);
        }

        return $monthly_purchases;
    }

    private function get_sales_total($range)
    {
        $this->db->select('SUM(total_amount) as total');
        $this->db->from('invoices');

        if ($range === 'daily') {
            $this->db->where('DATE(invoice_date)', date('Y-m-d'));
        } elseif ($range === 'weekly') {
            $this->db->where('invoice_date >=', date('Y-m-d', strtotime('-7 days')));
        } elseif ($range === 'monthly') {
            $this->db->where('invoice_date >=', date('Y-m-d', strtotime('-30 days')));
        }

        $query = $this->db->get();
        $result = $query->row_array();

        return $result['total'] ?? 0;
    }

    private function get_purchase_total($range)
    {
        $this->db->select('SUM(total_amount) as total');
        $this->db->from('purchase_orders');

        if ($range === 'daily') {
            $this->db->where('DATE(purchase_date)', date('Y-m-d'));
        } elseif ($range === 'weekly') {
            $this->db->where('purchase_date >=', date('Y-m-d', strtotime('-7 days')));
        } elseif ($range === 'monthly') {
            $this->db->where('purchase_date >=', date('Y-m-d', strtotime('-30 days')));
        }

        $query = $this->db->get();
        $result = $query->row_array();

        return $result['total'] ?? 0;
    }

    private function get_top_categories_sales()
    {
        $this->db->select('categories.name, SUM(invoice_details.final_price) as total_sales');
        $this->db->from('invoice_details');
        $this->db->join('products', 'products.id = invoice_details.product_id');
        $this->db->join('categories', 'categories.id = products.category_id');
        $this->db->group_by('categories.id');
        $this->db->order_by('total_sales', 'DESC');
        $this->db->limit(10);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function ajax_low_stock()
    {
        $this->load->helper('text');

        $this->db->select('id, name, low_stock_alert');
        $products = $this->db->get('products')->result_array();

        $low_stocks = [];

        foreach ($products as $product) {
            $product_id = $product['id'];

            // Total added stock
            $this->db->select_sum('quantity');
            $this->db->where('product_id', $product_id);
            $stock_in = $this->db->get('stock_management')->row()->quantity ?? 0;

            // Total sold
            $this->db->select_sum('quantity');
            $this->db->where('product_id', $product_id);
            $stock_out = $this->db->get('invoice_details')->row()->quantity ?? 0;

            $final_stock = $stock_in - $stock_out;

            if ($final_stock < $product['low_stock_alert']) {
                $low_stocks[] = [
                    'name' => $product['name'],
                    'quantity' => $final_stock
                ];
            }
        }

        // Sort ascending by quantity
        usort($low_stocks, function ($a, $b) {
            return $a['quantity'] <=> $b['quantity'];
        });

        // Load partial view
        $html = '<div class="due-scroll-container">';

        if (!empty($low_stocks)) {
            $html .= '<div class="list-group">';
            foreach ($low_stocks as $stock) {
                $html .= '
        <div class="list-group-item d-flex justify-content-between align-items-center">
            <div class="text-truncate" style="max-width: 75%;">
                <i class="fas fa-box-open text-warning mr-2"></i> 
                ' . htmlspecialchars(word_limiter($stock['name'], 6)) . '
            </div>
            <span class="badge badge-pill badge-danger px-3 py-2">
                ' . $stock['quantity'] . '
            </span>
        </div>';
            }
            $html .= '</div>';
        } else {
            $html .= '<div class="alert alert-success text-center mb-0">
            <i class="fas fa-check-circle mr-1"></i> All stocks are healthy.
        </div>';
        }

        $html .= '</div>';


        echo $html;
    }

    public function ajax_due_customers()
    {
        $data['due_customers'] = $this->due_customer_list();

        // Fix: Add true to return view content as string
        $html = $this->load->view('admin/dashboard/ajax_due_customers', $data, true);
        echo $html;
    }

    public function ajax_due_suppliers()
    {
        $data['due_suppliers'] = $this->due_supplier_list();

        // Fix: Add true to return view content as string
        $html = $this->load->view('admin/dashboard/ajax_due_suppliers', $data, true);
        echo $html;
    }


    private function due_customer_list()
    {
        $this->db->select('id, customer_name, phone');
        $customers = $this->db->get('customers')->result_array();

        $due_customers = [];

        foreach ($customers as $cust) {
            $customer_id = $cust['id'];

            // Get invoices for this customer
            $this->db->select('id, total_amount, invoice_date');
            $this->db->where('customer_id', $customer_id);
            $invoices = $this->db->get('invoices')->result_array();

            $total_purchase = 0;
            $total_paid = 0;

            foreach ($invoices as $inv) {
                $invoice_id = $inv['id'];
                $invoice_total = $inv['total_amount'];
                $invoice_date = $inv['invoice_date'];

                $total_purchase += $invoice_total;

                // Payments (trans_type = 1)
                $this->db->select_sum('amount');
                $this->db->where([
                    'trans_type' => 1,
                    'transaction_for_table' => 'invoices',
                    'table_id' => $invoice_id
                ]);
                $credit = $this->db->get('transactions')->row()->amount ?? 0;

                // Adjustments/Returns (trans_type = 2 or 3)
                $this->db->select_sum('amount');
                $this->db->where_in('trans_type', [2, 3]);
                $this->db->where([
                    'transaction_for_table' => 'invoices',
                    'table_id' => $invoice_id
                ]);
                $debit = $this->db->get('transactions')->row()->amount ?? 0;

                $total_paid += ($credit - $debit);
            }

            $due = $total_purchase - $total_paid;

            if ($due > 0) {
                $due_customers[] = [
                    'c_id' => $cust['id'],
                    'name' => $cust['customer_name'],
                    'mobile' => $cust['phone'],
                    'due_date' => $invoice_date ?? '',
                    'due_amount' => $due
                ];
            }
        }

        // 🔽 Sort by due_amount in descending order
        usort($due_customers, function ($a, $b) {
            return $b['due_amount'] <=> $a['due_amount'];
        });

        return $due_customers;
    }

    private function due_supplier_list()
    {
        $this->db->select('id, supplier_name');
        $suppliers = $this->db->get('suppliers')->result_array();

        $due_suppliers = [];

        foreach ($suppliers as $supplier) {
            $supplier_id = $supplier['id'];

            // Get purchase orders for this supplier
            $this->db->select('id, total_amount, due_date');
            $this->db->where('supplier_id', $supplier_id);
            $purchases = $this->db->get('purchase_orders')->result_array();

            $total_purchase = 0;
            $total_paid = 0;
            $latest_due_date = '';

            foreach ($purchases as $po) {
                $purchase_id = $po['id'];
                $purchase_total = $po['total_amount'];
                $due_date = $po['due_date'];

                $total_purchase += $purchase_total;
                $latest_due_date = $due_date; // Optional: capture last due date

                // Payments made by me (trans_type = 2)
                $this->db->select_sum('amount');
                $this->db->where([
                    'trans_type' => 2,
                    'transaction_for_table' => 'purchase_orders',
                    'table_id' => $purchase_id
                ]);
                $credit = $this->db->get('transactions')->row()->amount ?? 0;

                // Adjustments / Refunds from supplier (trans_type = 1 or 3)
                $this->db->select_sum('amount');
                $this->db->where_in('trans_type', [1, 3]);
                $this->db->where([
                    'transaction_for_table' => 'purchase_orders',
                    'table_id' => $purchase_id
                ]);
                $debit = $this->db->get('transactions')->row()->amount ?? 0;

                $total_paid += ($credit - $debit);
            }

            $due = $total_purchase - $total_paid;

            if ($due > 0) {
                $due_suppliers[] = [
                    's_id' => $supplier['id'],
                    'name' => $supplier['supplier_name'],
                    'due_date' => $latest_due_date ?? '',
                    'due_amount' => $due
                ];
            }
        }

        // 🔽 Sort by due_amount in descending order
        usort($due_suppliers, function ($a, $b) {
            return $b['due_amount'] <=> $a['due_amount'];
        });

        return $due_suppliers;
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


        $this->render_admin('admin/whatsapp', $data);
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

        $this->render_admin('admin/whatsapp_log', $data);
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


        $this->render_admin('admin/general_settings', $data);
    }

    public function updateSettings()
    {
        // Process the form submission and update the settings
        $settings = $this->input->post();
        $r_url = $this->input->post('page_url') ?? base_url('admin/settings');
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
        redirect($r_url);
    }

    public function companyDetails()
    {
        $data['activePage'] = 'company_details';
        $this->load->model('StateModel');
        $data['states'] = $this->StateModel->get_all_states();
        $data['settings'] = $this->SettingsModel->getSettings();

        $this->render_admin('admin/company_details', $data);
    }

    public function bankDetails()
    {
        $data['activePage'] = 'bank_details';
        $data['settings'] = $this->SettingsModel->getSettings();

        $this->render_admin('admin/bank_details', $data);
    }

    public function passwordChange()
    {
        // Method logic for password change
        $data['activePage'] = 'password';
        $data['error'] = ''; // Initialize the error message

        $this->render_admin('admin/password_change', $data);
    }

    public function updatePassword()
    {
        $this->form_validation->set_rules('current_password', 'Current Password', 'trim|required');
        $this->form_validation->set_rules('new_password', 'New Password', 'trim|required|min_length[5]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[new_password]');

        $data['activePage'] = 'password';

        if ($this->form_validation->run() == FALSE) {
            // Form validation failed, reload the change password page with validation errors

            $this->render_admin('admin/password_change', $data);
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

    public function premiumOnly()
    {
        $data['activePage'] = 'premium_only';
        $data['title'] = 'Premium Access Required';

        $this->render_admin('admin/premium_only');
    }

    public function add_reminder()
    {
        $this->load->model('ReminderModel');

        $content = $this->input->post('reminder_content', true);
        if (!empty($content)) {
            $this->ReminderModel->add_reminder($content);
        }

        redirect('admin/dashboard');
    }

    public function get_reminder_detail($id)
    {
        $this->load->model('ReminderModel');
        $reminder = $this->ReminderModel->get_reminder($id);
        echo json_encode($reminder);
    }

    public function mark_reminder_done($id)
    {
        $this->load->model('ReminderModel');
        $this->ReminderModel->mark_done($id);
        redirect('admin/dashboard');
    }
}
