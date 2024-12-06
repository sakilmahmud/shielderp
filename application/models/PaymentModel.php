<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PaymentModel extends CI_Model
{

    // Get all payment methods
    public function get_all_payment_methods()
    {
        return $this->db->get('payment_methods')->result_array();
    }

    // Get payment details by ID
    public function get_payment_details($id)
    {
        return $this->db->where('id', $id)
            ->get('payments')
            ->row_array();
    }

    // Get payment details by invoice ID
    public function get_payment_by_invoice($invoice_id)
    {
        return $this->db->where('invoice_id', $invoice_id)
            ->get('payments')
            ->result_array();
    }

    // Insert new payment data
    public function insert_payment($paymentData)
    {
        return $this->db->insert('payments', $paymentData);
    }

    // Update existing payment data
    public function update_payment($id, $paymentData)
    {
        $this->db->where('id', $id);
        return $this->db->update('payments', $paymentData);
    }

    // Insert payment log
    public function insert_payment_log($logPaymentData)
    {
        return $this->db->insert('log_payments', $logPaymentData);
    }

    // Update payment method balance
    public function update_payment_method_balance($payment_method_id, $new_amount, $old_amount)
    {
        // Get the current balance of the payment method
        $current_balance = $this->db->select('current_balance')
            ->where('id', $payment_method_id)
            ->get('payment_methods')
            ->row()
            ->current_balance;

        // Calculate the new balance
        $new_balance = $current_balance - $old_amount + $new_amount;

        // Update the balance in the payment_methods table
        $this->db->where('id', $payment_method_id)
            ->update('payment_methods', ['current_balance' => $new_balance]);
    }

    // Get payment method balance by ID
    public function get_payment_method_balance($payment_method_id)
    {
        return $this->db->select('current_balance')
            ->where('id', $payment_method_id)
            ->get('payment_methods')
            ->row()
            ->current_balance;
    }
}
