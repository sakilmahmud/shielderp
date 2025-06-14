<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ProductModel');
        $this->load->model('CategoryModel');
        $this->load->library('pagination');
    }

    public function index()
    {

        $data['title'] = "Global Computers";

        // Fetch the latest 8 products
        $data['latest_products'] = $this->ProductModel->get_products(6);
        $data['popular_products'] = $this->ProductModel->get_products(6, "RAND()", "");
        $data['best_products'] = $this->ProductModel->get_products(6, "RAND()", "");
        $data['deal_products'] = $this->ProductModel->get_products(6, "RAND()", "");
        $data['new_arrairval'] = $this->ProductModel->get_products(8);

        // Fetch all categories
        $data['categories'] = $this->CategoryModel->get_all_categories();
        $this->load->view('inc/header', $data);
        $this->load->view('home', $data);
        $this->load->view('inc/footer', $data);
    }

    public function contact()
    {
        $data['title'] = "Contact :: Global Computers";

        $this->load->view('inc/header', $data);
        $this->load->view('contact', $data);
        $this->load->view('inc/footer', $data);
    }

    public function contact_submit()
    {
        $this->load->library('email');

        $name = $this->input->post('name');
        $phone = $this->input->post('phone');
        $email = $this->input->post('email');
        $msg = $this->input->post('msg');

        $from_name = 'Global Computers';
        $from_email = 'info@gcshop.in';

        // Email Setup
        $this->email->from($from_email, $from_name);
        $this->email->to('globalcomputers19@gmail.com');
        $this->email->subject("New Contact Enquiry from $name");
        $this->email->message("
        Name: $name\n
        Phone: $phone\n
        Email: $email\n
        Message: $msg
    ");

        if ($this->email->send()) {
            // Optional: Send WhatsApp notification here
            //$this->send_whatsapp_notification($name, $phone, $msg);
            echo json_encode(['status' => 'success', 'message' => 'Thanks! We will get back to you.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to send email.']);
        }
    }


    public function cart()
    {
        $data['title'] = "Cart :: Global Computers";

        $this->load->view('inc/header', $data);
        $this->load->view('cart', $data);
        $this->load->view('inc/footer', $data);
    }

    public function checkout()
    {
        $data['title'] = "Checkout :: Global Computers";

        $this->load->view('inc/header', $data);
        $this->load->view('checkout', $data);
        $this->load->view('inc/footer', $data);
    }

    public function product_details($slug)
    {
        // Fetch product details by slug
        $product = $this->ProductModel->get_product_by_slug($slug);
        $data['similar_products'] = $this->ProductModel->get_products(6, 'id', 'RAND');
        // Check if the product exists
        if (!$product) {
            show_404(); // Show 404 error if product not found
        }

        // Pass product data to the view
        $data['product'] = $product;
        $data['title'] = $product['name']; // Set the page title

        // Load the views
        $this->load->view('inc/header', $data);
        $this->load->view('product_details', $data);
        $this->load->view('inc/footer', $data);
    }

    public function products()
    {
        $data['title'] = "Products :: Global Computers";

        // Get sorting parameter from URL, default to 'name'
        $sort_by = $this->input->get('sort_by') ?: 'name';
        $sort_order = $this->input->get('order') ?: 'asc'; // Default order is ascending

        // Pagination settings
        $config['base_url'] = base_url('products'); // Base URL for pagination
        $config['total_rows'] = $this->ProductModel->count_all_products(); // Get total product count
        $config['per_page'] = 9; // Number of items per page
        $config['uri_segment'] = 3; // This will match with the pagination URL (3rd segment as page number)
        $config['reuse_query_string'] = TRUE; // Keep the sorting parameters in the URL

        // Initialize pagination
        $this->pagination->initialize($config);

        // Get the current page number from the URL, but this will be an offset for the LIMIT
        $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;

        // Fetch products based on pagination and sorting
        $data['products'] = $this->ProductModel->get_all_products($sort_by, $sort_order, $config['per_page'], $page);

        // Pagination offsets might cause issues, so let's ensure proper start and end
        if (!empty($data['products'])) {
            $data['start'] = $page + 1; // First product on the current page (corrected offset)
            $data['end'] = min($page + $config['per_page'], $config['total_rows']); // Last product on the current page
            $data['total_products'] = $config['total_rows']; // Total number of products
        } else {
            $data['start'] = 0;
            $data['end'] = 0;
            $data['total_products'] = 0;
        }

        // Pass pagination links and sorting information to the view
        $data['pagination_links'] = $this->pagination->create_links();
        $data['sort_by'] = $sort_by;
        $data['sort_order'] = $sort_order;

        // Load the view
        $this->load->view('inc/header', $data);
        $this->load->view('products', $data);
        $this->load->view('inc/footer', $data);
    }


    public function category_products($category_slug)
    {
        $data['title'] = ucfirst($category_slug) . " :: Global Computers"; // Dynamic page title

        // Get sorting parameter from URL, default to 'name'
        $sort_by = $this->input->get('sort_by') ?: 'name';
        $sort_order = $this->input->get('order') ?: 'asc'; // default order is ascending

        // Fetch category information based on the slug
        $category = $this->CategoryModel->get_category_by_slug($category_slug);

        if (!$category) {
            show_404(); // If category does not exist, show 404 page
        }

        // Pagination settings
        $config['base_url'] = base_url('categories/' . $category_slug); // Base URL for pagination
        $config['total_rows'] = $this->ProductModel->count_products_by_category($category['id']); // Get total products count for the category
        $config['per_page'] = 9; // Number of items per page
        $config['uri_segment'] = 3; // This will match with the pagination URL
        $config['reuse_query_string'] = TRUE; // Keep the sorting parameters in the URL

        // Initialize pagination
        $this->pagination->initialize($config);

        // Get the current page number from the URL
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        // Fetch products filtered by category, pagination, and sorting
        $data['products'] = $this->ProductModel->get_products_by_category($category['id'], $sort_by, $sort_order, $config['per_page'], $page);

        // Ensure at least some products are fetched
        if (!empty($data['products'])) {
            // Prepare data for dynamic "Showing X to Y out of Z" message
            $data['start'] = $page + 1; // First product on the current page
            $data['end'] = min($page + $config['per_page'], $config['total_rows']); // Last product on the current page
            $data['total_products'] = $config['total_rows']; // Total number of products
        } else {
            // If no products, avoid undefined variables
            $data['start'] = 0;
            $data['end'] = 0;
            $data['total_products'] = 0;
        }

        // Pass pagination links and sorting information to the view
        $data['pagination_links'] = $this->pagination->create_links();
        $data['sort_by'] = $sort_by;
        $data['sort_order'] = $sort_order;

        // Load the view
        $this->load->view('inc/header', $data);
        $this->load->view('products', $data);
        $this->load->view('inc/footer', $data);
    }

    public function product_type($type_slug)
    {
        $data['title'] = ucfirst($type_slug) . " :: Global Computers";

        // Get sorting parameter from URL, default to 'name'
        $sort_by = $this->input->get('sort_by') ?: 'name';
        $sort_order = $this->input->get('order') ?: 'asc'; // default order is ascending

        // Fetch product type information based on the slug
        $product_type = $this->ProductModel->get_product_type_by_slug($type_slug);

        if (!$product_type) {
            show_404(); // If product type does not exist, show 404 page
        }

        // Pagination settings
        $config['base_url'] = base_url('product-type/' . $type_slug); // Base URL for pagination
        $config['total_rows'] = $this->ProductModel->count_products_by_type($product_type['id']); // Get total products count for the product type
        $config['per_page'] = 9; // Number of items per page
        $config['uri_segment'] = 3; // This will match with the pagination URL
        $config['reuse_query_string'] = TRUE; // Keep the sorting parameters in the URL

        // Initialize pagination
        $this->pagination->initialize($config);

        // Get the current page number from the URL
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        // Fetch products filtered by product type, pagination, and sorting
        $data['products'] = $this->ProductModel->get_products_by_type($product_type['id'], $sort_by, $sort_order, $config['per_page'], $page);

        // Ensure at least some products are fetched
        if (!empty($data['products'])) {
            // Prepare data for dynamic "Showing X to Y out of Z" message
            $data['start'] = $page + 1; // First product on the current page
            $data['end'] = min($page + $config['per_page'], $config['total_rows']); // Last product on the current page
            $data['total_products'] = $config['total_rows']; // Total number of products
        } else {
            // If no products, avoid undefined variables
            $data['start'] = 0;
            $data['end'] = 0;
            $data['total_products'] = 0;
        }

        // Pass pagination links and sorting information to the view
        $data['pagination_links'] = $this->pagination->create_links();
        $data['sort_by'] = $sort_by;
        $data['sort_order'] = $sort_order;

        // Load the view
        $this->load->view('inc/header', $data);
        $this->load->view('products', $data);
        $this->load->view('inc/footer', $data);
    }

    public function terms()
    {
        $data['title'] = "Global Computers :: Term & Conditions";
        $data['meta_descriptions'] = "Global Computers";
        $data['canonical_url'] = base_url('terms');

        $this->load->view('inc/header', $data);
        $this->load->view('terms', $data);
        $this->load->view('inc/footer', $data);
    }
    public function privacy()
    {
        $data['title'] = "Global Computers :: Privacy & Policy";
        $data['meta_descriptions'] = "Global Computers";
        $data['canonical_url'] = base_url('privacy');

        $this->load->view('inc/header', $data);
        $this->load->view('privacy', $data);
        $this->load->view('inc/footer', $data);
    }
}
