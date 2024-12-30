<?php
defined('BASEPATH') or exit('No direct script access allowed');

class InvoiceController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Load any required models, helpers, libraries, etc.
        $this->load->model('InvoiceModel');
        $this->load->model('ProductModel');
        $this->load->model('CustomerModel');
        $this->load->model('StockModel');
        $this->load->model('PaymentModel');
        $this->load->model('TransactionModel');
        $this->load->library('form_validation');

        if (!$this->session->userdata('user_id')) {
            redirect('login');
        }
    }

    public function index()
    {
        $data['activePage'] = 'invoices';
        $data['invoices'] = $this->InvoiceModel->get_all_invoices();
        $this->load->view('admin/header', $data);
        $this->load->view('admin/invoices/index', $data);
        $this->load->view('admin/footer');
    }

    public function createInvoice()
    {
        $data['activePage'] = 'invoices';
        $data['is_gst_bill'] = getSetting('is_gst_bill');

        // Load necessary data for the view
        $data['products'] = $this->ProductModel->get_all_products();
        $data['customers'] = $this->CustomerModel->get_all_customers();
        $data['paymentModes'] = getPaymentModes();
        /* print_r($data['paymentModes']);
        die; */
        $this->form_validation->set_rules('customer_id', 'Customer', 'required');
        $this->form_validation->set_rules('invoice_date', 'Invoice Date', 'required');
        $this->form_validation->set_rules('product_id[]', 'Product', 'required');

        if ($this->form_validation->run() === FALSE) {

            // Load the create invoice view
            $this->load->view('admin/header', $data);
            $this->load->view('admin/invoices/create', $data);
            $this->load->view('admin/footer');
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

            // Prepare invoice data
            $invoiceData = [
                'invoice_no' => $invoice_no,
                'invoice_date' => $this->input->post('invoice_date'),
                'due_date' => $this->input->post('invoice_date'),
                'customer_id' => $customer_id, // Use the correct customer ID
                'is_gst' => $is_gst,
                'sub_total' => $this->input->post('sub_total'),
                'total_discount' => $this->input->post('total_discount'),
                'total_gst' => $this->input->post('total_gst'),
                'total_amount' => $total_amount,
                'customer_name' => $customer_name,
                'mobile' => $customer_phone,
                'address' => $customer_address,
                'gst' => $customer_gst,
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
                $invoiceDetailsData[] = [
                    'invoice_id' => $invoice_id,
                    'customer_id' => $this->input->post('customer_id'),
                    'product_id' => $product_id,
                    'product_descriptions' => $product_descriptions[$index],
                    'quantity' => $quantities[$index],
                    'price' => $purchase_prices[$index],
                    'discount_type' => $discount_types[$index],
                    'discount' => $discounts[$index],
                    'gst_rate' => $gst_rates[$index],
                    'gst_amount' => $gst_amounts[$index],
                    'final_price' => $final_prices[$index],
                    'created_at' => $current_date_time
                ];
                // Reduce the stock for each product
                $this->StockModel->reduceStock($product_id, $quantities[$index]);
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
                        $paid_amount += $payment_amounts[$index];

                        // Insert into 'transactions' table
                        $transactionData = [
                            'amount' => $payment_amounts[$index],
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

        $data['is_gst_bill'] = getSetting('is_gst_bill');

        // Load necessary data for the view
        $data['products'] = $this->ProductModel->get_all_products();
        $data['customers'] = $this->CustomerModel->get_all_customers();
        $data['payment_methods'] = $this->PaymentModel->get_all_payment_methods(); // Load payment methods
        // Check if a payment already exists for this invoice
        $existing_payments = $this->TransactionModel->get_payment_by_invoice($invoice_id);
        $current_balance = 0;

        $data['invoice_payments'] = $existing_payments;

        $this->form_validation->set_rules('customer_id', 'Customer', 'required');
        $this->form_validation->set_rules('invoice_date', 'Invoice Date', 'required');

        if ($this->form_validation->run() === FALSE) {
            // Load the update invoice view
            $this->load->view('admin/header', $data);
            $this->load->view('admin/invoices/update', $data);
            $this->load->view('admin/footer');
        } else {
            /* echo "<pre>";
            print_r($_POST);
            die; */
            $current_date_time = date('Y-m-d H:i:s');
            // Prepare updated invoice data
            $invoiceData = [
                'invoice_date' => $this->input->post('invoice_date'),
                'due_date' => $this->input->post('invoice_date'),
                'customer_id' => $this->input->post('customer_id'),
                'total_amount' => array_sum($this->input->post('final_price')),
                'customer_name' => $this->input->post('customer_name'),
                'mobile' => $this->input->post('mobile'),
                'address' => $this->input->post('address'),
                'gst' => $this->input->post('gst'),
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
                $invoiceDetailsData[] = [
                    'invoice_id' => $invoice_id,
                    'customer_id' => $this->input->post('customer_id'),
                    'product_id' => $product_id,
                    'product_descriptions' => $product_descriptions[$index],
                    'quantity' => $quantities[$index],
                    'price' => $purchase_prices[$index],
                    'discount_type' => $discount_types[$index],
                    'discount' => $discounts[$index],
                    'gst_rate' => $gst_rates[$index],
                    'gst_amount' => $gst_amounts[$index],
                    'final_price' => $final_prices[$index],
                    'created_at' => $current_date_time
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

        // Fetch the last invoice number
        $last_invoice_no = $this->InvoiceModel->get_last_invoice_no($is_gst);

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

        $this->load->view('admin/header', $data);
        $this->load->view('admin/invoices/view', $data);
        $this->load->view('admin/footer');
    }


    public function print_old($invoice_id)
    {
        $data['invoice'] = $this->InvoiceModel->get_invoice_by_id($invoice_id);
        $data['invoice_details'] = $this->InvoiceModel->get_invoice_details($invoice_id);

        if (empty($data['invoice'])) {
            show_404();
        }

        // Load the HTML view as a string
        $html = $this->load->view('admin/invoices/print', $data, true);

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

        $data['transactions'] = $this->InvoiceModel->get_invoice_transactions($invoice_id);

        $html = $this->load->view('admin/invoices/print', $data, true);
        /* echo $html;
        die; */
        // Load the PDF library
        $this->load->library('pdf');
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'portrait');
        $this->pdf->render();
        $this->pdf->stream('invoice_' . $invoice_id . '.pdf', array('Attachment' => 0));
    }

    // Method to fetch last 5 stock entries
    public function getLastestStocks()
    {
        $product_id = $this->input->post('product_id');
        $stocks = $this->StockModel->get_lastest_stocks($product_id);

        if ($stocks) {
            $lastPP = $stocks[0]['purchase_price'];
            $lastSP = $stocks[0]['sale_price'];
            $response = [
                'status' => 'success',
                'lastPP' => $lastPP,
                'lastSP' => $lastSP,
                'data' => $stocks
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
}
