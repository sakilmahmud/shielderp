<?php
defined('BASEPATH') or exit('No direct script access allowed');

class InvoiceController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Load any required models, helpers, libraries, etc.
        $this->load->model('UserModel');
        $this->load->model('InvoiceModel');
        $this->load->model('ProductModel');
        $this->load->model('CustomerModel');
        $this->load->model('StockModel');
        $this->load->model('PaymentMethodsModel');
        $this->load->model('TransactionModel');
        $this->load->library('form_validation');

        if (!$this->session->userdata('user_id')) {
            redirect('login');
        }
    }

    public function index()
    {
        $data['activePage'] = 'invoices';
        $data['users'] = $this->UserModel->getUsers(array(1, 2));

        $this->render_admin('admin/invoices/index', $data);
    }

    public function fetchInvoices()
    {
        $from_date = $this->input->post('from_date', true);
        $to_date = $this->input->post('to_date', true);
        $type = $this->input->post('type', true);
        $payment_status = $this->input->post('payment_status', true);
        $created_by = $this->input->post('created_by', true);
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

        $result = $this->InvoiceModel->getFilteredInvoices(
            $from_date,
            $to_date,
            $payment_status,
            $type,
            $created_by,
            $search_value,
            $start,
            $length
        );

        $data = [];
        $totalAmount = 0;
        $totalPaid = 0;
        $totalDue = 0;

        foreach ($result['data'] as $invoice) {
            $totalAmount += $invoice['total_amount'];
            $totalPaid += $invoice['paid_amount'];
            $totalDue += $invoice['due_amount'];

            $actions = '<a href="' . base_url('admin/invoices/view/' . $invoice['id']) . '" class="btn btn-info btn-sm me-1">View</a>';
            $actions .= '<a href="' . base_url('admin/invoices/edit/' . $invoice['id']) . '" class="btn btn-warning btn-sm me-1">Edit</a>';
            if ($this->session->userdata('role') == 1) {
                $actions .= '<a href="' . base_url('admin/invoices/delete/' . $invoice['id']) . '" class="btn btn-danger btn-sm me-1" onclick="return confirm(\'Are you sure you want to delete this invoice?\');">Delete</a>';
            }
            $actions .= '<a href="' . base_url('admin/invoices/print/' . $invoice['id']) . '" target="_blank" class="btn btn-primary btn-sm">Print</a>';

            $status_badge = match ($invoice['payment_status']) {
                '1' => '<span class="badge text-bg-success">Paid</span>',
                '0' => '<span class="badge text-bg-warning">Pending</span>',
                '2' => '<span class="badge text-bg-info">Partial</span>',
                '3' => '<span class="badge text-bg-danger">Return</span>',
                default => '<span class="badge badge-secondary">Unknown</span>',
            };
            $customer_link = ($invoice['customer_id'] != 5)
                ? '<a href="' . base_url('admin/customers/show/' . $invoice['customer_id']) . '" class="text-dark">' . $invoice['customer_name'] . '</a>'
                : $invoice['customer_name'];

            $data[] = [
                $invoice['id'],
                $status_badge,
                ($invoice['is_gst']) ? 'GST' : 'NON-GST',
                $invoice['invoice_no'],
                $customer_link . '<br>' . $invoice['mobile'],
                date('d-m-Y', strtotime($invoice['invoice_date'])),
                '₹' . number_format($invoice['total_amount'], 2),
                '₹' . number_format($invoice['paid_amount'], 2),
                '₹' . number_format($invoice['due_amount'], 2),
                //$invoice['created_by_name'],
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
                'total_paid' => '₹' . number_format($totalPaid, 2),
                'total_due' => '₹' . number_format($totalDue, 2),
            ],
        ]);
    }

    public function createInvoice()
    {
        $data['activePage'] = 'invoices';
        $data['is_gst_bill'] = getSetting('is_gst_bill');
        $default_gst_rate = getSetting('default_gst_rate');
        $data['gst_rate'] = isset($default_gst_rate) ? $default_gst_rate : 0;
        // Load necessary data for the view
        $data['products'] = $this->ProductModel->get_all_products();
        $data['customers'] = $this->CustomerModel->get_all_customers();

        $data['paymentModes'] = $this->PaymentMethodsModel->getAll();
        /* print_r($data['paymentModes']);
        die; */
        $this->form_validation->set_rules('customer_id', 'Customer', 'required');
        $this->form_validation->set_rules('invoice_date', 'Invoice Date', 'required');
        $this->form_validation->set_rules('product_id[]', 'Product', 'required');

        if ($this->form_validation->run() === FALSE) {

            // Load the create invoice view

            $this->render_admin('admin/invoices/create', $data);
        } else {
            /* echo "<pre>";
            print_r($_POST);
            die; */
            $current_date_time = date('Y-m-d H:i:s');
            $is_gst = $this->input->post('is_gst');
            $invoice_no = $this->generate_invoice_no($is_gst);
            $total_amount = $this->input->post('total_amount');

            // Customer Data
            $customer_name = $this->input->post('customer_name');
            $customer_phone = $this->input->post('mobile');
            $customer_address = $this->input->post('address');
            $customer_gst = $this->input->post('gst');

            // Check if customer exists by mobile number
            $customer = $this->CustomerModel->get_by_phone($customer_phone); // Assuming this method exists in CustomerModel

            if ($customer) {
                // Customer exists, update the record
                $customer_id = $customer['id'];
                $customerData = [
                    'customer_name' => $customer_name,
                    'phone' => $customer_phone,
                    'address' => $customer_address,
                    'gst_number' => $customer_gst,
                    'updated_at' => $current_date_time
                ];
                $this->db->where('id', $customer_id);
                $this->db->update('customers', $customerData);
            } else {
                // Customer doesn't exist, insert a new record
                $customerData = [
                    'customer_name' => $customer_name,
                    'phone' => $customer_phone,
                    'address' => $customer_address,
                    'gst_number' => $customer_gst,
                    'created_at' => $current_date_time
                ];
                $this->db->insert('customers', $customerData);
                $customer_id = $this->db->insert_id();
            }
            $invoice_date = $this->input->post('invoice_date');
            // Prepare invoice data
            $sub_total = $this->input->post('sub_total');
            $total_discount = $this->input->post('total_discount');
            $taxable_value = $sub_total - $total_discount;

            // Prepare invoice data
            $invoiceData = [
                'invoice_no' => $invoice_no,
                'invoice_date' => $invoice_date,
                'due_date' => $invoice_date,
                'customer_id' => $customer_id, // Use the correct customer ID
                'is_gst' => $is_gst,
                'sub_total' => $sub_total,
                'total_discount' => $total_discount,
                'total_gst' => $this->input->post('total_gst'),
                'round_off' => $this->input->post('round_off'),
                'total_amount' => $total_amount,
                'taxable_value' => $taxable_value,
                'adjustment_balance' => 0.00,
                'customer_name' => $customer_name,
                'mobile' => $customer_phone,
                'address' => $customer_address,
                'note' => $this->input->post('note'),
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => $current_date_time
            ];

            // Insert invoice data into 'invoices' table
            $this->db->insert('invoices', $invoiceData);
            $invoice_id = $this->db->insert_id();

            // Insert into 'log_invoices' table
            $logInvoiceData = [
                'invoice_id' => $invoice_id,
                'invoice_data' => json_encode($invoiceData),
                'action' => 1, // Create action
                'made_by' => $this->session->userdata('user_id'),
                'device_data' => $this->input->user_agent(),
                'ip_address' => $this->input->ip_address(),
                'created_at' => $current_date_time
            ];
            $this->db->insert('log_invoices', $logInvoiceData);

            // Insert invoice details data
            $invoiceDetailsData = [];
            $product_ids = $this->input->post('product_id');
            $product_descriptions = $this->input->post('product_descriptions');
            $quantities = $this->input->post('qnt');
            $purchase_prices = $this->input->post('purchase_price');
            $discount_types = $this->input->post('discount_type');
            $discounts = $this->input->post('discount');
            $gst_rates = $this->input->post('gst_rate');

            $gst_amounts = $this->input->post('gst_amount');
            $final_prices = $this->input->post('final_price');

            foreach ($product_ids as $index => $product_id) {
                $gst_rate = $gst_rates[$index];
                $cgst = $gst_rate / 2;
                $sgst = $gst_rate / 2;

                $invoiceDetailsData[] = [
                    'invoice_id' => $invoice_id,
                    'customer_id' => $this->input->post('customer_id'),
                    'product_id' => $product_id,
                    'product_descriptions' => $product_descriptions[$index],
                    'quantity' => $quantities[$index],
                    'price' => $purchase_prices[$index],
                    'discount_type' => $discount_types[$index],
                    'discount' => $discounts[$index],
                    'cgst' => $cgst,
                    'sgst' => $sgst,
                    'gst_amount' => $gst_amounts[$index],
                    'final_price' => $final_prices[$index],
                    'invoice_date' => $invoice_date,
                    'created_at' => $current_date_time
                ];
                // Reduce the stock for each product
                //$this->StockModel->reduceStock($product_id, $quantities[$index]);
            }

            $this->db->insert_batch('invoice_details', $invoiceDetailsData);

            // Check if payment_mode is set and insert payment data
            $payment_modes = $this->input->post('payment_mode');
            $payment_amounts = $this->input->post('payment_amount');
            $payment_dates = $this->input->post('payment_date');
            if (!empty($payment_modes)) {
                $paid_amount = 0;
                foreach ($payment_modes as $index => $payment_mode) {
                    if (!empty($payment_mode)) {
                        $this_paid_amount = (float)$payment_amounts[$index] ?? 0;

                        $paid_amount += $this_paid_amount;

                        // Insert into 'transactions' table
                        $transactionData = [
                            'amount' => $this_paid_amount,
                            'trans_type' => 1, // Credit
                            'payment_method_id' => $payment_mode,
                            'descriptions' => 'Payment for Invoice #' . $invoice_no,
                            'transaction_for_table' => 'invoices',
                            'table_id' => $invoice_id,
                            'trans_by' => $this->session->userdata('user_id'),
                            'trans_date' => $payment_dates[$index],
                            'created_at' => $current_date_time
                        ];
                        $this->db->insert('transactions', $transactionData);
                        $transaction_id = $this->db->insert_id();

                        $paymentData = [
                            'invoice_id' => $invoice_id,
                            'invoice_no' => $invoice_no,
                            'customer_id' => $this->input->post('customer_id'),
                            'amount_paid' => $payment_amounts[$index],
                            'payment_mode' => $payment_mode,
                            'payment_date' => $payment_dates[$index],
                            'made_by' => $this->session->userdata('user_id'),
                            'created_at' => $current_date_time
                        ];

                        // Insert into 'log_payments' table
                        $logPaymentData = [
                            'transaction_id' => $transaction_id,
                            'payment_data' => json_encode($paymentData),
                            'action' => 1, // Create action
                            'made_by' => $this->session->userdata('user_id'),
                            'device_data' => $this->input->user_agent(),
                            'ip_address' => $this->input->ip_address(),
                            'created_at' => $current_date_time
                        ];
                        $this->db->insert('log_payments', $logPaymentData);
                    }
                }

                if ($paid_amount > 0) {
                    if ($paid_amount >= $total_amount) {
                        $payment_status = 1;
                    } else {
                        $payment_status = 2;
                    }
                } else {
                    $payment_status = 0;
                }

                $paymentUpdateForInvoice = [
                    'payment_status' => $payment_status,
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                // Update invoice data in 'invoices' table
                $this->db->where('id', $invoice_id);
                $this->db->update('invoices', $paymentUpdateForInvoice);
            }

            // Set flash message and redirect
            $this->session->set_flashdata('message', 'New Invoice created successfully');
            redirect('admin/invoices');
        }
    }

    public function updateInvoice($invoice_id)
    {
        $data['activePage'] = 'invoices';

        // Load existing invoice data
        $data['invoice'] = $this->InvoiceModel->get_invoice_by_id($invoice_id);
        $data['invoice_details'] = $this->InvoiceModel->get_invoice_details($invoice_id);
        $data['paymentModes'] = $this->PaymentMethodsModel->getAll();
        $data['is_gst_bill'] = getSetting('is_gst_bill');
        $default_gst_rate = getSetting('default_gst_rate');
        $data['gst_rate'] = isset($default_gst_rate) ? $default_gst_rate : 0;

        // Load necessary data for the view
        $data['products'] = $this->ProductModel->get_all_products();
        $data['customers'] = $this->CustomerModel->get_all_customers();
        $data['customer_details'] = $this->CustomerModel->get_customer_by_id($data['invoice']['customer_id']);
        //$data['payment_methods'] = $this->PaymentModel->get_all_payment_methods(); // Load payment methods

        // Check if a payment already exists for this invoice
        $existing_payments = $this->TransactionModel->get_payment_by_invoice($invoice_id);
        $current_balance = 0;

        $data['invoice_payments'] = $existing_payments;

        $this->form_validation->set_rules('customer_id', 'Customer', 'required');
        $this->form_validation->set_rules('invoice_date', 'Invoice Date', 'required');

        if ($this->form_validation->run() === FALSE) {
            // Load the update invoice view

            $this->render_admin('admin/invoices/update', $data);
        } else {
            /* echo "<pre>";
            print_r($_POST);
            die; */
            $current_date_time = date('Y-m-d H:i:s');
            // Customer Data
            $customer_name = $this->input->post('customer_name');
            $customer_phone = $this->input->post('mobile');
            $customer_address = $this->input->post('address');
            $customer_gst = $this->input->post('gst');

            // Check if customer exists by mobile number
            $customer = $this->CustomerModel->get_by_phone($customer_phone); // Assuming this method exists in CustomerModel

            if ($customer) {
                // Customer exists, update the record
                $customer_id = $customer['id'];
                $customerData = [
                    'customer_name' => $customer_name,
                    'phone' => $customer_phone,
                    'address' => $customer_address,
                    'gst_number' => $customer_gst,
                    'updated_at' => $current_date_time
                ];
                $this->db->where('id', $customer_id);
                $this->db->update('customers', $customerData);
            } else {
                // Customer doesn't exist, insert a new record
                $customerData = [
                    'customer_name' => $customer_name,
                    'phone' => $customer_phone,
                    'address' => $customer_address,
                    'gst_number' => $customer_gst,
                    'created_at' => $current_date_time
                ];
                $this->db->insert('customers', $customerData);
                $customer_id = $this->db->insert_id();
            }

            $round_off = $this->input->post('round_off');
            $sub_total = $this->input->post('sub_total');
            $total_discount = $this->input->post('total_discount');
            $taxable_value = $sub_total - $total_discount;
            $total_amount = array_sum($this->input->post('final_price'));
            // Prepare updated invoice data
            $invoiceData = [
                'invoice_date' => $this->input->post('invoice_date'),
                'due_date' => $this->input->post('invoice_date'),
                'customer_id' => $this->input->post('customer_id'),
                'sub_total' => $sub_total,
                'total_discount' => $total_discount,
                'total_gst' => $this->input->post('total_gst'),
                'round_off' => $this->input->post('round_off'),
                'total_amount' => ($total_amount + $round_off),
                'taxable_value' => $taxable_value,
                'adjustment_balance' => 0.00,
                'customer_name' => $this->input->post('customer_name'),
                'mobile' => $this->input->post('mobile'),
                'address' => $this->input->post('address'),
                'note' => $this->input->post('note'),
                'updated_at' => $current_date_time
            ];

            // Update invoice data in 'invoices' table
            $this->db->where('id', $invoice_id);
            $this->db->update('invoices', $invoiceData);

            // Insert into 'log_invoices' table
            $logInvoiceData = [
                'invoice_id' => $invoice_id,
                'invoice_data' => json_encode($invoiceData),
                'action' => 2, // Update action
                'made_by' => $this->session->userdata('user_id'),
                'device_data' => $this->input->user_agent(),
                'ip_address' => $this->input->ip_address(),
                'created_at' => $current_date_time
            ];
            $this->db->insert('log_invoices', $logInvoiceData);

            // Delete existing invoice details data
            $this->db->delete('invoice_details', ['invoice_id' => $invoice_id]);

            // Prepare new invoice details data
            $invoiceDetailsData = [];
            $product_ids = $this->input->post('product_id');
            $product_descriptions = $this->input->post('product_descriptions');
            $quantities = $this->input->post('qnt');
            $purchase_prices = $this->input->post('purchase_price');
            $discount_types = $this->input->post('discount_type');
            $discounts = $this->input->post('discount');
            $gst_rates = $this->input->post('gst_rate');
            $gst_amounts = $this->input->post('gst_amount');
            $final_prices = $this->input->post('final_price');

            foreach ($product_ids as $index => $product_id) {
                $gst_rate = $gst_rates[$index];
                $cgst = $gst_rate / 2;
                $sgst = $gst_rate / 2;
                $invoiceDetailsData[] = [
                    'invoice_id' => $invoice_id,
                    'customer_id' => $this->input->post('customer_id'),
                    'product_id' => $product_id,
                    'product_descriptions' => $product_descriptions[$index],
                    'quantity' => $quantities[$index],
                    'price' => $purchase_prices[$index],
                    'discount_type' => $discount_types[$index],
                    'discount' => $discounts[$index],
                    'cgst' => $cgst,
                    'sgst' => $sgst,
                    'gst_amount' => $gst_amounts[$index],
                    'final_price' => $final_prices[$index],
                    'invoice_date' => $this->input->post('invoice_date'),
                    'updated_at' => $current_date_time
                ];
            }

            // Insert new invoice details data into 'invoice_details' table
            $this->db->insert_batch('invoice_details', $invoiceDetailsData);

            // Handle payment update/insert
            /*  */

            // Set flash message and redirect
            $this->session->set_flashdata('message', 'Invoice updated successfully');
            redirect('admin/invoices');
        }
    }

    public function generate_invoice_no($is_gst)
    {
        $invoice_prefix = getSetting('invoice_prefix');
        $financial_year = getCurrentFinancialYear();

        $last_invoice_no = $this->InvoiceModel->get_last_invoice_no($is_gst, $financial_year);
        /* echo $last_invoice_no;
        die; */
        if ($last_invoice_no) {
            $last_invoice_no_parts = explode('/', $last_invoice_no);
            $last_number = end($last_invoice_no_parts);
            $next_number = str_pad((int)$last_number + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $next_number = '0001';
        }

        if ($is_gst) {
            $invoice_no = "{$invoice_prefix}/{$financial_year}/{$next_number}";
        } else {
            $invoice_no = "{$invoice_prefix}/INV/{$next_number}";
        }

        return $invoice_no;
    }



    public function view($invoice_id)
    {
        $data['activePage'] = 'invoices';

        // Fetch invoice details by ID
        $data['invoice'] = $this->InvoiceModel->get_invoice_by_id($invoice_id);
        $data['invoice_details'] = $this->InvoiceModel->get_invoice_details($invoice_id);

        if (empty($data['invoice'])) {
            show_404();
        }

        // Fetch transaction history for this invoice
        $data['transactions'] = $this->InvoiceModel->get_invoice_transactions($invoice_id);


        $this->render_admin('admin/invoices/view', $data);
    }


    public function print_old($invoice_id)
    {
        $data['invoice'] = $this->InvoiceModel->get_invoice_by_id($invoice_id);
        $data['invoice_details'] = $this->InvoiceModel->get_invoice_details($invoice_id);

        if (empty($data['invoice'])) {
            show_404();
        }

        // Load the HTML view as a string
        $html = $this->render_admin('admin/invoices/print', $data, true);

        // Load the PDF library
        $this->load->library('pdf');
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'portrait');
        $this->pdf->render();
        $this->pdf->stream('invoice_' . $invoice_id . '.pdf', array('Attachment' => 0));
    }

    public function print($invoice_id)
    {
        // Fetch invoice data
        $invoice = $this->InvoiceModel->get_invoice_by_id($invoice_id);
        if (!$invoice) {
            show_404();
        }
        $data['invoice'] = $invoice;
        $data['biller']['logo']     = base_url(getSetting('frontend_logo'));
        $data['biller']['name']     = getSetting('site_title');
        $data['biller']['address']  = getSetting('company_address');
        $data['biller']['contact']  = getSetting('company_contact');
        $data['biller']['email']    = getSetting('company_email');
        $data['biller']['website']  = getSetting('company_website');
        $data['biller']['gstin']    = getSetting('company_gstin');
        $data['bank_details']['bank_name']    = getSetting('bank_name');
        $data['bank_details']['account_no']    = getSetting('account_no');
        $data['bank_details']['ifsc_code']    = getSetting('ifsc_code');
        $data['bank_details']['branch']    = getSetting('branch');
        $data['terms']    = getSetting('terms');

        $invoice_details = $this->InvoiceModel->get_invoice_details($invoice_id);
        $data['invoice_details'] = $invoice_details;
        $data['customer'] = $this->CustomerModel->get_customer_by_id($invoice['customer_id']);
        /* echo "<pre>";
        print_r($data['customer']);
        die; */
        $customer_name = $data['customer']['customer_name'];
        $customer_name_formatted = str_replace(' ', '_', ucwords(strtolower($customer_name)));

        $data['transactions'] = $this->InvoiceModel->get_invoice_transactions($invoice_id);

        $html = $this->load->view('admin/invoices/print', $data, true);
        /* echo $html;
        die; */
        // Load the PDF library
        $this->load->library('pdf');
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'portrait');
        $this->pdf->render();
        $this->pdf->stream('Invoice_' . $customer_name_formatted . '_' . $invoice_id . '.pdf', array('Attachment' => 0));
    }

    public function print_receipt($transaction_id)
    {
        // Fetch payment transaction
        $payment = $this->InvoiceModel->get_transaction_by_id($transaction_id);

        if (!$payment) {
            show_404();
        }

        // Fetch linked invoice (if any)
        $invoice = null;
        if (!empty($payment['table_id'])) {
            $invoice = $this->InvoiceModel->get_invoice_by_id($payment['table_id']);
        }

        // Fetch customer (via invoice)
        $customer = $invoice ? $this->CustomerModel->get_customer_by_id($invoice['customer_id']) : null;

        // Biller details
        $biller = [
            'logo'     => base_url(getSetting('frontend_logo')),
            'name'     => getSetting('site_title'),
            'address'  => getSetting('company_address'),
            'contact'  => getSetting('company_contact'),
            'email'    => getSetting('company_email'),
            'website'  => getSetting('company_website'),
            'gstin'    => getSetting('company_gstin'),
        ];

        // Bank details
        $bank_details = [
            'bank_name'  => getSetting('bank_name'),
            'account_no' => getSetting('account_no'),
            'ifsc_code'  => getSetting('ifsc_code'),
            'branch'     => getSetting('branch'),
        ];

        // Load view
        $data = [
            'payment'       => $payment,
            'invoice'       => $invoice,
            'customer'      => $customer,
            'biller'        => $biller,
            'bank_details'  => $bank_details,
            'terms'         => getSetting('terms')
        ];

        $customer_name = $customer['customer_name'] ?? 'Payment';
        $filename = 'Receipt_' . str_replace(' ', '_', ucwords(strtolower($customer_name))) . '_' . $transaction_id . '.pdf';

        $html = $this->load->view('admin/payments/print_receipt', $data, true);

        $this->load->library('pdf');
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'portrait');
        $this->pdf->render();
        $this->pdf->stream($filename, ['Attachment' => 0]);
    }

    public function product_details()
    {
        $product_id  = $this->input->post('product_id');
        $customer_id = $this->input->post('customer_id'); // Optional

        $response = [];

        // Basic product + unit symbol
        $product = $this->ProductModel->get_product_details($product_id);

        // Last 5 purchase prices by customer
        $lastPurchases = $this->ProductModel->get_last_purchase_prices($product_id, $customer_id);

        // Last 5 stock entries
        $stock_history = $this->StockModel->get_stock_history($product_id);
        $current_stock = $this->StockModel->get_current_stock($product_id);


        if (!empty($product)) {
            $response = [
                'status'         => 'success',
                'product'        => $product,
                'last_purchases' => $lastPurchases,
                'stock_entries'  => $stock_history,
                'current_stock'  => $current_stock,
                'lastPP'         => $lastPP,
                'lastSP'         => $lastSP,
            ];
        } else {
            $response = [
                'status'  => 'error',
                'message' => 'Product not found.'
            ];
        }

        echo json_encode($response);
    }

    // Method to fetch last 5 stock entries
    public function getLastestStocks()
    {
        $product_id = $this->input->post('product_id');
        $stocks = $this->StockModel->get_stock_history($product_id);
        $current_stock = $this->StockModel->get_current_stock($product_id);

        if ($stocks) {
            $response = [
                'status' => 'success',
                'data' => $stocks,
                'current_stock' => $current_stock
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'No stock entries found.'
            ];
        }

        echo json_encode($response);
    }

    public function addPayment()
    {
        $invoice_id = $this->input->post('invoice_id');
        $payment_amount = $this->input->post('payment_amount');
        $payment_method = $this->input->post('payment_method');
        $payment_date = $this->input->post('payment_date');
        $payment_note = $this->input->post('payment_note');

        // Prepare data for insertion
        $data = [
            'amount' => $payment_amount,
            'trans_type' => 1, // Credit
            'payment_method_id' => $payment_method,
            'transaction_for_table' => 'invoices',
            'table_id' => $invoice_id,
            'trans_by' => $this->session->userdata('user_id'),
            'trans_date' => $payment_date,
            'descriptions' => $payment_note
        ];

        // Insert payment into the transactions table
        $transaction_id = $this->TransactionModel->insert_transaction($data);

        $balance = $this->calculateBalance($invoice_id); // Recalculate the balance

        $paymentModes = getPaymentModes();

        // Insert into 'log_payments' table
        $logPaymentData = [
            'transaction_id' => $transaction_id,
            'payment_data' => json_encode($data),
            'action' => 1, // Create action
            'made_by' => $this->session->userdata('user_id'),
            'device_data' => $this->input->user_agent(),
            'ip_address' => $this->input->ip_address(),
            'created_at' => date('Y-m-d H:i:s')
        ];
        $this->db->insert('log_payments', $logPaymentData);


        if ($payment_amount > 0) {
            if ($balance <= 0) {
                $payment_status = 1;
            } else {
                $payment_status = 2;
            }

            $paymentUpdateForInvoice = [
                'payment_status' => $payment_status,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Update invoice data in 'invoices' table
            $this->db->where('id', $invoice_id);
            $this->db->update('invoices', $paymentUpdateForInvoice);
        }


        // Return response as JSON
        echo json_encode([
            'success' => true,
            'action' => 'add',
            'data' => [
                'id' => $transaction_id,
                'amount' => number_format($payment_amount, 2),
                'payment_method' => $paymentModes[$payment_method],
                'trans_date' => $payment_date,
                'payment_note' => $payment_note
            ],
            'balance' => $balance
        ]);
    }

    public function getPaymentDetails($id)
    {
        $payment = $this->TransactionModel->get_transactions_by_id($id); // Assuming you have a model method
        if ($payment) {
            echo json_encode(['success' => true, 'data' => $payment]);
        } else {
            echo json_encode(['success' => false]);
        }
    }

    public function updatePayment()
    {
        //print_r($_POST);
        $this->form_validation->set_rules('payment_amount', 'Amount', 'required');
        $this->form_validation->set_rules('payment_method', 'Payment Method', 'required');

        if ($this->form_validation->run() == TRUE) {

            $payment_method = $this->input->post('payment_method');
            $payment_amount = $this->input->post('payment_amount');
            $payment_date = $this->input->post('payment_date');
            $payment_note = $this->input->post('payment_note');

            $data = [
                'amount' => $payment_amount,
                'payment_method_id' => $payment_method,
                'trans_date' => $payment_date,
                'descriptions' => $payment_note
            ];

            $transaction_id = $this->input->post('payment_id');
            $invoice_id = $this->input->post('invoice_id');

            /* print_r($invoice_id);
            print_r($transaction_id);
            print_r($data);
            die; */

            $this->TransactionModel->update_transaction($transaction_id, $data);

            $balance = $this->calculateBalance($invoice_id);

            $paymentModes = getPaymentModes();

            // Update 'log_payments' table
            $logPaymentData = [
                'transaction_id' => $transaction_id,
                'payment_data' => json_encode($data),
                'action' => 2, // Update action
                'made_by' => $this->session->userdata('user_id'),
                'device_data' => $this->input->user_agent(),
                'ip_address' => $this->input->ip_address(),
                'created_at' => date('Y-m-d H:i:s')
            ];
            $this->db->insert('log_payments', $logPaymentData);


            if ($payment_amount > 0) {
                if ($balance <= 0) {
                    $payment_status = 1;
                } else {
                    $payment_status = 2;
                }

                $paymentUpdateForInvoice = [
                    'payment_status' => $payment_status,
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                // Update invoice data in 'invoices' table
                $this->db->where('id', $invoice_id);
                $this->db->update('invoices', $paymentUpdateForInvoice);
            }

            // Return response as JSON
            echo json_encode([
                'success' => true,
                'action' => 'edit',
                'data' => [
                    'id' => $transaction_id,
                    'amount' => number_format($payment_amount, 2),
                    'payment_method' => $paymentModes[$payment_method],
                    'trans_date' => $payment_date,
                    'payment_note' => $payment_note
                ],
                'balance' => $balance
            ]);
        } else {
            echo json_encode(['success' => false]);
        }
    }

    public function calculateBalance($invoice_id)
    {
        // Calculate the updated balance
        $total_paid = $this->TransactionModel->get_total_paid_by_invoice($invoice_id);
        $invoice_total = $this->InvoiceModel->get_invoice_total($invoice_id);
        $balance = $invoice_total - $total_paid;

        return $balance;
    }

    public function deletePayment()
    {
        $invoice_id = $this->input->post('invoice_id');
        $transaction_id = $this->input->post('payment_id');

        if ($transaction_id) {

            $data = $this->TransactionModel->get_transactions_by_id($transaction_id);

            if ($this->db->where('id', $transaction_id)->delete('transactions')) {

                $balance = $this->calculateBalance($invoice_id);

                // Update 'log_payments' table
                $logPaymentData = [
                    'transaction_id' => $transaction_id,
                    'payment_data' => json_encode($data),
                    'action' => 3, // delete action
                    'made_by' => $this->session->userdata('user_id'),
                    'device_data' => $this->input->user_agent(),
                    'ip_address' => $this->input->ip_address(),
                    'created_at' => date('Y-m-d H:i:s')
                ];
                $this->db->insert('log_payments', $logPaymentData);


                if ($balance <= 0) {
                    $payment_status = 1;
                } else {
                    $payment_status = 2;
                }

                $paymentUpdateForInvoice = [
                    'payment_status' => $payment_status,
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                // Update invoice data in 'invoices' table
                $this->db->where('id', $invoice_id);
                $this->db->update('invoices', $paymentUpdateForInvoice);


                echo json_encode(['success' => true, 'balance' => $balance]);
            } else {
                echo json_encode(['success' => false]);
            }
        } else {
            echo json_encode(['success' => false]);
        }
    }

    public function delete($invoice_id)
    {
        $current_date_time = date('Y-m-d H:i:s');

        // Fetch the invoice to check if it exists
        $invoice = $this->InvoiceModel->get_invoice_by_id($invoice_id);
        if (!$invoice) {
            $this->session->set_flashdata('error', 'Invoice not found.');
            redirect('admin/invoices');
        }

        // Log the deletion for audit purposes
        $logInvoiceData = [
            'invoice_id' => $invoice_id,
            'invoice_data' => json_encode($invoice), // Save the deleted invoice data
            'action' => 3, // Delete action
            'made_by' => $this->session->userdata('user_id'),
            'device_data' => $this->input->user_agent(),
            'ip_address' => $this->input->ip_address(),
            'created_at' => $current_date_time
        ];
        $this->db->insert('log_invoices', $logInvoiceData);

        // Delete the invoice details from 'invoice_details'
        $this->db->delete('invoice_details', ['invoice_id' => $invoice_id]);

        // Delete the related transactions from 'transactions'
        $this->db->delete('transactions', ['table_id' => $invoice_id, 'transaction_for_table' => 'invoices']);

        // Delete the invoice from 'invoices'
        $this->db->delete('invoices', ['id' => $invoice_id]);

        // Set a success message and redirect
        $this->session->set_flashdata('error_message', 'Invoice deleted successfully.');
        redirect('admin/invoices');
    }

    public function latest_sale_prices()
    {
        $customer_id = $this->input->post('customer_id');
        $product_id = $this->input->post('product_id');
        $result = $this->StockModel->get_latest_sale_prices($product_id, $customer_id);

        if (!empty($result)) {
            echo json_encode(['status' => 'success', 'data' => $result]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No previous purchases found']);
        }
    }
}
