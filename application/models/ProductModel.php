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
    public function get_last_price($product_id)
    {
        $this->db->select('price');
        $this->db->from('products');
        $this->db->where('id', $product_id);
        $this->db->order_by('updated_at', 'DESC'); // Assuming you have a timestamp or similar column
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row()->price;
        } else {
            return 0; // Default price if none found
        }
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
        return $query->result_array();
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
}
