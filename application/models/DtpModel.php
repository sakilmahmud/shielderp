<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DtpModel extends CI_Model
{

    public function getCategories()
    {
        $this->db->where('status', 1);
        return $this->db->get('dtp_service_categories')->result_array();
    }

    public function saveCategory($data)
    {
        $this->db->insert('dtp_service_categories', $data);
        return $this->db->insert_id(); // Return the last inserted ID
    }


    public function getCategory($id)
    {
        return $this->db->where('id', $id)->get('dtp_service_categories')->row_array();
    }

    public function updateCategory($id, $data)
    {
        return $this->db->where('id', $id)->update('dtp_service_categories', $data);
    }

    public function deleteCategory($id)
    {
        return $this->db->where('id', $id)->delete('dtp_service_categories');
    }

    public function all()
    {
        return $this->db->get('dtp_services')->result_array();
    }

    public function getFilteredDataOld($from_date, $to_date, $created_by = null, $category_id = null, $paid_status = null, $payment_mode = null)
    {
        $this->db->select('dtp_services.*, dtp_service_categories.cat_title AS category_title, payment_methods.title AS payment_mode_title, users.full_name AS created_by_name');

        $this->db->from('dtp_services');

        // Left join with dtp_service_categories
        $this->db->join('dtp_service_categories', 'dtp_service_categories.id = dtp_services.dtp_service_category_id', 'left');
        // Left join with payment_methods
        $this->db->join('payment_methods', 'payment_methods.id = dtp_services.payment_mode', 'left');
        // Left join with users
        $this->db->join('users', 'users.id = dtp_services.created_by', 'left');

        // Filters
        $this->db->where('dtp_services.service_date >=', $from_date);
        $this->db->where('dtp_services.service_date <=', $to_date);

        if (!empty($created_by)) {
            $this->db->where('dtp_services.created_by', $created_by);
        }
        if (!empty($category_id)) {
            $this->db->where('dtp_services.dtp_service_category_id', $category_id);
        }
        if ($paid_status !== null && $paid_status !== '') { // Check for all valid statuses
            $this->db->where('dtp_services.paid_status', $paid_status);
        }
        if (!is_null($payment_mode) && $payment_mode !== '') { // Add Payment Mode filter
            $this->db->where('dtp_services.payment_mode', $payment_mode);
        }

        // Order By
        $this->db->order_by('dtp_services.service_date', 'DESC'); // Primary sorting
        $this->db->order_by('dtp_services.id', 'DESC'); // Secondary sorting (tie-breaking)

        $query = $this->db->get();
        return $query->result_array();
    }

    public function getFilteredData($from_date, $to_date, $created_by = null, $category_id = null, $paid_status = null, $payment_mode = null, $search_value = null, $start = 0, $length = 10)
    {
        // Base query
        $this->db->select('dtp_services.*, dtp_service_categories.cat_title AS category_title, payment_methods.title AS payment_mode_title, users.full_name AS created_by_name');
        $this->db->from('dtp_services');

        // Joins
        $this->db->join('dtp_service_categories', 'dtp_service_categories.id = dtp_services.dtp_service_category_id', 'left');
        $this->db->join('payment_methods', 'payment_methods.id = dtp_services.payment_mode', 'left');
        $this->db->join('users', 'users.id = dtp_services.created_by', 'left');

        // Filters
        $this->db->where('dtp_services.service_date >=', $from_date);
        $this->db->where('dtp_services.service_date <=', $to_date);

        if (!empty($created_by)) {
            $this->db->where('dtp_services.created_by', $created_by);
        }
        if (!empty($category_id)) {
            $this->db->where('dtp_services.dtp_service_category_id', $category_id);
        }
        if ($paid_status !== null && $paid_status !== '') {
            $this->db->where('dtp_services.paid_status', $paid_status);
        }
        if (!is_null($payment_mode) && $payment_mode !== '') {
            $this->db->where('dtp_services.payment_mode', $payment_mode);
        }

        // Search functionality
        if (!empty($search_value)) {
            $this->db->group_start(); // Start grouping conditions for search
            $this->db->like('dtp_services.service_descriptions', $search_value);
            $this->db->or_like('dtp_service_categories.cat_title', $search_value);
            $this->db->or_like('payment_methods.title', $search_value);
            $this->db->or_like('users.full_name', $search_value);
            $this->db->or_like('dtp_services.service_date', $search_value);
            $this->db->group_end(); // End grouping
        }

        // Order and Pagination
        $this->db->order_by('dtp_services.service_date', 'DESC');
        $this->db->order_by('dtp_services.id', 'DESC');
        if ($length != -1) { // -1 means no pagination (fetch all data)
            $this->db->limit($length, $start);
        }

        $query = $this->db->get();
        $result['data'] = $query->result_array();

        // Count total records (without filters)
        $this->db->reset_query();
        $this->db->from('dtp_services');
        $total_records = $this->db->count_all_results();

        // Count filtered records
        $this->db->reset_query();
        $this->db->select('dtp_services.id');
        $this->db->from('dtp_services');
        $this->db->join('dtp_service_categories', 'dtp_service_categories.id = dtp_services.dtp_service_category_id', 'left');
        $this->db->join('payment_methods', 'payment_methods.id = dtp_services.payment_mode', 'left');
        $this->db->join('users', 'users.id = dtp_services.created_by', 'left');

        // Reapply filters for filtered count
        $this->db->where('dtp_services.service_date >=', $from_date);
        $this->db->where('dtp_services.service_date <=', $to_date);
        if (!empty($created_by)) {
            $this->db->where('dtp_services.created_by', $created_by);
        }
        if (!empty($category_id)) {
            $this->db->where('dtp_services.dtp_service_category_id', $category_id);
        }
        if ($paid_status !== null && $paid_status !== '') {
            $this->db->where('dtp_services.paid_status', $paid_status);
        }
        if (!is_null($payment_mode) && $payment_mode !== '') {
            $this->db->where('dtp_services.payment_mode', $payment_mode);
        }
        if (!empty($search_value)) {
            $this->db->group_start();
            $this->db->like('dtp_services.service_descriptions', $search_value);
            $this->db->or_like('dtp_service_categories.cat_title', $search_value);
            $this->db->or_like('payment_methods.title', $search_value);
            $this->db->or_like('users.full_name', $search_value);
            $this->db->or_like('dtp_services.service_date', $search_value);
            $this->db->group_end();
        }
        $filtered_records = $this->db->count_all_results();

        // Combine results
        $result['recordsTotal'] = $total_records;
        $result['recordsFiltered'] = $filtered_records;

        return $result;
    }

    public function saveService($data)
    {
        $this->db->insert('dtp_services', $data);
        return $this->db->insert_id(); // Return the last inserted ID
    }

    public function getService($id)
    {
        return $this->db->where('id', $id)->get('dtp_services')->row_array();
    }

    public function updateService($id, $data)
    {
        return $this->db->where('id', $id)->update('dtp_services', $data);
    }

    public function deleteService($id)
    {
        return $this->db->where('id', $id)->delete('dtp_services');
    }

    // In DtpModel.php
    public function getServiceLogs($serviceId)
    {
        // Query to get logs for the specific service from the log_dtp_services table
        $this->db->select('log_data, action, made_by_name, created_at');
        $this->db->from('log_dtp_services');
        $this->db->where('dtp_service_id', $serviceId);  // Ensure your log table has a `service_id` column
        $this->db->order_by('created_at', 'DESC');   // Order by the latest log entry
        $query = $this->db->get();

        // Check if any logs are found
        if ($query->num_rows() > 0) {
            return $query->result_array();  // Return log data
        } else {
            return false;  // Return false if no logs found
        }
    }
}
