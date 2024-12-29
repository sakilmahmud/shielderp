<?php
class StockModel extends CI_Model
{
    public function get_all_stocks()
    {
        $this->db->select('stock_management.*, products.name as product_name, suppliers.supplier_name, categories.name as cat_name, brands.brand_name');
        $this->db->from('stock_management');
        $this->db->join('products', 'stock_management.product_id = products.id');
        $this->db->join('categories', 'categories.id = products.category_id');
        $this->db->join('brands', 'brands.id = products.brand_id');
        $this->db->join('suppliers', 'stock_management.supplier_id = suppliers.id', 'left');
        $this->db->order_by('stock_management.id', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_filtered_stocks($filters)
    {
        $this->db->select('stock_management.*, products.name as product_name, suppliers.supplier_name, categories.name as cat_name, brands.brand_name');
        $this->db->from('stock_management');
        $this->db->join('products', 'stock_management.product_id = products.id');
        $this->db->join('categories', 'categories.id = products.category_id');
        $this->db->join('brands', 'brands.id = products.brand_id');
        $this->db->join('suppliers', 'stock_management.supplier_id = suppliers.id', 'left');

        // Apply filters
        if (!empty($filters['category_id'])) {
            $this->db->where('categories.id', $filters['category_id']);
        }
        if (!empty($filters['brand_id'])) {
            $this->db->where('brands.id', $filters['brand_id']);
        }

        $this->db->order_by('stock_management.id', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function insert_stock($data)
    {
        return $this->db->insert('stock_management', $data);
    }

    public function get_stock($id)
    {
        $this->db->select('stock_management.*, products.name as product_name');
        $this->db->from('stock_management');
        $this->db->join('products', 'stock_management.product_id = products.id');
        $this->db->where('stock_management.id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function update_stock($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('stock_management', $data);
    }

    public function update_stock_purchase_update($purchase_order_id, $product_id, $stockData)
    {
        $this->db->where('purchase_order_id', $purchase_order_id);
        $this->db->where('product_id', $product_id);
        $this->db->update('stock_management', $stockData);

        return $this->db->affected_rows(); // Returns the number of rows affected by the update
    }

    public function delete_stock($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('stock_management');
    }

    public function get_lastest_stocks($product_id)
    {
        $this->db->select('sm.purchase_price, sm.sale_price, sm.purchase_date, sm.available_stock, s.supplier_name, sm.batch_no');
        $this->db->from('stock_management sm');
        $this->db->join('suppliers s', 's.id = sm.supplier_id', 'left');
        $this->db->where('sm.product_id', $product_id);
        $this->db->where('sm.available_stock >', 0);  // Add this condition
        $this->db->order_by('sm.purchase_date', 'DESC');
        $this->db->limit(5);

        //echo $this->db->get_compiled_select();


        $query = $this->db->get();
        return $query->result_array();
    }

    public function update_stock_on_delete($product_id, $quantity)
    {
        // Reduce the available stock by the quantity of the deleted purchase order
        $this->db->set('available_stock', 'available_stock - ' . (int) $quantity, FALSE);
        $this->db->where('product_id', $product_id);
        $this->db->update('stock_management');
    }

    public function reduceStock($product_id, $quantity)
    {
        // Fetch the current available stock for the given product
        $this->db->select('id, available_stock');
        $this->db->from('stock_management');
        $this->db->where('product_id', $product_id);
        $this->db->where('available_stock >', 0);  // Add this condition
        $this->db->order_by('purchase_date', 'ASC');
        $this->db->limit(1);
        $currentStock = $this->db->get()->row();
        $stock_id = $currentStock->id;
        $oldAvailableStock = $currentStock->available_stock;

        // Calculate the new stock value
        $newStock = $oldAvailableStock - $quantity;

        // Update the available stock in the database
        $this->db->where('id', $stock_id);
        $this->db->update('stock_management', ['available_stock' => $newStock]);
    }

    public function addStockRecord($data)
    {
        return $this->db->insert('stock_management', $data);
    }
}
