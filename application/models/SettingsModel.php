<?php
// models/SettingsModel.php

class SettingsModel extends CI_Model
{
    public function getSettings()
    {
        // Replace 'settings' with your actual table name for storing settings
        $query = $this->db->get('settings');
        $settings = $query->result_array();

        // Create an associative array of settings with setting_name as keys
        $result = array();
        foreach ($settings as $setting) {
            $result[$setting['setting_name']] = $setting['setting_value'];
        }

        return $result;
    }

    public function get_setting($setting_name)
    {
        $query = $this->db->get_where('settings', ['setting_name' => $setting_name]);
        return $query->row()->setting_value;
    }

    public function getSetting($setting_name)
    {
        // Replace 'settings' with your actual table name for storing settings
        $query = $this->db->get_where('settings', array('setting_name' => $setting_name));
        $setting = $query->row_array();

        // Return the value of the setting if found, otherwise return null
        return isset($setting['setting_value']) ? $setting['setting_value'] : null;
    }

    public function updateSettings($settings)
    {
        foreach ($settings as $setting_name => $setting_value) {
            // Check if the setting_name exists in the database
            $query = $this->db->get_where('settings', array('setting_name' => $setting_name));
            $result = $query->row();
    
            if ($result) {
                // If the setting_name exists, update its value
                $this->db->where('setting_name', $setting_name);
                $this->db->update('settings', array('setting_value' => $setting_value));
            } else {
                // If the setting_name doesn't exist, add it as a new setting
                $this->db->insert('settings', array('setting_name' => $setting_name, 'setting_value' => $setting_value));
            }
        }
    }
}
