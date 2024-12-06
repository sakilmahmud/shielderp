<?php
defined('BASEPATH') or exit('No direct script access allowed');

class StockManagementController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('StockModel');
        $this->load->model('ProductModel'); // Assuming this model already exists
        $this->load->library('form_validation');

        if (!$this->session->userdata('username')) {
            redirect('login');
        }
    }

    public function index()
    {
        $data['activePage'] = 'stocks';
        $data['stocks'] = $this->StockModel->get_all_stocks();
        $this->load->view('admin/header', $data);
        $this->load->view('admin/stocks/index', $data);
        $this->load->view('admin/footer');
    }

    public function add()
    {
        $data['activePage'] = 'stocks';
        $data['products'] = $this->ProductModel->get_all_products();

        $this->form_validation->set_rules('product_id', 'Product', 'required');
        $this->form_validation->set_rules('purchase_price', 'Purchase Price', 'required');
        $this->form_validation->set_rules('sale_price', 'Sale Price', 'required');
        $this->form_validation->set_rules('purchase_date', 'Purchase Date', 'required');
        $this->form_validation->set_rules('quantity', 'Quantity', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('admin/header', $data);
            $this->load->view('admin/stocks/add', $data);
            $this->load->view('admin/footer');
        } else {
            $stockData = array(
                'product_id' => $this->input->post('product_id'),
                'purchase_price' => $this->input->post('purchase_price'),
                'sale_price' => $this->input->post('sale_price'),
                'purchase_date' => $this->input->post('purchase_date'),
                'quantity' => $this->input->post('quantity'),
                'available_stock' => $this->input->post('quantity'),
                'batch_no' => $this->input->post('batch_no')
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

        if (empty($data['stock'])) {
            show_404();
        }

        $this->form_validation->set_rules('product_id', 'Product', 'required');
        $this->form_validation->set_rules('purchase_price', 'Purchase Price', 'required');
        $this->form_validation->set_rules('sale_price', 'Sale Price', 'required');
        $this->form_validation->set_rules('purchase_date', 'Purchase Date', 'required');
        $this->form_validation->set_rules('quantity', 'Quantity', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('admin/header', $data);
            $this->load->view('admin/stocks/edit', $data);
            $this->load->view('admin/footer');
        } else {
            $stockData = array(
                'product_id' => $this->input->post('product_id'),
                'purchase_price' => $this->input->post('purchase_price'),
                'sale_price' => $this->input->post('sale_price'),
                'purchase_date' => $this->input->post('purchase_date'),
                'quantity' => $this->input->post('quantity'),
                'available_stock' => $this->input->post('quantity'),
                'batch_no' => $this->input->post('batch_no')
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
