<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CommonController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('UserModel');
    }

    public function getClientDetails($client_id)
    {
        // Fetch the client details from the UserModel
        $client = $this->UserModel->getUserById($client_id);

        // Format the created_at date
        if ($client) {
            $client['created_at'] = date('M d, Y h:i A', strtotime($client['created_at'])); // Format date
            $client['status'] = ($client['status'] == 1) ? 'Active' : 'Inactive'; // Convert status to string

            // Return the client details as JSON
            echo json_encode($client);
        } else {
            echo json_encode(['error' => 'Client not found']);
        }
    }

    public function searchCustomer()
    {
        $search_term = $this->input->get('term'); // Get the search term from the query string

        // Query the database to search by phone or name
        $this->db->like('phone', $search_term);
        $this->db->or_like('customer_name', $search_term);
        $query = $this->db->get('customers'); // Assuming you have a 'customers' table

        $customers = $query->result_array();

        // Return the result as JSON
        echo json_encode($customers);
    }
}
