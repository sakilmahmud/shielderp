<?php
defined('BASEPATH') or exit('No direct script access allowed');

class GstrModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('SettingsModel');
    }

    // Helper function to get company's state code
    private function _get_company_state_code()
    {
        $company_state_id = $this->SettingsModel->get_setting('company_state');
        $this->db->select('state_code');
        $this->db->where('id', $company_state_id);
        $query = $this->db->get('states');
        $result = $query->row();
        return $result ? $result->state_code : null;
    }

    // Helper function to get state code from state ID
    private function _get_state_code_by_id($state_id)
    {
        $this->db->select('state_code');
        $this->db->where('id', $state_id);
        $query = $this->db->get('states');
        $result = $query->row();
        return $result ? $result->state_code : null;
    }

    public function get_gstr1_b2b_data($from_date, $to_date)
    {
        $this->db->select(
            'i.invoice_no, i.invoice_date, i.total_amount, i.total_discount, i.total_gst, i.round_off, i.is_reverse_charge, i.supply_type,
             c.gst_number as cust_gstin, s.state_code as pos_state_code,
             id.hsn_code, id.quantity, id.price, id.discount, id.cgst, id.sgst, id.gst_amount, id.cess_amount, id.final_price'
        );
        $this->db->from('invoices i');
        $this->db->join('customers c', 'i.customer_id = c.id', 'left');
        $this->db->join('states s', 'c.state_id = s.id', 'left');
        $this->db->join('invoice_details id', 'i.id = id.invoice_id', 'left');
        $this->db->where('i.invoice_date >=', $from_date);
        $this->db->where('i.invoice_date <=', $to_date);
        $this->db->where('c.gst_number IS NOT NULL'); // Only B2B invoices
        $this->db->where('c.gst_number !=', '');
        $this->db->where('i.is_gst', 1); // Only GST invoices

        $query = $this->db->get();
        $results = $query->result_array();

        $b2b_invoices = [];
        $company_state_code = $this->_get_company_state_code();

        foreach ($results as $row) {
            $invoice_no = $row['invoice_no'];
            $cust_gstin = $row['cust_gstin'];
            $pos_state_code = $row['pos_state_code'];

            // Determine supply type (inter-state or intra-state)
            $supply_type = ($company_state_code === $pos_state_code) ? 'INTRA' : 'INTER';

            if (!isset($b2b_invoices[$cust_gstin])) {
                $b2b_invoices[$cust_gstin] = [
                    'ctin' => $cust_gstin,
                    'inv' => []
                ];
            }

            if (!isset($b2b_invoices[$cust_gstin]['inv'][$invoice_no])) {
                $b2b_invoices[$cust_gstin]['inv'][$invoice_no] = [
                    'inum' => $invoice_no,
                    'idt' => date('d-m-Y', strtotime($row['invoice_date'])),
                    'val' => (float)($row['total_amount'] + $row['total_discount'] - $row['round_off']),
                    'pos' => $pos_state_code,
                    'rchrg' => $row['is_reverse_charge'] ? 'Y' : 'N',
                    'inv_typ' => 'R', // Regular
                    'itms' => []
                ];
            }

            $taxable_value = round($row['final_price'] / (1 + ($row['cgst'] + $row['sgst']) / 100), 2); // Calculate taxable value from final price

            $b2b_invoices[$cust_gstin]['inv'][$invoice_no]['itms'][] = [
                'num' => count($b2b_invoices[$cust_gstin]['inv'][$invoice_no]['itms']) + 1,
                'itm_det' => [
                    'txval' => (float) $taxable_value,
                    'rt' => (float)($row['cgst'] + $row['sgst']),
                    'iamt' => ($supply_type === 'INTER') ? (float)($row['gst_amount']) : 0.00,
                    'camt' => ($supply_type === 'INTRA') ? (float)($row['cgst'] * $taxable_value / 100) : 0.00,
                    'samt' => ($supply_type === 'INTRA') ? (float)($row['sgst'] * $taxable_value / 100) : 0.00,
                    'csamt' => (float)$row['cess_amount']
                ]
            ];
        }

        // Convert associative arrays to indexed arrays for JSON output
        $formatted_b2b = [];
        foreach ($b2b_invoices as $gstin_data) {
            $inv_array = [];
            foreach ($gstin_data['inv'] as $inv) {
                $inv_array[] = $inv;
            }
            $gstin_data['inv'] = $inv_array;
            $formatted_b2b[] = $gstin_data;
        }

        return $formatted_b2b;
    }

    public function get_gstr1_b2cl_data($from_date, $to_date)
    {
        $this->db->select(
            'i.invoice_no, i.invoice_date, i.total_amount, i.total_discount, i.total_gst, i.round_off, i.supply_type,
             s.state_code as pos_state_code,
             id.hsn_code, id.quantity, id.price, id.discount, id.cgst, id.sgst, id.gst_amount, id.cess_amount, id.final_price'
        );
        $this->db->from('invoices i');
        $this->db->join('customers c', 'i.customer_id = c.id', 'left');
        $this->db->join('states s', 'c.state_id = s.id', 'left');
        $this->db->join('invoice_details id', 'i.id = id.invoice_id', 'left');
        $this->db->where('i.invoice_date >=', $from_date);
        $this->db->where('i.invoice_date <=', $to_date);
        $this->db->group_start();
        $this->db->where('c.gst_number IS NULL');
        $this->db->or_where('c.gst_number', '');
        $this->db->group_end();
        $this->db->where('i.is_gst', 1); // Only GST invoices
        $this->db->where('i.total_amount >', 250000); // B2CL threshold

        $query = $this->db->get();
        $results = $query->result_array();

        $b2cl_invoices = [];
        $company_state_code = $this->_get_company_state_code();

        foreach ($results as $row) {
            $invoice_no = $row['invoice_no'];
            $pos_state_code = $row['pos_state_code'];

            // Determine supply type (inter-state or intra-state)
            $supply_type = ($company_state_code === $pos_state_code) ? 'INTRA' : 'INTER';

            // B2CL only applies to inter-state supplies
            if ($supply_type === 'INTRA') {
                continue;
            }

            if (!isset($b2cl_invoices[$pos_state_code])) {
                $b2cl_invoices[$pos_state_code] = [
                    'pos' => $pos_state_code,
                    'inv' => []
                ];
            }

            if (!isset($b2cl_invoices[$pos_state_code]['inv'][$invoice_no])) {
                $b2cl_invoices[$pos_state_code]['inv'][$invoice_no] = [
                    'inum' => $invoice_no,
                    'idt' => date('d-m-Y', strtotime($row['invoice_date'])),
                    'val' => (float)($row['total_amount'] + $row['total_discount'] - $row['round_off']),
                    'itms' => []
                ];
            }

            $taxable_value = round($row['final_price'] / (1 + ($row['cgst'] + $row['sgst']) / 100), 2);

            $b2cl_invoices[$pos_state_code]['inv'][$invoice_no]['itms'][] = [
                'num' => count($b2cl_invoices[$pos_state_code]['inv'][$invoice_no]['itms']) + 1,
                'itm_det' => [
                    'txval' => (float) $taxable_value,
                    'rt' => (float)($row['cgst'] + $row['sgst']),
                    'iamt' => (float)($row['gst_amount']),
                    'csamt' => (float)$row['cess_amount']
                ]
            ];
        }

        $formatted_b2cl = [];
        foreach ($b2cl_invoices as $pos_data) {
            $inv_array = [];
            foreach ($pos_data['inv'] as $inv) {
                $inv_array[] = $inv;
            }
            $pos_data['inv'] = $inv_array;
            $formatted_b2cl[] = $pos_data;
        }

        return $formatted_b2cl;
    }

    public function get_gstr1_b2cs_data($from_date, $to_date)
    {
        $this->db->select(
            's.state_code as pos_state_code, id.cgst, id.sgst, id.cess_amount, id.final_price'
        );
        $this->db->from('invoices i');
        $this->db->join('customers c', 'i.customer_id = c.id', 'left');
        $this->db->join('states s', 'c.state_id = s.id', 'left');
        $this->db->join('invoice_details id', 'i.id = id.invoice_id', 'left');
        $this->db->where('i.invoice_date >=', $from_date);
        $this->db->where('i.invoice_date <=', $to_date);
        $this->db->group_start();
        $this->db->where('c.gst_number IS NULL');
        $this->db->or_where('c.gst_number', '');
        $this->db->group_end();
        $this->db->where('i.is_gst', 1); // Only GST invoices
        $this->db->where('i.total_amount <=', 250000); // B2CS threshold

        $query = $this->db->get();
        $results = $query->result_array();

        $b2cs_summary = [];
        $company_state_code = $this->_get_company_state_code();

        foreach ($results as $row) {
            $pos_state_code = $row['pos_state_code'];
            $gst_rate = (float)($row['cgst'] + $row['sgst']);
            $cess_rate = (float)$row['cess_amount']; // Assuming cess_amount can be used to derive cess rate or is already a rate

            $taxable_value = round($row['final_price'] / (1 + ($row['cgst'] + $row['sgst']) / 100), 2);

            // Determine supply type (inter-state or intra-state)
            $supply_type = ($company_state_code === $pos_state_code) ? 'INTRA' : 'INTER';

            $key = $pos_state_code . '_' . $gst_rate . '_' . $cess_rate . '_' . $supply_type;

            if (!isset($b2cs_summary[$key])) {
                $b2cs_summary[$key] = [
                    'pos' => $pos_state_code,
                    'sp_typ' => ($supply_type === 'INTER') ? 'INTER' : 'INTRA',
                    'txval' => 0.00,
                    'rt' => $gst_rate,
                    'iamt' => 0.00,
                    'camt' => 0.00,
                    'samt' => 0.00,
                    'csamt' => 0.00
                ];
            }

            $b2cs_summary[$key]['txval'] += $taxable_value;
            if ($supply_type === 'INTER') {
                $b2cs_summary[$key]['iamt'] += $row['gst_amount'];
            } else {
                $b2cs_summary[$key]['camt'] += ($row['cgst'] * $taxable_value / 100);
                $b2cs_summary[$key]['samt'] += ($row['sgst'] * $taxable_value / 100);
            }
            $b2cs_summary[$key]['csamt'] += $row['cess_amount'];
        }

        // Round all values to 2 decimal places
        foreach ($b2cs_summary as &$item) {
            $item['txval'] = round($item['txval'], 2);
            $item['iamt'] = round($item['iamt'], 2);
            $item['camt'] = round($item['camt'], 2);
            $item['samt'] = round($item['samt'], 2);
            $item['csamt'] = round($item['csamt'], 2);
        }

        return array_values($b2cs_summary);
    }

    public function get_gstr1_hsn_data($from_date, $to_date)
    {
        $this->db->select(
            'id.hsn_code, SUM(id.quantity) as total_qty, SUM(id.final_price) as total_value,
             SUM(id.gst_amount) as total_gst_amount, SUM(id.cess_amount) as total_cess_amount,
             id.cgst, id.sgst'
        );
        $this->db->from('invoices i');
        $this->db->join('invoice_details id', 'i.id = id.invoice_id', 'left');
        $this->db->where('i.invoice_date >=', $from_date);
        $this->db->where('i.invoice_date <=', $to_date);
        $this->db->where('i.is_gst', 1); // Only GST invoices
        $this->db->group_by('id.hsn_code, id.cgst, id.sgst');
        $query = $this->db->get();
        $results = $query->result_array();

        $hsn_summary = [];

        foreach ($results as $row) {
            $hsn_code = $row['hsn_code'];
            $gst_rate = (float)($row['cgst'] + $row['sgst']);

            $taxable_value = round($row['total_value'] / (1 + $gst_rate / 100), 2);

            if (!isset($hsn_summary[$hsn_code])) {
                $hsn_summary[$hsn_code] = [
                    'num' => $hsn_code,
                    'desc' => '', // Description is not in invoice_details, might need to join with products/hsn_codes table
                    'uqc' => 'OTH', // Unit Quantity Code - default to others, ideally should come from product unit
                    'qty' => 0,
                    'txval' => 0.00,
                    'iamt' => 0.00,
                    'camt' => 0.00,
                    'samt' => 0.00,
                    'csamt' => 0.00
                ];
            }
            // Assuming total_gst_amount is IGST if inter-state, or CGST+SGST if intra-state
            // This needs refinement based on how gst_amount is stored (IGST or combined CGST+SGST)
            // For simplicity, let's assume gst_amount is total GST and distribute based on rate

            $hsn_summary[$hsn_code]['qty'] += (float)$row['total_qty'];
            $hsn_summary[$hsn_code]['txval'] += $taxable_value;
            $hsn_summary[$hsn_code]['iamt'] += ($gst_rate > 0 && $row['cgst'] == 0 && $row['sgst'] == 0) ? $row['total_gst_amount'] : 0; // If only IGST
            $hsn_summary[$hsn_code]['camt'] += ($gst_rate > 0 && $row['cgst'] > 0) ? ($row['total_gst_amount'] / 2) : 0; // If CGST exists
            $hsn_summary[$hsn_code]['samt'] += ($gst_rate > 0 && $row['sgst'] > 0) ? ($row['total_gst_amount'] / 2) : 0; // If SGST exists
            $hsn_summary[$hsn_code]['csamt'] += (float)$row['total_cess_amount'];
        }

        // Fetch HSN descriptions and UQC from products and hsn_codes table
        $this->db->select('p.hsn_code_id, h.hsn_code, h.description, u.symbol as uqc_symbol');
        $this->db->from('products p');
        $this->db->join('hsn_codes h', 'p.hsn_code_id = h.id', 'left');
        $this->db->join('units u', 'p.unit_id = u.id', 'left');
        $product_hsn_query = $this->db->get();
        $product_hsn_data = $product_hsn_query->result_array();

        $hsn_details_map = [];
        foreach ($product_hsn_data as $ph) {
            $hsn_details_map[$ph['hsn_code']] = [
                'desc' => $ph['description'],
                'uqc' => $ph['uqc_symbol'] // Use symbol as UQC, or map to official UQC codes
            ];
        }

        foreach ($hsn_summary as $hsn_code => &$data) {
            if (isset($hsn_details_map[$hsn_code])) {
                $data['desc'] = $hsn_details_map[$hsn_code]['desc'];
                $data['uqc'] = $hsn_details_map[$hsn_code]['uqc'];
            }
            // Round all values to 2 decimal places
            $data['txval'] = round($data['txval'], 2);
            $data['iamt'] = round($data['iamt'], 2);
            $data['camt'] = round($data['camt'], 2);
            $data['samt'] = round($data['samt'], 2);
            $data['csamt'] = round($data['csamt'], 2);
        }

        return array_values($hsn_summary);
    }

    public function get_gstr3b_data($from_date, $to_date)
    {
        $company_state_code = $this->_get_company_state_code();

        // 3.1 Outward taxable supplies (other than zero rated, nil rated and exempted)
        $outward_supplies = [
            'txval' => 0.00,
            'iamt' => 0.00,
            'camt' => 0.00,
            'samt' => 0.00,
            'csamt' => 0.00
        ];

        $this->db->select(
            'i.total_amount, i.total_discount, i.round_off, i.total_gst,
             id.cgst, id.sgst, id.gst_amount, id.cess_amount, id.final_price,
             s.state_code as pos_state_code'
        );
        $this->db->from('invoices i');
        $this->db->join('customers c', 'i.customer_id = c.id', 'left');
        $this->db->join('states s', 'c.state_id = s.id', 'left');
        $this->db->join('invoice_details id', 'i.id = id.invoice_id', 'left');
        $this->db->where('i.invoice_date >=', $from_date);
        $this->db->where('i.invoice_date <=', $to_date);
        $this->db->where('i.is_gst', 1); // Only GST invoices

        $query = $this->db->get();
        $sales_results = $query->result_array();

        foreach ($sales_results as $row) {
            $taxable_value = round($row['final_price'] / (1 + (($row['cgst'] + $row['sgst']) / 100)), 2);
            $outward_supplies['txval'] += $taxable_value;

            $supply_type = ($company_state_code === $row['pos_state_code']) ? 'INTRA' : 'INTER';

            if ($supply_type === 'INTER') {
                $outward_supplies['iamt'] += $row['gst_amount'];
            } else {
                $outward_supplies['camt'] += ($row['cgst'] * $taxable_value / 100);
                $outward_supplies['samt'] += ($row['sgst'] * $taxable_value / 100);
            }
            $outward_supplies['csamt'] += $row['cess_amount'];
        }

        // Round all values to 2 decimal places
        foreach ($outward_supplies as $key => $value) {
            $outward_supplies[$key] = round($value, 2);
        }

        // 4. Eligible ITC
        $itc_available = [
            'igd' => ['iamt' => 0.00, 'camt' => 0.00, 'samt' => 0.00, 'csamt' => 0.00], // ITC available (full)
            'isgd' => ['iamt' => 0.00, 'camt' => 0.00, 'samt' => 0.00, 'csamt' => 0.00], // ITC available (partial)
            'ineli' => ['iamt' => 0.00, 'camt' => 0.00, 'samt' => 0.00, 'csamt' => 0.00], // ITC ineligible
            'rchrg' => ['iamt' => 0.00, 'camt' => 0.00, 'samt' => 0.00, 'csamt' => 0.00], // ITC on reverse charge
            'othr' => ['iamt' => 0.00, 'camt' => 0.00, 'samt' => 0.00, 'csamt' => 0.00] // Other ITC
        ];

        $this->db->select(
            'pop.gst_rate, pop.gst_amount, pop.cess_amount, s.state_code as supplier_state_code'
        );
        $this->db->from('purchase_order_products pop');
        $this->db->join('purchase_orders po', 'pop.purchase_order_id = po.id', 'left');
        $this->db->join('suppliers s', 'po.supplier_id = s.id', 'left');
        $this->db->where('po.purchase_date >=', $from_date);
        $this->db->where('po.purchase_date <=', $to_date);
        $this->db->where('po.is_gst', 1); // Only GST purchases

        $query = $this->db->get();
        $purchase_results = $query->result_array();

        foreach ($purchase_results as $row) {
            $gst_rate = (float)$row['gst_rate'];
            $gst_amount = (float)$row['gst_amount'];
            $cess_amount = (float)$row['cess_amount'];
            $supplier_state_code = $row['supplier_state_code'];

            $supply_type = ($company_state_code === $supplier_state_code) ? 'INTRA' : 'INTER';

            if ($supply_type === 'INTER') {
                $itc_available['igd']['iamt'] += $gst_amount;
            } else {
                $itc_available['igd']['camt'] += ($gst_amount / 2);
                $itc_available['igd']['samt'] += ($gst_amount / 2);
            }
            $itc_available['igd']['csamt'] += $cess_amount;
        }

        // Round all ITC values
        foreach ($itc_available as $type => &$amounts) {
            foreach ($amounts as $key => $value) {
                $amounts[$key] = round($value, 2);
            }
        }

        return [
            'sup_details' => [
                'osup_det' => [
                    'txval' => $outward_supplies['txval'],
                    'iamt' => $outward_supplies['iamt'],
                    'camt' => $outward_supplies['camt'],
                    'samt' => $outward_supplies['samt'],
                    'csamt' => $outward_supplies['csamt']
                ]
            ],
            'itc_elg' => [
                'itc_avl' => [
                    ['ty' => 'IGST', 'val' => $itc_available['igd']['iamt']],
                    ['ty' => 'CGST', 'val' => $itc_available['igd']['camt']],
                    ['ty' => 'SGST', 'val' => $itc_available['igd']['samt']],
                    ['ty' => 'CESS', 'val' => $itc_available['igd']['csamt']]
                ]
            ]
        ];
    }

    public function get_company_gstin()
    {
        return $this->SettingsModel->get_setting('company_gstin');
    }

    public function get_company_state_code()
    {
        return $this->_get_company_state_code();
    }

    public function get_state_code_by_id($state_id)
    {
        return $this->_get_state_code_by_id($state_id);
    }
}