<?php
defined('BASEPATH') or exit('No direct script access allowed');

class StockManagementController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('StockModel');
        $this->load->model('ProductModel'); // Assuming this model already exists
        $this->load->model('CategoryModel');
        $this->load->model('BrandModel');
        $this->load->library('form_validation');

        if (!$this->session->userdata('username')) {
            redirect('login');
        }
    }

    public function index()
    {
        $data['activePage'] = 'stocks';
        //$data['stocks'] = $this->StockModel->get_all_stocks();

        // Fetch all stocks
        $filters = $this->input->get(); // Get filter values from the request
        $data['stocks'] = $this->StockModel->get_filtered_stocks($filters);

        // Fetch categories and brands for filters
        $data['categories'] = $this->CategoryModel->get_all_categories();
        $data['brands'] = $this->BrandModel->get_all_brands();

        $this->render_admin('admin/stocks/index', $data);
    }

    public function add()
    {
        $data['activePage'] = 'stocks';
        $data['products'] = $this->ProductModel->get_all_products();

        $current_user_id = $this->session->userdata('user_id');

        $this->form_validation->set_rules('product_id', 'Product', 'required');
        $this->form_validation->set_rules('purchase_price', 'Purchase Price', 'required');
        $this->form_validation->set_rules('sale_price', 'Sale Price', 'required');
        $this->form_validation->set_rules('purchase_date', 'Purchase Date', 'required');
        $this->form_validation->set_rules('quantity', 'Quantity', 'required');

        if ($this->form_validation->run() === FALSE) {

            $this->render_admin('admin/stocks/add', $data);
        } else {
            $stockData = array(
                'product_id' => $this->input->post('product_id'),
                'purchase_price' => $this->input->post('purchase_price'),
                'sale_price' => $this->input->post('sale_price'),
                'purchase_date' => $this->input->post('purchase_date'),
                'quantity' => $this->input->post('quantity'),
                'available_stock' => $this->input->post('quantity'),
                'batch_no' => $this->input->post('batch_no'),
                'created_by' => $current_user_id
            );

            $this->StockModel->insert_stock($stockData);
            $this->session->set_flashdata('message', 'Stock added successfully');
            redirect('admin/stocks');
        }
    }
    public function edit($id)
    {
        $data['activePage'] = 'stocks';
        $data['products'] = $this->ProductModel->get_all_products();
        $data['stock'] = $this->StockModel->get_stock($id);

        $current_user_id = $this->session->userdata('user_id');

        if (empty($data['stock'])) {
            show_404();
        }

        $this->form_validation->set_rules('product_id', 'Product', 'required');
        $this->form_validation->set_rules('purchase_price', 'Purchase Price', 'required');
        $this->form_validation->set_rules('sale_price', 'Sale Price', 'required');
        $this->form_validation->set_rules('purchase_date', 'Purchase Date', 'required');
        $this->form_validation->set_rules('quantity', 'Quantity', 'required');

        if ($this->form_validation->run() === FALSE) {

            $this->render_admin('admin/stocks/edit', $data);
        } else {

            $stockData = array(
                'product_id' => $this->input->post('product_id'),
                'purchase_price' => $this->input->post('purchase_price'),
                'sale_price' => $this->input->post('sale_price'),
                'purchase_date' => $this->input->post('purchase_date'),
                'quantity' => $this->input->post('quantity'),
                'available_stock' => $this->input->post('quantity'),
                'batch_no' => $this->input->post('batch_no'),
                'created_by' => $current_user_id
            );

            $this->StockModel->update_stock($id, $stockData);
            $this->session->set_flashdata('message', 'Stock updated successfully');
            redirect('admin/stocks');
        }
    }
    public function delete($id)
    {
        if ($this->StockModel->delete_stock($id)) {
            $this->session->set_flashdata('message', 'Stock deleted successfully');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete stock');
        }
        redirect('admin/stocks');
    }
}
