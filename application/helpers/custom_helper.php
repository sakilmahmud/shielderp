<?php

function numberTowords($num)
{
    $ones = array(
        1 => "one",
        2 => "two",
        3 => "three",
        4 => "four",
        5 => "five",
        6 => "six",
        7 => "seven",
        8 => "eight",
        9 => "nine",
        10 => "ten",
        11 => "eleven",
        12 => "twelve",
        13 => "thirteen",
        14 => "fourteen",
        15 => "fifteen",
        16 => "sixteen",
        17 => "seventeen",
        18 => "eighteen",
        19 => "nineteen"
    );

    $tens = array(
        1 => "ten",
        2 => "twenty",
        3 => "thirty",
        4 => "forty",
        5 => "fifty",
        6 => "sixty",
        7 => "seventy",
        8 => "eighty",
        9 => "ninety"
    );

    $hundreds = array(
        "hundred",
        "thousand",
        "million",
        "billion",
        "trillion",
        "quadrillion"
    ); //limit t quadrillion 

    $num = number_format($num, 2, ".", ",");
    $num_arr = explode(".", $num);
    $wholenum = $num_arr[0];
    $decnum = $num_arr[1];
    $whole_arr = array_reverse(explode(",", $wholenum));
    krsort($whole_arr);
    $rettxt = "";
    foreach ($whole_arr as $key => $i) {
        if ($i < 20) {
            $rettxt .= $ones[$i];
        } elseif ($i < 100) {
            $rettxt .= $tens[substr($i, 0, 1)];
            $rettxt .= " " . $ones[substr($i, 1, 1)];
        } else {
            @$rettxt .= $ones[substr($i, 0, 1)] . " " . $hundreds[0];
            @$rettxt .= " " . $tens[substr($i, 1, 1)];
            @$rettxt .= " " . $ones[substr($i, 2, 1)];
        }
        if ($key > 0) {
            $rettxt .= " " . $hundreds[$key] . " ";
        }
    }
    if ($decnum > 0) {
        $rettxt .= " and ";
        if ($decnum < 20) {
            $rettxt .= $ones[$decnum];
        } elseif ($decnum < 100) {
            $rettxt .= $tens[substr($decnum, 0, 1)];
            $rettxt .= " " . $ones[substr($decnum, 1, 1)];
        }
    }
    return $rettxt;
}

function getUserDetails($user_id)
{
    $CI = &get_instance();
    // Fetch user details from the database based on the user_id
    $query = $CI->db->get_where('users', ['id' => $user_id]);

    if ($query->num_rows() > 0) {
        return $query->row();
    } else {
        return null;
    }
}

function getPaymentModes()
{
    $CI = &get_instance();
    $query = $CI->db->get_where('payment_methods');

    $arr = [];

    if ($query->num_rows() > 0) {
        $all = $query->result_array();
        if (!empty($all)) {
            foreach ($all as $single) {
                $arr[$single['id']] = $single['title'];
            }
        }
        return $arr;
    } else {
        return null;
    }
}

function getPaymentModeDetails($id)
{
    $CI = &get_instance();
    $query = $CI->db->get_where('payment_methods', ['id' => $id]);

    if ($query->num_rows() > 0) {
        return $query->row_array();
    } else {
        return null;
    }
}

function getLastBalance($payment_method_id)
{
    $CI = &get_instance();

    // Order by created_at or id to get the latest record
    $CI->db->select('balance');
    $CI->db->where('payment_method_id', $payment_method_id);
    $CI->db->order_by('id', 'DESC');  // You can use 'id' if that better suits your table structure
    $CI->db->limit(1);

    $query = $CI->db->get('transactions');

    // Check if the result exists
    if ($query->num_rows() > 0) {
        $result = $query->row_array();
        return $result['balance'];  // Return only the balance
    } else {
        return null;  // Return null if no record exists
    }
}


function getProductStocks($product_id)
{
    $CI = &get_instance();

    // Get the total product quantity from stock_management
    $CI->db->select('SUM(quantity) as total_quantity, SUM(available_stock) as total_available_stocks');
    $CI->db->from('stock_management');
    $CI->db->where('product_id', $product_id);
    $product_query = $CI->db->get();
    $product_data = $product_query->row_array();

    $total_quantity = $product_data['total_quantity'] ?? 0; // Total quantity added to stock

    // Get the total sold quantity from invoice_details
    $CI->db->select('SUM(quantity) as sold_quantity');
    $CI->db->from('invoice_details');
    $CI->db->where('product_id', $product_id);
    $CI->db->where('status', 1); // Optional: filter only active or completed invoices
    $sold_query = $CI->db->get();
    $sold_data = $sold_query->row_array();

    $total_sold_quantity = $sold_data['sold_quantity'] ?? 0; // Total quantity sold

    // Calculate final stock
    $final_stock = $total_quantity - $total_sold_quantity;

    // Return the calculated stock data
    return [
        'product_id' => $product_id,
        'total_quantity' => $total_quantity,
        'total_sold_quantity' => $total_sold_quantity,
        'final_stock' => $final_stock
    ];
}

function getSetting($setting_name)
{
    $CI = &get_instance();
    // Replace 'settings' with your actual table name for storing settings
    $query = $CI->db->get_where('settings', array('setting_name' => $setting_name));
    $setting = $query->row_array();

    // Return the value of the setting if found, otherwise return null
    return isset($setting['setting_value']) ? $setting['setting_value'] : null;
}

function getCurrentFinancialYear()
{
    $currentYear = date('Y');
    $currentMonth = date('m');

    if ($currentMonth >= 4) {
        $financialYearStart = $currentYear;
        $financialYearEnd = $currentYear + 1;
    } else {
        $financialYearStart = $currentYear - 1;
        $financialYearEnd = $currentYear;
    }

    return $financialYearStart . '-' . substr($financialYearEnd, 2);
}

function sendTextMsg($receiver_number, $message, $file_url = "", $media_type = "")
{
    $apiKey = 'ZntBhRkhweXNW3Pnw8WD0Kx4WHarkV';
    $sender = '9635943842';
    $receiver_number = "91" . $receiver_number;

    if ($file_url != "") {
        // Send media message if file exists
        $url = "https://wa.aisensy.in/send-media?api_key=$apiKey&sender=$sender&number={$receiver_number}&media_type={$media_type}&caption=" . $message . "&url=" . $file_url;
    } else {
        // Send text message only if no file is uploaded
        $url = "https://wa.aisensy.in/send-message?api_key=$apiKey&sender=$sender&number={$receiver_number}&message=" . $message;
    }

    $response = file_get_contents($url);
    return $response;
}

function ge_media_type($file_type)
{
    switch ($file_type) {
        case 'image/jpeg':
        case 'image/png':
        case 'image/gif':
            $media_type = 'image';
            break;
        case 'application/pdf':
            $media_type = 'pdf';
            break;
        case 'audio/mpeg':
            $media_type = 'audio';
            break;
        case 'video/mp4':
            $media_type = 'video';
            break;
        default:
            $media_type = ''; // Default to an empty string if the file type is unknown
            break;
    }
    return $media_type;
}

function create_slug($string)
{
    // Convert the string to lowercase
    $slug = strtolower($string);

    // Remove any characters that are not alphanumeric or spaces
    $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);

    // Replace spaces and multiple hyphens with a single hyphen
    $slug = preg_replace('/[\s-]+/', '-', $slug);

    // Trim any leading or trailing hyphens
    $slug = trim($slug, '-');

    return $slug;
}

/* function get_product_types()
{
    $CI = &get_instance();
    $CI->db->select('*');
    $CI->db->from('product_types');
    $CI->db->where('parent_id', 0);
    $CI->db->where('status', 1);
    $CI->db->order_by('product_type_order', 'ASC');
    $query = $CI->db->get();

    return $query->result_array();
} */

function get_product_types()
{
    $CI = &get_instance();

    // First, fetch the parent categories
    $CI->db->select('*');
    $CI->db->from('product_types');
    $CI->db->where('parent_id', 0); // Parent categories
    $CI->db->where('status', 1); // Only active categories
    $CI->db->order_by('product_type_order', 'ASC');
    $parent_query = $CI->db->get();

    $parent_categories = $parent_query->result_array();

    // Loop through each parent to fetch its child categories
    foreach ($parent_categories as &$parent) {
        $CI->db->select('*');
        $CI->db->from('product_types');
        $CI->db->where('parent_id', $parent['id']); // Fetch children of the parent
        $CI->db->where('status', 1); // Only active child categories
        $CI->db->order_by('product_type_order', 'ASC');
        $child_query = $CI->db->get();

        // Add the child categories to the parent array
        $parent['children'] = $child_query->result_array();
    }

    return $parent_categories;
}

if (!function_exists('add_log_data')) {
    /**
     * Add log data to the specified table.
     * 
     * @param string $table_name Name of the log table (e.g., 'log_dtp_services' or 'log_dtp_service_categories').
     * @param array $data Log data containing 'log_data', 'action', 'made_by_id', and 'made_by_name'.
     */
    function add_log_data($table_name, $data)
    {
        $CI = &get_instance();
        $CI->db->insert($table_name, [
            'log_data' => json_encode($data['log_data']),
            'action' => $data['action'],
            'made_by_id' => $data['made_by_id'],
            'made_by_name' => $data['made_by_name'],
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
