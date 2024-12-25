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

    public function getFilteredData($from_date, $to_date, $created_by = null, $category_id = null, $paid_status = null)
    {
        $this->db->select('*');
        $this->db->from('dtp_services');
        $this->db->where('service_date >=', $from_date);
        $this->db->where('service_date <=', $to_date);

        if (!empty($created_by)) {
            $this->db->where('created_by', $created_by);
        }
        if (!empty($category_id)) {
            $this->db->where('dtp_service_category_id', $category_id);
        }
        if ($paid_status !== null && $paid_status !== '') { // Check for all valid statuses
            $this->db->where('paid_status', $paid_status);
        }

        // Multiple order by conditions
        $this->db->order_by('service_date', 'DESC'); // Primary sorting
        $this->db->order_by('id', 'DESC'); // Secondary sorting (e.g., for tie-breaking)

        $query = $this->db->get();
        return $query->result_array();
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
