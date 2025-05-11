<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProductsController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ProductModel');
        $this->load->model('CategoryModel');
        $this->load->model('BrandModel');
        $this->load->model('UnitModel');
        $this->load->model('ProductTypeModel');
        $this->load->library('form_validation');
        $this->load->library('upload');

        if (!$this->session->userdata('username')) {
            redirect('login');
        }
    }

    public function index()
    {
        $data['activePage'] = 'products';

        // Get all categories, brands, and product types for filters
        $data['categories'] = $this->CategoryModel->get_all_categories();
        $data['brands'] = $this->BrandModel->get_all_brands();
        $data['product_types'] = $this->ProductTypeModel->get_all_product_types();

        $this->load->view('admin/header', $data);
        $this->load->view('admin/products/index', $data);
        $this->load->view('admin/footer');
    }

    public function ajax_list()
    {
        $category_id = $this->input->post('category_id', true);
        $brand_id = $this->input->post('brand_id', true);
        $product_type_id = $this->input->post('product_type_id', true);
        $stock = $this->input->post('stock', true);
        $search_value = $this->input->post('search')['value'] ?? null;
        $start = $this->input->post('start', true);
        $length = $this->input->post('length', true);
        $draw = $this->input->post('draw', true);

        $result = $this->ProductModel->get_filtered_products($category_id, $brand_id, $product_type_id, $stock, $search_value, $start, $length);

        $data = [];
        $totalAmount = 0;
        $totalStock = 0;

        foreach ($result['data'] as $product) {
            $purchase_price = $product['purchase_price'];
            $total_quantity = $product['total_quantity'];
            $total_available_stocks = $product['total_available_stocks'];

            $totalAmount += $purchase_price * $total_available_stocks;
            $totalStock += $total_available_stocks;

            $actions = '<a href="' . base_url('admin/products/edit/' . $product['id']) . '" class="btn btn-warning btn-sm mr-1">Edit</a>';
            $actions .= '<a href="' . base_url('admin/products/delete/' . $product['id']) . '" class="btn btn-danger btn-sm mr-1" onclick="return confirm(\'Are you sure you want to delete this invoice?\');">Delete</a>';

            $endpoint = ($product['slug'] != "") ? $product['slug'] : $product['id'];

            $price_lists = '<p>MRP: ₹' . number_format($product['regular_price'], 2) . '</p>
                <p>Sale: ₹' . number_format($product['sale_price'], 2) . '</p>
                <p>Purchase:
                    <span class="purchase-price" data-product-id="' . $product['id'] . '" style="display: none;">
                        ₹' . number_format($purchase_price, 2) . '</span>
                    <a href="javascript:void(0);"
                        class="show_pp"
                        data-product-id="' . $product['id'] . '"
                        data-purchase-price="₹' . number_format($purchase_price, 2) . '">Show</a>
                </p>
                <a href="javascript:void(0);"
                    class="quick-edit"
                    data-product-id="' . $product['id'] . '"
                    data-product-name="' . $product['name'] . '">
                    <i class="fa fa-edit"></i> Quick Edit
                </a>';

            $stock_lists = 'In stocks: ' . $total_available_stocks . '<br>
                <a href="javascript:void(0);"
                    class="quick-stock-update"
                    data-product-id="' . $product['id'] . '"
                    data-product-name="' . $product['name'] . '"
                    data-total-purchased="' . $total_quantity . '"
                    data-current-stocks="' . $total_available_stocks . '">
                    <i class="fa fa-plus-circle"></i> Quick Stock Update
                </a>';

            $data[] = [
                $product['id'],
                'image',
                $product['name'],
                $product['category_name'],
                $product['brand_name'],
                $price_lists,
                $stock_lists,
                $actions,
            ];
        }

        echo json_encode([
            "draw" => intval($draw),
            "recordsTotal" => $result['recordsTotal'],
            "recordsFiltered" => $result['recordsFiltered'],
            "data" => $data,
            "totals" => [
                'total_amount' => '₹' . number_format($totalAmount, 2),
                'total_stock' => $totalStock
            ],
        ]);
    }

    public function add()
    {
        $data['activePage'] = 'products';
        $data['categories'] = $this->CategoryModel->get_all_categories();
        $data['brands'] = $this->BrandModel->get_all_brands();  // Get all brands
        $data['product_types'] = $this->ProductTypeModel->get_all_product_types();  // Get all brands
        $data['units'] = $this->UnitModel->get_all_units();  // Get all units

        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('category_id', 'Category', 'required');
        $this->form_validation->set_rules('brand_id', 'Brand', 'required');

        // Validation for MRP Price (regular_price)
        /* $this->form_validation->set_rules(
            'regular_price',
            'Regular Price',
            'required|numeric|greater_than[0]',
            ['greater_than' => 'The {field} must be greater than 0.']
        ); */

        // Validation for Sale Price
        $this->form_validation->set_rules(
            'sale_price',
            'Sale Price',
            'required|numeric|greater_than[0]',
            ['greater_than' => 'The {field} must be greater than 0.']
        );

        // Validation for Purchase Price
        $this->form_validation->set_rules(
            'purchase_price',
            'Purchase Price',
            'required|numeric|greater_than[0]',
            ['greater_than' => 'The {field} must be greater than 0.']
        );


        if ($this->form_validation->run() === FALSE) {
            $data['isUpdate'] = false;

            $this->load->view('admin/header', $data);
            $this->load->view('admin/products/add', $data);
            $this->load->view('admin/footer');
        } else {
            $upload_path = './uploads/products/';
            // Check if the folder exists, if not create it
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }

            $featured_image = '';
            $gallery_images_encoded = '';

            // Upload Featured Image
            if (!empty($_FILES['featured_image']['name'])) {
                $config['upload_path'] = $upload_path;
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['file_name'] = time() . '_' . $_FILES['featured_image']['name'];

                $this->upload->initialize($config);

                if ($this->upload->do_upload('featured_image')) {
                    $uploadData = $this->upload->data();
                    $featured_image = $uploadData['file_name'];
                }
            }

            // Upload Gallery Images
            $gallery_images = array();
            if (!empty($_FILES['gallery_images']['name'][0])) {
                $filesCount = count($_FILES['gallery_images']['name']);

                for ($i = 0; $i < $filesCount; $i++) {
                    $_FILES['file']['name'] = $_FILES['gallery_images']['name'][$i];
                    $_FILES['file']['type'] = $_FILES['gallery_images']['type'][$i];
                    $_FILES['file']['tmp_name'] = $_FILES['gallery_images']['tmp_name'][$i];
                    $_FILES['file']['error'] = $_FILES['gallery_images']['error'][$i];
                    $_FILES['file']['size'] = $_FILES['gallery_images']['size'][$i];

                    $config['file_name'] = time() . '_' . $_FILES['file']['name'];

                    $this->upload->initialize($config);

                    if ($this->upload->do_upload('file')) {
                        $uploadData = $this->upload->data();
                        $gallery_images[] = $uploadData['file_name'];
                    }
                }

                if (!empty($gallery_images)) {
                    $gallery_images_encoded = json_encode($gallery_images);
                }
            }

            $productData = array(
                'name' => $this->input->post('name'),
                'slug' => $this->input->post('slug'),
                'regular_price' => $this->input->post('regular_price'),
                'sale_price' => $this->input->post('sale_price'),
                'purchase_price' => $this->input->post('purchase_price'),
                'description' => $this->input->post('description'),
                'featured_image' => $featured_image,
                'gallery_images' => $gallery_images_encoded,
                'category_id' => $this->input->post('category_id'),
                'brand_id' => $this->input->post('brand_id'),
                'product_type_id' => $this->input->post('product_type_id'),
                'hsn_code' => $this->input->post('hsn_code'),
                'cgst' => $this->input->post('cgst'),
                'sgst' => $this->input->post('sgst'),
                'low_stock_alert' => $this->input->post('low_stock_alert'),
                'unit_id' => $this->input->post('unit_id')
            );

            $this->ProductModel->insert_product($productData);
            $this->session->set_flashdata('message', 'Product added successfully');
            redirect('admin/products');
        }
    }

    public function edit($id)
    {
        $data['activePage'] = 'products';
        $data['categories'] = $this->CategoryModel->get_all_categories();
        $data['brands'] = $this->BrandModel->get_all_brands(); // Get all brands
        $data['product_types'] = $this->ProductTypeModel->get_all_product_types();
        $data['product'] = $this->ProductModel->get_product($id);
        $data['units'] = $this->UnitModel->get_all_units();  // Get all units

        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('category_id', 'Category', 'required');
        $this->form_validation->set_rules('brand_id', 'Brand', 'required');

        // Validation for MRP Price (regular_price)
        /* $this->form_validation->set_rules(
            'regular_price',
            'Regular Price',
            'required|numeric|greater_than[0]',
            ['greater_than' => 'The {field} must be greater than 0.']
        ); */

        // Validation for Sale Price
        $this->form_validation->set_rules(
            'sale_price',
            'Sale Price',
            'required|numeric|greater_than[0]',
            ['greater_than' => 'The {field} must be greater than 0.']
        );

        // Validation for Purchase Price
        $this->form_validation->set_rules(
            'purchase_price',
            'Purchase Price',
            'required|numeric|greater_than[0]',
            ['greater_than' => 'The {field} must be greater than 0.']
        );

        if ($this->form_validation->run() === FALSE) {
            $data['isUpdate'] = true;

            // Load the views
            $this->load->view('admin/header', $data);
            $this->load->view('admin/products/add', $data);
            $this->load->view('admin/footer');
        } else {
            /* echo "<pre>";
            print_r($_FILES);
            die; */
            $upload_path = './uploads/products/';
            // Check if the folder exists, if not create it
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }
            // Handle file upload for featured image
            $featured_image = '';
            $gallery_images_encoded = '';

            if (!empty($_FILES['featured_image']['name'])) {
                $config['upload_path'] = $upload_path;
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['file_name'] = time() . '_' . $_FILES['featured_image']['name'];

                $this->upload->initialize($config);

                if ($this->upload->do_upload('featured_image')) {
                    $uploadData = $this->upload->data();
                    $featured_image = $uploadData['file_name'];
                }
            } else {
                // Keep the existing image if no new image is uploaded
                $featured_image = $this->input->post('existing_featured_image');
            }

            // Handle gallery images
            $gallery_images = array();
            if (!empty($_FILES['gallery_images']['name'][0])) {
                $filesCount = count($_FILES['gallery_images']['name']);
                for ($i = 0; $i < $filesCount; $i++) {
                    $_FILES['file']['name'] = $_FILES['gallery_images']['name'][$i];
                    $_FILES['file']['type'] = $_FILES['gallery_images']['type'][$i];
                    $_FILES['file']['tmp_name'] = $_FILES['gallery_images']['tmp_name'][$i];
                    $_FILES['file']['error'] = $_FILES['gallery_images']['error'][$i];
                    $_FILES['file']['size'] = $_FILES['gallery_images']['size'][$i];

                    $config['file_name'] = time() . '_' . $_FILES['file']['name'];
                    $config['upload_path'] = $upload_path;
                    $config['allowed_types'] = 'jpg|jpeg|png|gif';  // Allowed file types

                    $this->upload->initialize($config);

                    if ($this->upload->do_upload('file')) {
                        $uploadData = $this->upload->data();
                        $gallery_images[] = $uploadData['file_name'];
                    } else {
                        // Log the upload error
                        $error = $this->upload->display_errors();
                        echo "File upload failed: " . $error;
                    }
                }

                if (!empty($gallery_images)) {
                    $gallery_images_encoded = json_encode($gallery_images);
                }
            } else {
                $gallery_images_encoded = $data['product']['gallery_images'];
            }


            // Product data
            $productData = array(
                'name' => $this->input->post('name'),
                'slug' => $this->input->post('slug'),
                'regular_price' => $this->input->post('regular_price'),
                'sale_price' => $this->input->post('sale_price'),
                'purchase_price' => $this->input->post('purchase_price'),
                'description' => $this->input->post('description'),
                'category_id' => $this->input->post('category_id'),
                'brand_id' => $this->input->post('brand_id'),
                'product_type_id' => $this->input->post('product_type_id'),
                'featured_image' => $featured_image,
                'gallery_images' => $gallery_images_encoded,
                'hsn_code' => $this->input->post('hsn_code'),
                'cgst' => $this->input->post('cgst'),
                'sgst' => $this->input->post('sgst'),
                'low_stock_alert' => $this->input->post('low_stock_alert'),
                'unit_id' => $this->input->post('unit_id')
            );

            // Update the product
            $this->ProductModel->update_product($id, $productData);
            $this->session->set_flashdata('message', 'Product updated successfully');
            redirect('admin/products');
        }
    }

    public function addAjax()
    {

        $this->form_validation->set_rules('name', 'Product Name', 'required');
        $this->form_validation->set_rules('category_id', 'Category', 'required');
        $this->form_validation->set_rules('brand_id', 'Brand', 'required');

        // Validation for MRP Price (regular_price)
        /* $this->form_validation->set_rules(
            'mrp_price',
            'MRP Price',
            'required|numeric|greater_than[0]',
            ['greater_than' => 'The {field} must be greater than 0.']
        ); */

        // Validation for Sale Price
        $this->form_validation->set_rules(
            'sale_price',
            'Sale Price',
            'required|numeric|greater_than[0]',
            ['greater_than' => 'The {field} must be greater than 0.']
        );

        // Validation for Purchase Price
        $this->form_validation->set_rules(
            'purchase_price',
            'Purchase Price',
            'required|numeric|greater_than[0]',
            ['greater_than' => 'The {field} must be greater than 0.']
        );

        if ($this->form_validation->run() == FALSE) {
            $response = array('success' => false, 'errors' => validation_errors());
        } else {

            $productName = $this->input->post('name');
            $productSlug = $this->generateUniqueSlug($productName);
            $regular_price = $this->input->post('mrp_price');
            $sale_price = $this->input->post('sale_price');
            $purchase_price = $this->input->post('purchase_price');

            $data = array(
                'name' => $productName,
                'slug' => $productSlug,
                'regular_price' => $regular_price,
                'sale_price' => $sale_price,
                'purchase_price' => $purchase_price,
                'description' => $this->input->post('description'),
                'category_id' => $this->input->post('category_id'),
                'brand_id' => $this->input->post('brand_id')
            );

            $product_id = $this->ProductModel->insert_product($data);

            $response = array(
                'success' => true,
                'product' => array(
                    'id' => $product_id,
                    'name' => $this->input->post('name')
                )
            );
        }

        echo json_encode($response);
    }

    public function delete($id)
    {
        $this->ProductModel->delete_product($id);
        // Set flash message and redirect
        $this->session->set_flashdata('message', 'Product deleted successfully');
        redirect('admin/products');
    }

    public function search()
    {
        $term = $this->input->get('term');
        $products = $this->ProductModel->search_products($term);
        echo json_encode($products);
    }

    public function get_product_details()
    {
        $product_id = $this->input->post('product_id');
        $product_details = $this->ProductModel->get_product_details($product_id);
        if (!empty($product_details)) {
            $response = [
                'status' => 'success',
                'data' => $product_details
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'No details found.'
            ];
        }
        echo json_encode($response);
    }

    public function get_product_prices()
    {
        $product_id = $this->input->post('product_id');
        $prices = $this->ProductModel->get_product_prices($product_id);
        echo json_encode($prices);
    }

    public function get_last_purchase_price()
    {
        // Get product_id from AJAX request
        $product_id = $this->input->post('product_id');

        // Validate the input
        if (empty($product_id)) {
            echo json_encode(['status' => 'error', 'message' => 'Product ID is required']);
            return;
        }

        // Fetch the last purchase price using the model
        $last_purchase_price = $this->ProductModel->getLastPurchasePrice($product_id);

        // Check if a price was found
        if ($last_purchase_price !== null) {
            echo json_encode([
                'status' => 'success',
                'purchase_price' => number_format($last_purchase_price, 2)
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No purchase price found for this product']);
        }
    }

    public function update_price()
    {
        $product_id = $this->input->post('product_id');
        $mrp = $this->input->post('mrp');
        $sale_price = $this->input->post('sale_price');
        $purchase_price = $this->input->post('purchase_price');

        if (empty($product_id) || !is_numeric($mrp) || !is_numeric($sale_price) || !is_numeric($purchase_price)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
            return;
        }

        $data = [
            'regular_price' => $mrp,
            'sale_price' => $sale_price,
            'purchase_price' => $purchase_price,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $updateStatus = $this->ProductModel->updateProductPrice($product_id, $data);

        if ($updateStatus) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update product']);
        }
    }



    public function getProductsByCategory()
    {
        $categoryId = $this->input->post('category_id');
        $products = $this->ProductModel->getProductsByCategory($categoryId);

        if (!empty($products)) {
            echo '<div class="product-list-container">';
            echo '<ul class="product-list">';
            foreach ($products as $product) {
                echo '<li>' . $product['name'] . '</li>';
            }
            echo '</ul>';
            echo '</div>';
        } else {
            echo '<p>No products found for this category.</p>';
        }
    }

    public function bulkUpload()
    {
        $data['activePage'] = 'products';
        $this->load->view('admin/header', $data);
        $this->load->view('admin/products/bulk_upload', $data);
        $this->load->view('admin/footer');
    }

    public function processBulkUpload()
    {
        $config['upload_path'] = './uploads/csv/';
        $config['allowed_types'] = 'csv';
        $config['file_name'] = 'bulk_upload_' . time() . '.csv';

        // Check if the folder exists, if not create it
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
        }

        $this->upload->initialize($config);

        if ($this->upload->do_upload('csv_file')) {
            $uploadData = $this->upload->data();
            $filePath = $uploadData['full_path'];

            // Parse CSV
            $file = fopen($filePath, 'r');
            $header = fgetcsv($file); // First row is the header

            $products = [];
            while (($row = fgetcsv($file)) !== false) {
                $products[] = array_combine($header, $row);
            }
            fclose($file);
            $got_duplicate = 0;
            $product_update_count = 0;
            // Insert products into the database
            foreach ($products as $product) {
                $productName = trim($product['name']);
                $productSlug = $this->generateUniqueSlug($productName);

                $productData = [
                    'name' => $productName,
                    'slug' => $productSlug,
                    'hsn_code' => $product['hsn_code'] ?? '8471', // Default HSN code
                    'regular_price' => isset($product['sp']) ? (float)$product['sp'] * 1.5 : 150,
                    'sale_price' => $product['sp'] ?? 100,
                    'purchase_price' => $product['pp'] ?? 90,
                    'category_id' => $product['category_id'],
                    'brand_id' => $product['brand_id'],
                    'product_type_id' => 1
                ];

                // Check for duplicate product name
                $existingProduct = $this->ProductModel->get_product_by_name($productName);
                if ($existingProduct) {
                    $got_duplicate++;

                    if ($this->ProductModel->update_product_by_name($productName, $productData)) {
                        $product_update_count++;
                    }
                } else {
                    $this->ProductModel->insert_product($productData);
                }
            }
            $this->session->set_flashdata('duplicate', "Duplicate product name found:" . $got_duplicate);
            $this->session->set_flashdata('update', "Product updated:" . $product_update_count);
            $this->session->set_flashdata('message', 'Products uploaded successfully.');
            redirect('admin/products');
        } else {
            $this->session->set_flashdata('error', $this->upload->display_errors());
            redirect('admin/products/bulk-upload');
        }
    }

    /**
     * Generate a unique slug for the product name.
     *
     * @param string $productName
     * @return string
     */
    private function generateUniqueSlug($productName)
    {
        $slug = url_title($productName, '-', true); // Convert product name to a URL-friendly slug
        $originalSlug = $slug;
        $counter = 1;

        // Check if the slug already exists in the database
        while ($this->ProductModel->check_duplicate_slug($slug)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function update_stock()
    {
        $this->load->model('StockModel');
        $current_user_id = $this->session->userdata('user_id');
        $product_id = $this->input->post('product_id');
        $action = $this->input->post('action');
        $quantity = $this->input->post('quantity');
        $sale_price = $this->input->post('sale_price');
        $purchase_price = $this->input->post('purchase_price');

        // Validate inputs
        if (empty($product_id) || !in_array($action, ['increase', 'decrease']) || !is_numeric($quantity) || $quantity <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
            return;
        }

        // Determine quantity to add to `stock_management`
        $quantity = ($action === 'decrease') ? -$quantity : $quantity;
        $available_stock = ($action === 'decrease') ? 0 : $quantity;
        $purchase_date = date('Y-m-d');
        $data = [
            'product_id' => $product_id,
            'purchase_price' => $purchase_price,
            'sale_price' => $sale_price,
            'purchase_date' => $purchase_date,
            'quantity' => $quantity,
            'available_stock' => $available_stock,
            'created_by' => $current_user_id,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        // Insert into `stock_management` table
        $insertStatus = $this->StockModel->addStockRecord($data);

        if ($insertStatus) {

            $product_data = [
                'sale_price' => $sale_price,
                'purchase_price' => $purchase_price,
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $this->ProductModel->updateProductPrice($product_id, $product_data);

            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update stock']);
        }
    }
}
