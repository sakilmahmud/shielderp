<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProductModel extends CI_Model
{

    public function get_all_products($sort_by = 'products.id', $sort_order = 'DESC', $limit = null, $offset = null, $filters = [])
    {
        // Select fields including the joined category and brand names
        $this->db->select('products.*, categories.name as category_name, brands.brand_name');
        $this->db->from('products');
        $this->db->join('categories', 'products.category_id = categories.id', 'left');
        $this->db->join('brands', 'products.brand_id = brands.id', 'left');

        // Apply filters, if provided
        if (isset($filters['category_id'])) {
            $this->db->where('products.category_id', $filters['category_id']);
        }
        if (isset($filters['brand_id'])) {
            $this->db->where('products.brand_id', $filters['brand_id']);
        }
        if (isset($filters['min_price']) && isset($filters['max_price'])) {
            $this->db->where('products.price >=', $filters['min_price']);
            $this->db->where('products.price <=', $filters['max_price']);
        }

        // Sorting
        $sort_by = ($sort_by === 'price') ? 'sale_price' : $sort_by;
        $this->db->order_by($sort_by, $sort_order);

        // Apply pagination if limit and offset are provided
        if ($limit !== null && $offset !== null) {
            $this->db->limit($limit, $offset);
        } elseif ($limit !== null) {
            $this->db->limit($limit);
        }

        // Execute the query
        $query = $this->db->get();

        // Return the result as an array
        return $query->result_array();
    }

    public function get_filtered_products($category_id = null, $brand_id = null, $product_type_id = null, $stock = null, $search_value, $start, $length)
    {
        $this->db->select('products.*, categories.name as category_name, brands.brand_name, hsn_codes.hsn_code');
        $this->db->from('products');
        $this->db->join('categories', 'products.category_id = categories.id', 'left');
        $this->db->join('brands', 'products.brand_id = brands.id', 'left');
        $this->db->join('hsn_codes', 'products.hsn_code_id = hsn_codes.id', 'left');

        // Apply filters if set
        if ($category_id) {
            $this->db->where('products.category_id', $category_id);
        }
        if ($brand_id) {
            $this->db->where('products.brand_id', $brand_id);
        }
        if ($product_type_id) {
            $this->db->where('products.product_type_id', $product_type_id);
        }

        // Apply search filter
        if (!empty($search_value)) {
            $this->db->group_start();
            $this->db->like('products.name', $search_value);
            $this->db->or_like('products.description', $search_value);
            $this->db->or_like('categories.name', $search_value);
            $this->db->or_like('brands.brand_name', $search_value);
            $this->db->or_like('hsn_codes.hsn_code', $search_value);
            $this->db->group_end();
        }
        // Pagination
        if ($length != -1) {
            $this->db->limit($length, $start);
        }

        // Ordering
        $this->db->order_by('products.name', 'ASC');

        $query = $this->db->get();
        $products = $query->result_array();

        // Iterate over products and calculate stock data for each product
        foreach ($products as &$product) {
            $product_id = $product['id'];

            // Get the total product quantity from stock_management
            $this->db->select('SUM(quantity) as total_quantity');
            $this->db->from('stock_management');
            $this->db->where('product_id', $product_id);
            $product_query = $this->db->get();
            $product_data = $product_query->row_array();

            $total_quantity = $product_data['total_quantity'] ?? 0; // Total quantity added to stock

            // Get the total sold quantity from invoice_details
            $this->db->select('SUM(quantity) as sold_quantity');
            $this->db->from('invoice_details');
            $this->db->where('product_id', $product_id);
            $this->db->where('status', 1); // Optional: filter only active or completed invoices
            $sold_query = $this->db->get();
            $sold_data = $sold_query->row_array();

            $total_sold_quantity = $sold_data['sold_quantity'] ?? 0; // Total quantity sold

            // Calculate final stock
            $final_stock = $total_quantity - $total_sold_quantity;

            // Add stock data to the product array
            $product['total_quantity'] = $total_quantity;
            $product['total_sold_quantity'] = $total_sold_quantity;
            $product['total_available_stocks'] = $final_stock;
        }

        if ($stock) {
            //echo "stock filter works";
            // Apply stock filter after calculating stock
            if ($stock === 'positive') {
                $products = array_filter($products, function ($product) {
                    return $product['total_available_stocks'] > 0;
                });
            } elseif ($stock === 'zero') {
                $products = array_filter($products, function ($product) {
                    return $product['total_available_stocks'] == 0;
                });
            } elseif ($stock === 'negative') {
                $products = array_filter($products, function ($product) {
                    return $product['total_available_stocks'] < 0;
                });
            }

            // Re-index the array after filtering
            $products = array_values($products);
        } else {
            //echo "stock filter not works";
        }

        $filtered_count = count($products);

        /* print_r($products);
        die; */

        // Fetch filtered data count for DataTables
        $this->db->select('COUNT(DISTINCT products.id) as count');
        $this->db->from('products');
        $this->db->join('categories', 'products.category_id = categories.id', 'left');
        $this->db->join('brands', 'products.brand_id = brands.id', 'left');

        // Apply filters if set
        if ($category_id) {
            $this->db->where('products.category_id', $category_id);
        }
        if ($brand_id) {
            $this->db->where('products.brand_id', $brand_id);
        }
        if ($product_type_id) {
            $this->db->where('products.product_type_id', $product_type_id);
        }

        if (!empty($search_value)) {
            $this->db->group_start();
            $this->db->like('products.name', $search_value);
            $this->db->or_like('products.description', $search_value);
            $this->db->or_like('categories.name', $search_value);
            $this->db->or_like('brands.brand_name', $search_value);
            $this->db->group_end();
        }

        $count_query = $this->db->get();
        $count_result = $count_query->row_array();

        return [
            'data' => $products,
            'recordsTotal' => $count_result['count'],
            'recordsFiltered' => $filtered_count,
        ];
    }

    public function get_product($id)
    {
        $this->db->select('products.*, categories.name as category_name, brands.brand_name');
        $this->db->from('products');
        $this->db->join('categories', 'products.category_id = categories.id', 'left');
        $this->db->join('brands', 'products.brand_id = brands.id', 'left');
        $this->db->where('products.id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function get_product_details($product_id)
    {
        return $this->db
            ->select('p.*, u.symbol, h.hsn_code, h.gst_rate')
            ->from('products p')
            ->join('units u', 'p.unit_id = u.id', 'left')
            ->join('hsn_codes h', 'p.hsn_code_id = h.id', 'left')
            ->where('p.id', $product_id)
            ->get()
            ->row_array();
    }

    public function insert_product($data)
    {
        $this->db->insert('products', $data);
        return $this->db->insert_id();
    }

    public function update_product($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('products', $data);
    }

    public function delete_product($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('products');
    }

    public function search_products($term)
    {
        $this->db->select('id, name');
        $this->db->from('products');
        $this->db->like('name', $term);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_product_prices($product_id)
    {
        $this->db->select('regular_price, sale_price, purchase_price, hsn_code_id');
        $this->db->from('products');
        $this->db->where('id', $product_id);
        $this->db->order_by('updated_at', 'DESC'); // Assuming you have a timestamp or similar column
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $arr = array('prices' => $query->row());
            return $arr;
        } else {
            return 0; // Default price if none found
        }
    }

    public function updateProductPrice($product_id, $data)
    {
        $this->db->where('id', $product_id);
        return $this->db->update('products', $data);
    }

    public function getProductsByCategory($categoryId)
    {
        $this->db->where('category_id', $categoryId);
        $query = $this->db->get('products');
        return $query->result_array();
    }

    public function get_products($limit = 8, $order_by = 'created_at', $direction = 'DESC')
    {
        // Ensure the direction is either ASC, DESC, or RAND
        if (strtoupper($direction) === 'RAND') {
            $this->db->order_by('RAND()');
        } else {
            $this->db->order_by($order_by, strtoupper($direction));
        }

        // Limit the results
        $this->db->limit($limit);

        // Get products from the 'products' table
        $query = $this->db->get('products');

        // Return the result as an array
        return $query->result_array();
    }


    public function get_product_by_slug($slug)
    {
        // Query to fetch product details by slug
        $this->db->where('slug', $slug);
        $query = $this->db->get('products');

        // Return the product data if found, or false if not
        return $query->row_array();
    }

    public function count_all_products()
    {
        return $this->db->count_all('products');
    }

    public function count_products_by_category($category_id)
    {
        $this->db->where('category_id', $category_id);
        return $this->db->count_all_results('products');
    }

    public function get_products_by_category($category_id, $sort_by = 'name', $sort_order = 'asc', $limit = 9, $start = 0)
    {
        $sort_by = ($sort_by === 'price') ? 'sale_price' : $sort_by;

        $this->db->select('products.*, categories.name as category_name, brands.brand_name');
        $this->db->from('products');
        $this->db->join('categories', 'products.category_id = categories.id', 'left');
        $this->db->join('brands', 'products.brand_id = brands.id', 'left'); // Join with brands table
        $this->db->where('products.category_id', $category_id); // Filter by category
        $this->db->order_by($sort_by, $sort_order); // Apply sorting
        $this->db->limit($limit, $start); // Apply pagination

        $query = $this->db->get();
        $products = $query->result_array();

        // Iterate over products and calculate stock data for each product
        foreach ($products as &$product) {
            $product_id = $product['id'];

            // Get the total product quantity from stock_management
            $this->db->select('SUM(quantity) as total_quantity');
            $this->db->from('stock_management');
            $this->db->where('product_id', $product_id);
            $product_query = $this->db->get();
            $product_data = $product_query->row_array();

            $total_quantity = $product_data['total_quantity'] ?? 0; // Total quantity added to stock

            // Get the total sold quantity from invoice_details
            $this->db->select('SUM(quantity) as sold_quantity');
            $this->db->from('invoice_details');
            $this->db->where('product_id', $product_id);
            $this->db->where('status', 1); // Optional: filter only active or completed invoices
            $sold_query = $this->db->get();
            $sold_data = $sold_query->row_array();

            $total_sold_quantity = $sold_data['sold_quantity'] ?? 0; // Total quantity sold

            // Calculate final stock
            $final_stock = $total_quantity - $total_sold_quantity;

            // Add stock data to the product array
            $product['total_quantity'] = $total_quantity;
            $product['total_sold_quantity'] = $total_sold_quantity;
            $product['total_available_stocks'] = $final_stock;
        }
        return $products;
    }

    public function get_product_type_by_slug($slug)
    {
        $this->db->select('*');
        $this->db->from('product_types');
        $this->db->where('slug', $slug);
        $query = $this->db->get();

        return $query->row_array();  // Return the single row as an associative array
    }

    public function count_products_by_type($product_type_id)
    {
        $this->db->from('products');
        $this->db->where('product_type_id', $product_type_id);  // Assuming there's a product_type_id in the products table
        return $this->db->count_all_results();
    }

    public function get_products_by_type($product_type_id, $sort_by, $sort_order, $limit, $start)
    {
        $sort_by = ($sort_by === 'price') ? 'sale_price' : $sort_by;
        $this->db->select('products.*, categories.name as category_name, brands.brand_name');
        $this->db->from('products');
        $this->db->join('categories', 'products.category_id = categories.id', 'left');
        $this->db->join('brands', 'products.brand_id = brands.id', 'left');
        $this->db->where('products.product_type_id', $product_type_id);  // Assuming there's a product_type_id in the products table
        $this->db->order_by($sort_by, $sort_order);  // Sort by the selected column
        $this->db->limit($limit, $start);  // Apply pagination
        $query = $this->db->get();

        return $query->result_array();  // Return the result set as an array
    }

    public function check_duplicate_slug($slug)
    {
        $this->db->where('slug', $slug);
        $query = $this->db->get('products');
        return $query->num_rows() > 0;
    }

    public function get_product_by_name($productName)
    {
        $this->db->where('name', $productName);
        $query = $this->db->get('products');
        return $query->row();
    }

    public function update_product_by_name($name, $data)
    {
        $this->db->where('name', $name);
        return $this->db->update('products', $data);
    }

    public function get_by_name_slug($name, $slug)
    {
        return $this->db->get_where('products', ['name' => $name, 'slug' => $slug])->row_array();
    }

    public function get_all_products_with_names()
    {
        $this->db->select('p.*, h.hsn_code, c.name as category, b.brand_name as brand, u.name as unit');
        $this->db->from('products p');
        $this->db->join('hsn_codes h', 'h.id = p.hsn_code_id', 'left');
        $this->db->join('categories c', 'c.id = p.category_id', 'left');
        $this->db->join('brands b', 'b.id = p.brand_id', 'left');
        $this->db->join('units u', 'u.id = p.unit_id', 'left');
        return $this->db->get()->result_array();
    }
}
