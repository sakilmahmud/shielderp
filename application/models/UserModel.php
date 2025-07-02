<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UserModel extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getUserById($user_id)
    {
        // Query to retrieve user details along with the added_by user's name
        $this->db->select('u.*, a.full_name AS added_by_name');
        $this->db->from('users u');
        $this->db->join('users a', 'a.id = u.added_by', 'left'); // Join with the same table to get added_by name
        $this->db->where('u.id', $user_id);
        $query = $this->db->get();

        return $query->row_array();
    }


    public function get_all_users()
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('status', 1);
        $this->db->where('user_role !=', 1); // Exclude users with role_id 1

        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_all_staff()
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('status', 1);
        $this->db->where_in('user_role', [2, 3]); // Include users with role_id 2 or 3

        $query = $this->db->get();
        return $query->result_array();
    }


    public function get_all_clients()
    {
        $query = $this->db->get_where('users', array('user_role' => 4));
        return $query->result_array();
    }

    public function get_all_doers()
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('status', 1);
        $this->db->where_in('user_role', [2, 3]); // Include users with role_id 2 or 3

        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_user($id)
    {
        $query = $this->db->get_where('users', array('id' => $id));
        return $query->row_array();
    }

    public function insert_user($data)
    {
        $this->db->insert('users', $data);

        // Get the inserted patient's ID
        $patientId = $this->db->insert_id();
        return $patientId;
    }

    public function update_user($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('users', $data);
    }

    public function delete_user($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('users');
    }




    public function getUsers($role_id = null, $added_by = null)
    {
        /**
         * User roles:
         * 1 = Super Admin
         * 2 = Admin
         * 3 = Doer
         * 4 = Client
         */

        // Fetch the users from the database
        $this->db->select('*');
        $this->db->from('users');

        // Apply role filter: handle single role or multiple roles
        if (!is_null($role_id)) {
            if (is_array($role_id)) {
                $this->db->where_in('user_role', $role_id); // Multiple roles
            } else {
                $this->db->where('user_role', $role_id); // Single role
            }
        }

        // Apply added_by filter if provided
        if (!is_null($added_by)) {
            $this->db->where('added_by', $added_by);
        }

        // Order by descending ID by default
        $this->db->order_by('id', 'desc');

        // Execute the query and return the results
        $query = $this->db->get();
        return $query->result_array();
    }


    public function getUserByUsernameOrMobileOrEmail($usernameOrMobileOrEmail)
    {
        // Replace 'users' with your actual table name for storing user data
        $this->db->where('username', $usernameOrMobileOrEmail);
        $this->db->or_where('mobile', $usernameOrMobileOrEmail);
        $this->db->or_where('email', $usernameOrMobileOrEmail);
        $query = $this->db->get('users');
        return $query->row_array();
    }


    public function updatePassword($user_id, $hashed_password)
    {
        $data = array(
            'password' => $hashed_password
        );

        $this->db->where('id', $user_id);
        $this->db->update('users', $data);

        return $this->db->affected_rows() > 0;
    }
}
