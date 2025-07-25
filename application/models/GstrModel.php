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
            'i.invoice_no, i.invoice_date, i.total_amount, i.total_discount, i.round_off, i.is_reverse_charge, i.supply_type,
             c.gst_number as cust_gstin, c.customer_name as receiver_name, s.state_code as pos_state_code,
             id.quantity, id.price, id.discount, id.cess_amount, id.final_price,
             h.hsn_code, h.gst_rate as hsn_gst_rate'
        );
        $this->db->from('invoices i');
        $this->db->join('customers c', 'i.customer_id = c.id', 'left');
        $this->db->join('states s', 'c.state_id = s.id', 'left');
        $this->db->join('invoice_details id', 'i.id = id.invoice_id', 'left');
        $this->db->join('products p', 'id.product_id = p.id', 'left');
        $this->db->join('hsn_codes h', 'p.hsn_code_id = h.id', 'left');
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
            $hsn_gst_rate = (float)$row['hsn_gst_rate'];

            // Determine supply type (inter-state or intra-state)
            $supply_type = ($company_state_code === $pos_state_code) ? 'INTRA' : 'INTER';

            // Calculate taxable value for the item
            $item_taxable_value = $row['quantity'] * $row['price'] - $row['discount'];

            // Recalculate GST amounts based on HSN rate
            $item_gst_amount = $item_taxable_value * ($hsn_gst_rate / 100);
            $item_cgst = ($supply_type === 'INTRA') ? ($item_taxable_value * ($hsn_gst_rate / 200)) : 0.00;
            $item_sgst = ($supply_type === 'INTRA') ? ($item_taxable_value * ($hsn_gst_rate / 200)) : 0.00;
            $item_iamt = ($supply_type === 'INTER') ? $item_gst_amount : 0.00;

            if (!isset($b2b_invoices[$cust_gstin])) {
                $b2b_invoices[$cust_gstin] = [
                    'ctin' => $cust_gstin,
                    'receiver_name' => $row['receiver_name'], // Add receiver name here
                    'inv' => []
                ];
            }

            if (!isset($b2b_invoices[$cust_gstin]['inv'][$invoice_no])) {
                $b2b_invoices[$cust_gstin]['inv'][$invoice_no] = [
                    'inum' => $invoice_no,
                    'idt' => date('d-m-Y', strtotime($row['invoice_date'])),
                    'val' => (float)($row['total_amount'] + $row['total_discount'] - $row['round_off']), // Use invoice total for overall value
                    'pos' => $pos_state_code,
                    'rchrg' => $row['is_reverse_charge'] ? 'Y' : 'N',
                    'inv_typ' => 'R', // Regular
                    'itms' => []
                ];
            }

            $b2b_invoices[$cust_gstin]['inv'][$invoice_no]['itms'][] = [
                'num' => count($b2b_invoices[$cust_gstin]['inv'][$invoice_no]['itms']) + 1,
                'itm_det' => [
                    'txval' => (float) round($item_taxable_value, 2),
                    'rt' => (float)$hsn_gst_rate,
                    'iamt' => (float) round($item_iamt, 2),
                    'camt' => (float) round($item_cgst, 2),
                    'samt' => (float) round($item_sgst, 2),
                    'csamt' => (float) round($row['cess_amount'], 2)
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
             id.quantity, id.price, id.discount, id.cess_amount, id.final_price,
             h.hsn_code, h.gst_rate as hsn_gst_rate'
        );
        $this->db->from('invoices i');
        $this->db->join('customers c', 'i.customer_id = c.id', 'left');
        $this->db->join('states s', 'c.state_id = s.id', 'left');
        $this->db->join('invoice_details id', 'i.id = id.invoice_id', 'left');
        $this->db->join('products p', 'id.product_id = p.id', 'left');
        $this->db->join('hsn_codes h', 'p.hsn_code_id = h.id', 'left');
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
            $hsn_gst_rate = (float)$row['hsn_gst_rate'];

            // Determine supply type (inter-state or intra-state)
            $supply_type = ($company_state_code === $pos_state_code) ? 'INTRA' : 'INTER';

            // B2CL only applies to inter-state supplies
            if ($supply_type === 'INTRA') {
                continue;
            }

            // Calculate taxable value for the item
            $item_taxable_value = $row['quantity'] * $row['price'] - $row['discount'];

            // Recalculate GST amounts based on HSN rate
            $item_gst_amount = $item_taxable_value * ($hsn_gst_rate / 100);
            $item_cgst = ($supply_type === 'INTRA') ? ($item_taxable_value * ($hsn_gst_rate / 200)) : 0.00;
            $item_sgst = ($supply_type === 'INTRA') ? ($item_taxable_value * ($hsn_gst_rate / 200)) : 0.00;
            $item_iamt = ($supply_type === 'INTER') ? $item_gst_amount : 0.00;

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
                    'val' => (float)($row['total_amount'] + $row['total_discount'] - $row['round_off']), // Use invoice total for overall value
                    'itms' => []
                ];
            }

            $b2cl_invoices[$pos_state_code]['inv'][$invoice_no]['itms'][] = [
                'num' => count($b2cl_invoices[$pos_state_code]['inv'][$invoice_no]['itms']) + 1,
                'itm_det' => [
                    'txval' => (float) round($item_taxable_value, 2),
                    'rt' => (float)$hsn_gst_rate,
                    'iamt' => (float) round($item_iamt, 2),
                    'csamt' => (float) round($row['cess_amount'], 2)
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
            's.state_code as pos_state_code, id.quantity, id.price, id.discount, id.cess_amount,
             h.hsn_code, h.gst_rate as hsn_gst_rate'
        );
        $this->db->from('invoices i');
        $this->db->join('customers c', 'i.customer_id = c.id', 'left');
        $this->db->join('states s', 'c.state_id = s.id', 'left');
        $this->db->join('invoice_details id', 'i.id = id.invoice_id', 'left');
        $this->db->join('products p', 'id.product_id = p.id', 'left');
        $this->db->join('hsn_codes h', 'p.hsn_code_id = h.id', 'left');
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
            $hsn_gst_rate = (float)$row['hsn_gst_rate'];

            // Calculate taxable value for the item
            $item_taxable_value = $row['quantity'] * $row['price'] - $row['discount'];

            // Determine supply type (inter-state or intra-state)
            $supply_type = ($company_state_code === $pos_state_code) ? 'INTRA' : 'INTER';

            // Recalculate GST amounts based on HSN rate
            $item_gst_amount = $item_taxable_value * ($hsn_gst_rate / 100);
            $item_cgst = ($supply_type === 'INTRA') ? ($item_taxable_value * ($hsn_gst_rate / 200)) : 0.00;
            $item_sgst = ($supply_type === 'INTRA') ? ($item_taxable_value * ($hsn_gst_rate / 200)) : 0.00;
            $item_iamt = ($supply_type === 'INTER') ? $item_gst_amount : 0.00;

            $key = $pos_state_code . '_' . $hsn_gst_rate . '_' . $row['cess_amount'] . '_' . $supply_type;

            if (!isset($b2cs_summary[$key])) {
                $b2cs_summary[$key] = [
                    'pos' => $pos_state_code,
                    'sp_typ' => ($supply_type === 'INTER') ? 'INTER' : 'INTRA',
                    'txval' => 0.00,
                    'rt' => $hsn_gst_rate,
                    'iamt' => 0.00,
                    'camt' => 0.00,
                    'samt' => 0.00,
                    'csamt' => 0.00
                ];
            }

            $b2cs_summary[$key]['txval'] += $item_taxable_value;
            $b2cs_summary[$key]['iamt'] += $item_iamt;
            $b2cs_summary[$key]['camt'] += $item_cgst;
            $b2cs_summary[$key]['samt'] += $item_sgst;
            $b2cs_summary[$key]['csamt'] += (float)$row['cess_amount'];
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
        $company_state_code = $this->_get_company_state_code();

        $this->db->select(
            'h.hsn_code, h.description, u.symbol as uqc_symbol,
             SUM(id.quantity) as total_qty,
             SUM(id.quantity * id.price - id.discount) as total_taxable_value,
             SUM(CASE WHEN s.state_code != ' . $company_state_code . ' THEN (id.quantity * id.price - id.discount) * (h.gst_rate / 100) ELSE 0 END) as total_iamt,
             SUM(CASE WHEN s.state_code = ' . $company_state_code . ' THEN (id.quantity * id.price - id.discount) * (h.gst_rate / 200) ELSE 0 END) as total_camt,
             SUM(CASE WHEN s.state_code = ' . $company_state_code . ' THEN (id.quantity * id.price - id.discount) * (h.gst_rate / 200) ELSE 0 END) as total_samt,
             SUM(id.cess_amount) as total_csamt,
             h.gst_rate as rt'
        );
        $this->db->from('invoices i');
        $this->db->join('invoice_details id', 'i.id = id.invoice_id', 'left');
        $this->db->join('products p', 'id.product_id = p.id', 'left');
        $this->db->join('hsn_codes h', 'p.hsn_code_id = h.id', 'left');
        $this->db->join('customers c', 'i.customer_id = c.id', 'left');
        $this->db->join('states s', 'c.state_id = s.id', 'left');
        $this->db->join('units u', 'p.unit_id = u.id', 'left');
        $this->db->where('i.invoice_date >=', $from_date);
        $this->db->where('i.invoice_date <=', $to_date);
        $this->db->where('i.is_gst', 1); // Only GST invoices
        $this->db->group_by('h.hsn_code, h.description, u.symbol, h.gst_rate');
        $query = $this->db->get();
        $results = $query->result_array();

        $hsn_summary = [];

        foreach ($results as $row) {
            $hsn_code = $row['hsn_code'];
            $hsn_summary[$hsn_code] = [
                'num' => $hsn_code,
                'desc' => $row['description'],
                'uqc' => $row['uqc_symbol'],
                'qty' => (float)$row['total_qty'],
                'txval' => (float)round($row['total_taxable_value'], 2),
                'iamt' => (float)round($row['total_iamt'], 2),
                'camt' => (float)round($row['total_camt'], 2),
                'samt' => (float)round($row['total_samt'], 2),
                'csamt' => (float)round($row['total_csamt'], 2),
                'rt' => (float)$row['rt']
            ];
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
             id.quantity, id.price, id.discount, id.cess_amount, id.final_price,
             s.state_code as pos_state_code,
             h.gst_rate as hsn_gst_rate'
        );
        $this->db->from('invoices i');
        $this->db->join('customers c', 'i.customer_id = c.id', 'left');
        $this->db->join('states s', 'c.state_id = s.id', 'left');
        $this->db->join('invoice_details id', 'i.id = id.invoice_id', 'left');
        $this->db->join('products p', 'id.product_id = p.id', 'left');
        $this->db->join('hsn_codes h', 'p.hsn_code_id = h.id', 'left');
        $this->db->where('i.invoice_date >=', $from_date);
        $this->db->where('i.invoice_date <=', $to_date);
        $this->db->where('i.is_gst', 1); // Only GST invoices

        $query = $this->db->get();
        $sales_results = $query->result_array();

        foreach ($sales_results as $row) {
            $hsn_gst_rate = (float)$row['hsn_gst_rate'];
            $item_taxable_value = $row['quantity'] * $row['price'] - $row['discount'];

            $supply_type = ($company_state_code === $row['pos_state_code']) ? 'INTRA' : 'INTER';

            // Recalculate GST amounts based on HSN rate
            $item_gst_amount = $item_taxable_value * ($hsn_gst_rate / 100);
            $item_cgst = ($supply_type === 'INTRA') ? ($item_taxable_value * ($hsn_gst_rate / 200)) : 0.00;
            $item_sgst = ($supply_type === 'INTRA') ? ($item_taxable_value * ($hsn_gst_rate / 200)) : 0.00;
            $item_iamt = ($supply_type === 'INTER') ? $item_gst_amount : 0.00;

            $outward_supplies['txval'] += $item_taxable_value;
            $outward_supplies['iamt'] += $item_iamt;
            $outward_supplies['camt'] += $item_cgst;
            $outward_supplies['samt'] += $item_sgst;
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
            'pop.qnt as quantity, pop.purchase_price, pop.discount, pop.cess_amount, 
             st.state_code as supplier_state_code,
             h.gst_rate as hsn_gst_rate'
        );
        $this->db->from('purchase_order_products pop');
        $this->db->join('purchase_orders po', 'pop.purchase_order_id = po.id', 'left');
        $this->db->join('suppliers s', 'po.supplier_id = s.id', 'left');
        $this->db->join('states st', 's.state_id = st.id', 'left');
        $this->db->join('products p', 'pop.product_id = p.id', 'left');
        $this->db->join('hsn_codes h', 'p.hsn_code_id = h.id', 'left');
        $this->db->where('po.purchase_date >=', $from_date);
        $this->db->where('po.purchase_date <=', $to_date);
        $this->db->where('po.is_gst', 1); // Only GST purchases

        $query = $this->db->get();
        $purchase_results = $query->result_array();

        foreach ($purchase_results as $row) {
            $hsn_gst_rate = (float)$row['hsn_gst_rate'];
            $item_taxable_value = $row['quantity'] * $row['purchase_price'] - $row['discount'];

            $supplier_state_code = $row['supplier_state_code'];

            $supply_type = ($company_state_code === $supplier_state_code) ? 'INTRA' : 'INTER';

            // Recalculate GST amounts based on HSN rate
            $item_gst_amount = $item_taxable_value * ($hsn_gst_rate / 100);
            $item_cgst = ($supply_type === 'INTRA') ? ($item_taxable_value * ($hsn_gst_rate / 200)) : 0.00;
            $item_sgst = ($supply_type === 'INTRA') ? ($item_taxable_value * ($hsn_gst_rate / 200)) : 0.00;
            $item_iamt = ($supply_type === 'INTER') ? $item_gst_amount : 0.00;

            $itc_available['igd']['iamt'] += $item_iamt;
            $itc_available['igd']['camt'] += $item_cgst;
            $itc_available['igd']['samt'] += $item_sgst;
            $itc_available['igd']['csamt'] += (float)$row['cess_amount'];
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
