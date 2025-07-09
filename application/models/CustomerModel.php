<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CustomerModel extends CI_Model
{
    private $table = 'customers';
    private $column_order = [null, 'id', 'customer_name', 'phone', 'email', 'balance']; // null for image and balance/action

    private $column_search = ['customer_name', 'phone', 'email']; // address removed
    private $order = ['id' => 'desc'];

    public function __construct()
    {
        parent::__construct();
    }

    // Method to insert a new customer
    public function insert_customer($data)
    {
        $this->db->insert('customers', $data);
        return $this->db->insert_id(); // Return the ID of the newly created customer
    }

    // Method to update an existing customer
    public function update_customer($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('customers', $data);
    }

    // Method to retrieve customer by phone number
    public function get_by_phone($phone)
    {
        $this->db->where('phone', $phone);
        $query = $this->db->get('customers');

        // Check if the customer exists
        if ($query->num_rows() > 0) {
            return $query->row_array(); // Return customer data if found
        } else {
            return false; // Return false if not found
        }
    }

    // Method to get all customers
    public function get_all_customers()
    {
        $query = $this->db->get('customers');
        return $query->result_array();
    }

    // Method to get a specific customer by ID
    public function get_customer_by_id($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('customers');
        $customer = $query->row_array();

        if (!$customer) {
            return null;
        }

        // Get total invoice amount
        $this->db->select('SUM(total_amount) as invoice_total');
        $this->db->from('invoices');
        $this->db->where('customer_id', $id);
        $this->db->where('status', 1);
        $invoice_total_result = $this->db->get()->row_array();
        $invoice_total = $invoice_total_result['invoice_total'] ?? 0;

        // Get total payments
        $this->db->select('SUM(amount) as paid_total');
        $this->db->from('transactions');
        $this->db->join('invoices', 'invoices.id = transactions.table_id', 'left');
        $this->db->where('transactions.transaction_for_table', 'invoices');
        $this->db->where('transactions.trans_type', 1); // incoming payments
        $this->db->where('transactions.status', 1);
        $this->db->where('invoices.status', 1);
        $this->db->where('invoices.customer_id', $id);
        $paid_total_result = $this->db->get()->row_array();
        $paid_total = $paid_total_result['paid_total'] ?? 0;

        // Calculate balance
        $balance = $paid_total - $invoice_total;
        $customer['balance'] = $balance;

        return $customer;
    }

    private function _get_datatables_query()
    {
        $this->db->select("
            customers.*,
            (
                SELECT COALESCE(SUM(i.total_amount), 0)
                FROM invoices i
                WHERE i.customer_id = customers.id AND i.status = 1
            ) AS invoice_total,
            (
                SELECT COALESCE(SUM(t.amount), 0)
                FROM transactions t
                LEFT JOIN invoices i2 ON i2.id = t.table_id
                WHERE t.transaction_for_table = 'invoices'
                    AND t.trans_type = 1
                    AND t.status = 1
                    AND i2.status = 1
                    AND i2.customer_id = customers.id
            ) AS paid_total,
            (
                (
                    SELECT COALESCE(SUM(t.amount), 0)
                    FROM transactions t
                    LEFT JOIN invoices i2 ON i2.id = t.table_id
                    WHERE t.transaction_for_table = 'invoices'
                        AND t.trans_type = 1
                        AND t.status = 1
                        AND i2.status = 1
                        AND i2.customer_id = customers.id
                ) -
                (
                    SELECT COALESCE(SUM(i.total_amount), 0)
                    FROM invoices i
                    WHERE i.customer_id = customers.id AND i.status = 1
                )
            ) AS balance
        ");
        $this->db->from('customers');

        // Search filter
        $i = 0;
        foreach ($this->column_search as $item) {
            if ($_POST['search']['value']) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        }

        // Ordering
        if (isset($_POST['order'])) {
            $order_col = $this->column_order[$_POST['order']['0']['column']];
            $order_dir = $_POST['order']['0']['dir'];
            $this->db->order_by($order_col, $order_dir);
        } else {
            $this->db->order_by(key($this->order), $this->order[key($this->order)]);
        }
    }

    public function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);

        return $this->db->get()->result();
    }

    public function count_filtered()
    {
        $this->_get_datatables_query();
        return $this->db->count_all_results();
    }

    public function count_all()
    {
        return $this->db->count_all($this->table);
    }

    public function delete_customer($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    public function get_account_summary($customer_id)
    {
        $from_date = $this->input->get('from_date') ?? date('Y-01-01');
        $to_date   = $this->input->get('to_date') ?? date('Y-m-d');

        // Opening balance: total paid - total invoiced BEFORE from_date
        $this->db->select('
            (
                (SELECT COALESCE(SUM(amount), 0)
                FROM transactions
                WHERE transaction_for_table = "invoices"
                AND trans_type = 1
                AND status = 1
                AND table_id IN (SELECT id FROM invoices WHERE customer_id = c.id)
                AND trans_date < "' . $from_date . '")
                -
                (SELECT COALESCE(SUM(total_amount), 0)
                FROM invoices
                WHERE customer_id = c.id AND status = 1 AND invoice_date < "' . $from_date . '")
            ) AS opening_balance
        ');
        $this->db->from('customers c');
        $this->db->where('c.id', $customer_id);
        $opening = $this->db->get()->row_array();

        $opening_balance = $opening['opening_balance'] ?? 0;

        // All Invoices (within date range)
        $this->db->select('invoice_date as date, invoice_no as ref, total_amount as amount, "Invoice" as type');
        $this->db->from('invoices');
        $this->db->where('customer_id', $customer_id);
        $this->db->where('status', 1);
        $this->db->where('invoice_date >=', $from_date);
        $this->db->where('invoice_date <=', $to_date);
        $invoices = $this->db->get()->result_array();

        // All Payments (within date range)
        $this->db->select('trans_date as date, descriptions as ref, amount, "Payment" as type');
        $this->db->from('transactions');
        $this->db->where('transaction_for_table', 'invoices');
        $this->db->where('trans_type', 1);
        $this->db->where('status', 1);
        $this->db->where('table_id IN (SELECT id FROM invoices WHERE customer_id = ' . $customer_id . ')', NULL, FALSE);
        $this->db->where('trans_date >=', $from_date);
        $this->db->where('trans_date <=', $to_date);
        $payments = $this->db->get()->result_array();

        // Merge + Sort by date
        $entries = array_merge($invoices, $payments);
        usort($entries, function ($a, $b) {
            return strtotime($a['date']) <=> strtotime($b['date']);
        });

        // Recalculate running balance
        $balance = $opening_balance;
        foreach ($entries as &$entry) {
            if ($entry['type'] == 'Invoice') {
                $balance -= $entry['amount']; // subtract invoice
            } else {
                $balance += $entry['amount']; // add payment
            }
            $entry['balance'] = $balance;
        }

        return [
            'from_date' => $from_date,
            'to_date' => $to_date,
            'opening_balance' => $opening_balance,
            'entries' => $entries,
            'closing_balance' => $balance
        ];
    }

    public function get_payments($customer_id)
    {
        $from_date = $this->input->get('from_date') ?? date('Y-01-01');
        $to_date   = $this->input->get('to_date') ?? date('Y-m-d');

        $this->db->select('
            t.id,
            t.trans_date,
            t.amount,
            t.descriptions,
            pm.title as payment_method,
            i.invoice_no
        ');
        $this->db->from('transactions t');
        $this->db->join('payment_methods pm', 'pm.id = t.payment_method_id', 'left');
        $this->db->join('invoices i', 'i.id = t.table_id', 'left');
        $this->db->where('t.transaction_for_table', 'invoices');
        $this->db->where('t.trans_type', 1); // incoming payments only
        $this->db->where('t.status', 1);
        $this->db->where('i.customer_id', $customer_id);
        $this->db->where('t.trans_date >=', $from_date);
        $this->db->where('t.trans_date <=', $to_date);
        $this->db->order_by('t.trans_date DESC, t.id DESC');

        return $this->db->get()->result_array();
    }

    public function get_invoices($customer_id)
    {
        $from_date = $this->input->get('from_date') ?? date('Y-01-01');
        $to_date   = $this->input->get('to_date') ?? date('Y-m-d');

        $this->db->select('id, invoice_no, invoice_date, due_date, total_amount, payment_status');
        $this->db->from('invoices');
        $this->db->where('customer_id', $customer_id);
        $this->db->where('status', 1);
        $this->db->where('invoice_date >=', $from_date);
        $this->db->where('invoice_date <=', $to_date);
        $this->db->order_by('invoice_date', 'DESC');

        return $this->db->get()->result_array();
    }
}
