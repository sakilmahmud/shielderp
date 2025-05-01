<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PurchaseEntryController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('PurchaseOrderModel');
        $this->load->model('SupplierModel');
        $this->load->model('ProductModel');
        $this->load->model('CategoryModel');
        $this->load->model('BrandModel');  // Load BrandModel
        $this->load->model('StockModel');
        $this->load->library('form_validation');

        if (!$this->session->userdata('username')) {
            redirect('login');
        }
    }

    public function index()
    {
        $data['activePage'] = 'purchase_entries';
        //$data['purchase_entries'] = $this->PurchaseOrderModel->get_all_purchase_orders();
        $data['suppliers'] = $this->SupplierModel->get_all_suppliers();
        $this->load->view('admin/header', $data);
        $this->load->view('admin/purchase_entries/index', $data);
        $this->load->view('admin/footer');
    }

    public function fetchPurchases()
    {
        $from_date = $this->input->post('from_date', true);
        $to_date = $this->input->post('to_date', true);
        $payment_status = $this->input->post('payment_status', true);
        $type = $this->input->post('type', true);
        $supplier_id = $this->input->post('supplier_id', true);
        $search_value = $this->input->post('search')['value'] ?? null;
        $start = $this->input->post('start', true);
        $length = $this->input->post('length', true);
        $draw = $this->input->post('draw', true);

        if (empty($from_date)) {
            $from_date = date('Y-m-d', strtotime('-30 days'));
        }
        if (empty($to_date)) {
            $to_date = date('Y-m-d');
        }

        $result = $this->PurchaseOrderModel->getFilteredPurchases(
            $from_date,
            $to_date,
            $payment_status,
            $type,
            $supplier_id,
            $search_value,
            $start,
            $length
        );

        $data = [];
        $totalAmount = 0;
        foreach ($result['data'] as $invoice) {
            $totalAmount += $invoice['total_amount'];

            $actions = '<a href="' . base_url('admin/purchase_entries/edit/' . $invoice['id']) . '" class="btn btn-warning btn-sm mr-1">Edit</a>';
            $actions .= '<a href="' . base_url('admin/purchase_entries/delete/' . $invoice['id']) . '" class="btn btn-danger btn-sm mr-1" onclick="return confirm(\'Are you sure you want to delete this purchase entry?\');">Delete</a>';

            /* $status_badge = match ($invoice['payment_status']) {
                '1' => '<span class="badge badge-success">Paid</span>',
                '0' => '<span class="badge badge-warning">Pending</span>',
                '2' => '<span class="badge badge-info">Partial</span>',
                '3' => '<span class="badge badge-danger">Return</span>',
                default => '<span class="badge badge-secondary">Unknown</span>',
            }; */

            $data[] = [
                $invoice['id'],
                $invoice['supplier_name'],
                date('d-m-Y', strtotime($invoice['purchase_date'])),
                $invoice['invoice_no'],
                '₹' . number_format($invoice['total_amount'], 2),
                $actions,
            ];
        }

        echo json_encode([
            "draw" => intval($draw),
            "recordsTotal" => $result['recordsTotal'],
            "recordsFiltered" => $result['recordsFiltered'],
            "data" => $data,
            "totals" => [
                'total_amount' => '₹' . number_format($totalAmount, 2)
            ],
        ]);
    }

    public function add()
    {
        $data['activePage'] = 'purchase_entries';
        $data['suppliers'] = $this->SupplierModel->get_all_suppliers();
        $data['products'] = $this->ProductModel->get_all_products();
        $data['categories'] = $this->CategoryModel->get_all_categories();
        $data['brands'] = $this->BrandModel->get_all_brands();  // Get all brands

        $this->form_validation->set_rules('supplier_id', 'Supplier', 'required');
        $this->form_validation->set_rules('purchase_date', 'Purchase Date', 'required');
        $this->form_validation->set_rules('invoice_no', 'Invoice Number', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('admin/header', $data);
            $this->load->view('admin/purchase_entries/add', $data);
            $this->load->view('admin/footer');
        } else {
            /* echo "<pre>";
            print_r($_POST);
            die; */
            if ($this->input->post('total_amount') < 1) {

                $this->session->set_flashdata('message', 'Please add some products');
                $this->load->view('admin/header', $data);
                $this->load->view('admin/purchase_entries/add', $data);
                $this->load->view('admin/footer');

                return;
            }
            $purchaseOrderData = array(
                'purchase_date' => $this->input->post('purchase_date'),
                'invoice_no' => $this->input->post('invoice_no'),
                'supplier_id' => $this->input->post('supplier_id'),
                'is_gst' => $this->input->post('is_gst'),
                'sub_total' => $this->input->post('sub_total'),
                'total_discount' => $this->input->post('total_discount'),
                'total_gst' => $this->input->post('total_gst'),
                'total_amount' => $this->input->post('total_amount')
            );

            // Insert the purchase order
            $purchaseOrderId = $this->PurchaseOrderModel->insert_purchase_order($purchaseOrderData);

            // Handle products
            $product_ids = $this->input->post('product_id');
            $qnt = $this->input->post('qnt');
            $purchase_prices = $this->input->post('purchase_price');
            $discount_types = $this->input->post('discount_type');
            $discounts = $this->input->post('discount');
            $gst_rates = $this->input->post('gst_rate');
            $gst_amounts = $this->input->post('gst_amount');
            $final_prices = $this->input->post('final_price');
            $single_net_price = $this->input->post('single_net_price');
            $sale_price = $this->input->post('sale_price');

            foreach ($product_ids as $key => $product_id) {
                if ($product_id) {
                    $productData = array(
                        'purchase_order_id' => $purchaseOrderId,
                        'supplier_id' => $this->input->post('supplier_id'),
                        'product_id' => $product_id,
                        'qnt' => $qnt[$key],
                        'purchase_price' => $purchase_prices[$key],
                        'discount_type' => $discount_types[$key],
                        'discount' => $discounts[$key],
                        'gst_rate' => $gst_rates[$key],
                        'gst_amount' => $gst_amounts[$key],
                        'final_price' => $final_prices[$key],
                        'single_net_price' => $single_net_price[$key],
                        'sale_price' => $sale_price[$key]
                    );

                    $this->PurchaseOrderModel->insert_purchase_order_product($productData);

                    $stockData = array(
                        'product_id' => $product_id,
                        'purchase_price' => $single_net_price[$key],
                        'sale_price' => $sale_price[$key],
                        'purchase_date' => $this->input->post('purchase_date'),
                        'quantity' => $qnt[$key],
                        'available_stock' => $qnt[$key],
                        'purchase_order_id' => $purchaseOrderId,
                        'supplier_id' => $this->input->post('supplier_id'),
                        'batch_no' => $this->input->post('invoice_no'),
                    );

                    $this->StockModel->insert_stock($stockData);
                }
            }

            $this->session->set_flashdata('message', 'Purchase order added successfully');
            redirect('admin/purchase_entries');
        }
    }

    public function edit($id)
    {
        $data['activePage'] = 'purchase_entries';
        $data['suppliers'] = $this->SupplierModel->get_all_suppliers();
        $data['products'] = $this->ProductModel->get_all_products();
        $data['categories'] = $this->CategoryModel->get_all_categories();
        $data['brands'] = $this->BrandModel->get_all_brands();  // Get all brands
        $data['purchase_entry'] = $this->PurchaseOrderModel->get_purchase_order($id);
        $data['purchase_order_products'] = $this->PurchaseOrderModel->get_purchase_order_products($id);



        $this->form_validation->set_rules('supplier_id', 'Supplier', 'required');
        $this->form_validation->set_rules('purchase_date', 'Purchase Date', 'required');
        $this->form_validation->set_rules('invoice_no', 'Invoice Number', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('admin/header', $data);
            $this->load->view('admin/purchase_entries/edit', $data);
            $this->load->view('admin/footer');
        } else {

            /* echo "<pre>";
            print_r($_POST);
            die; */

            if ($this->input->post('total_amount') < 1) {
                $this->session->set_flashdata('message', 'Please add some products');
                $this->load->view('admin/header', $data);
                $this->load->view('admin/purchase_entries/edit', $data);
                $this->load->view('admin/footer');
                return;
            }

            // Update the purchase order
            $purchaseOrderData = array(
                'purchase_date' => $this->input->post('purchase_date'),
                'invoice_no' => $this->input->post('invoice_no'),
                'supplier_id' => $this->input->post('supplier_id'),
                'is_gst' => $this->input->post('is_gst'),
                'sub_total' => $this->input->post('sub_total'),
                'total_discount' => $this->input->post('total_discount'),
                'total_gst' => $this->input->post('total_gst'),
                'total_amount' => $this->input->post('total_amount')
            );

            $this->PurchaseOrderModel->update_purchase_order($id, $purchaseOrderData);

            // Handle products
            $product_ids = $this->input->post('product_id');
            $qnt = $this->input->post('qnt');
            $purchase_prices = $this->input->post('purchase_price');
            $discount_types = $this->input->post('discount_type');
            $discounts = $this->input->post('discount');
            $gst_rates = $this->input->post('gst_rate');
            $gst_amounts = $this->input->post('gst_amount');
            $final_prices = $this->input->post('final_price');
            $single_net_price = $this->input->post('single_net_price');
            $sale_price = $this->input->post('sale_price');

            // Delete existing products linked to this order
            $this->PurchaseOrderModel->delete_purchase_order_products($id);
            $this->PurchaseOrderModel->delete_stocks($id);

            foreach ($product_ids as $key => $product_id) {
                if ($product_id) {
                    $productData = array(
                        'purchase_order_id' => $id,
                        'supplier_id' => $this->input->post('supplier_id'),
                        'product_id' => $product_id,
                        'qnt' => $qnt[$key],
                        'purchase_price' => $purchase_prices[$key],
                        'discount_type' => $discount_types[$key],
                        'discount' => $discounts[$key],
                        'gst_rate' => $gst_rates[$key],
                        'gst_amount' => $gst_amounts[$key],
                        'final_price' => $final_prices[$key],
                        'single_net_price' => $single_net_price[$key],
                        'sale_price' => $sale_price[$key]
                    );

                    $this->PurchaseOrderModel->insert_purchase_order_product($productData);

                    // Update stock
                    $stockData = array(
                        'product_id' => $product_id,
                        'purchase_price' => $single_net_price[$key],
                        'sale_price' => $sale_price[$key],
                        'purchase_date' => $this->input->post('purchase_date'),
                        'quantity' => $qnt[$key],
                        'available_stock' => $qnt[$key],
                        'purchase_order_id' => $id,
                        'supplier_id' => $this->input->post('supplier_id'),
                        'batch_no' => $this->input->post('invoice_no'),
                    );

                    $this->StockModel->insert_stock($stockData);
                }
            }

            $this->session->set_flashdata('message', 'Purchase order updated successfully');
            redirect('admin/purchase_entries');
        }
    }

    public function delete($id)
    {
        // Delete associated products from purchase_order_products
        $this->PurchaseOrderModel->delete_purchase_order_products($id);

        // Delete the purchase order
        $this->PurchaseOrderModel->delete_purchase_order($id);


        // Delete from stock_management table
        $this->PurchaseOrderModel->delete_stocks($id);

        // Set success message and redirect
        $this->session->set_flashdata('message', 'Purchase order deleted successfully');
        redirect('admin/purchase_entries');
    }
}
