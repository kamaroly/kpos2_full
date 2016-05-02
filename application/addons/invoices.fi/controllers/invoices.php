<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * KPOS
 * 
 * A free and open source web based invoicing system
 *
 * @package		Kpos
 * @author		Jesse Terry modified by Kamaro Lambert(kamaroly@gmail.com)
 * @copyright	Copyright (c) 2012 - 2013, Jesse Terry
 * @license		http://www.kamaroly.com/support/page/license-agreement
 * @link		http://www.kamaroly.com
 * 
 */

class Invoices extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        //load models...
        //--------------
        $invoices=$this->load->model('invoices/mdl_invoices');
        //load Languages..
        //----------------
        $invoices=$this->load->language(array('invoices/invoices',
        		                              'invoices/merchant'));
    }

    public function index()
    {
        // Display open invoices by default
        redirect('invoices/status/open');
    }

    public function status($status = 'open', $page = 0)
    {
        
        
        
        $this->mdl_invoices->default_select();

        // Determine which group of invoices to load
        $invoices=$this->mdl_invoices->get_invoices($status)->result();
      
       
        $this->data=
            array(
                'invoices'           => $invoices,
                'status'             => $status,
                'filter_display'     => TRUE,
                'filter_placeholder' => $this->lang->line('filter_invoices'),
                'filter_method'      => 'filter_invoices'
            );

        $this->load->view('invoices/index',$this->data);
       
    }

    public function client($client_id, $status = 'open', $page = 0)
    {
        // Determine which group of invoices to load
        switch ($status)
        {
            case 'open':
                $this->mdl_invoices->by_client($client_id)->is_open();
                break;
            case 'closed':
                $this->mdl_invoices->by_client($client_id)->is_closed();
                break;
            case 'overdue':
                $this->mdl_invoices->by_client($client_id)->is_overdue();
                break;
        }

        $this->mdl_invoices->paginate(site_url('invoices/client/' . $client_id . '/' . $status), $page);
        $invoices = $this->mdl_invoices->result();

        $this->layout->set(
            array(
                'client_id'          => $client_id,
                'invoices'           => $invoices,
                'status'             => $status,
                'filter_display'     => TRUE,
                'filter_placeholder' => lang('filter_invoices'),
                'filter_method'      => 'filter_invoices'
            )
        );

        $this->layout->buffer('content', 'invoices/index_client');
        $this->layout->render();
    }

    public function view($invoice_id)
    {
        $this->load->model('mdl_items');
        $this->load->model('tax_rates/mdl_tax_rates');
        $this->load->model('payment_methods/mdl_payment_methods');
        $this->load->model('mdl_invoice_tax_rates');
        $this->load->model('custom_fields/mdl_custom_fields');

        $this->load->module('payments');

        $this->load->model('custom_fields/mdl_invoice_custom');

        $invoice_custom = $this->mdl_invoice_custom->where('invoice_id', $invoice_id)->get();

        if ($invoice_custom->num_rows())
        {
            $invoice_custom = $invoice_custom->row();

            unset($invoice_custom->invoice_id, $invoice_custom->invoice_custom_id);

            foreach ($invoice_custom as $key => $val)
            {
                $this->mdl_invoices->set_form_value('custom[' . $key . ']', $val);
            }
        }

        $invoice = $this->mdl_invoices->get_by_id($invoice_id);

        if (!$invoice)
        {
            show_404();
        }

        $this->layout->set(
            array(
                'invoice'           => $invoice,
                'items'             => $this->mdl_items->where('invoice_id', $invoice_id)->get()->result(),
                'invoice_id'        => $invoice_id,
                'tax_rates'         => $this->mdl_tax_rates->get()->result(),
                'invoice_tax_rates' => $this->mdl_invoice_tax_rates->where('invoice_id', $invoice_id)->get()->result(),
                'payment_methods'   => $this->mdl_payment_methods->get()->result(),
                'custom_fields'     => $this->mdl_custom_fields->by_table('fi_invoice_custom')->get()->result(),
                'custom_js_vars'    => array(
                    'currency_symbol'           => $this->mdl_settings->setting('currency_symbol'),
                    'currency_symbol_placement' => $this->mdl_settings->setting('currency_symbol_placement'),
                    'decimal_point'             => $this->mdl_settings->setting('decimal_point')
                )
            )
        );

        $this->layout->buffer(
            array(
                array('modal_delete_invoice', 'invoices/modal_delete_invoice'),
                array('modal_add_invoice_tax', 'invoices/modal_add_invoice_tax'),
                array('modal_add_payment', 'payments/modal_add_payment'),
                array('content', 'invoices/view')
            )
        );

        $this->layout->render();
    }

    public function calendar()
    {
        $this->layout->buffer(
            array(
                array('calendar', 'calendar/full_calendar'),
                array('content', 'invoices/calendar')
            )
        );

        $this->layout->render();
    }

    public function delete($invoice_id)
    {
        // Delete the invoice
        $this->mdl_invoices->delete($invoice_id);

        // Redirect to invoice index
        redirect('invoices/index');
    }

    public function delete_item($invoice_id, $item_id)
    {
        // Delete invoice item
        $this->load->model('mdl_items');
        $this->mdl_items->delete($item_id);

        // Redirect to invoice view
        redirect('invoices/view/' . $invoice_id);
    }

    public function generate_pdf($invoice_id, $stream = TRUE, $invoice_template = NULL)
    {
        $this->load->model('mdl_items');
        $this->load->model('mdl_invoice_tax_rates');

        $invoice = $this->mdl_invoices->get_by_id($invoice_id);

        if (!$invoice_template)
        {
            $invoice_template = $this->mdl_settings->setting('default_invoice_template');
        }

        $data = array(
            'invoice'           => $invoice,
            'invoice_tax_rates' => $this->mdl_invoice_tax_rates->where('invoice_id', $invoice_id)->get()->result(),
            'items'             => $this->mdl_items->where('invoice_id', $invoice_id)->get()->result(),
            'output_type'       => 'pdf'
        );

        $html = $this->load->view('invoice_templates/' . $invoice_template, $data, TRUE);

        $this->load->helper('mpdf');

        echo pdf_create($html, lang('invoice') . '_' . $invoice->invoice_number, $stream);
    }

    public function delete_invoice_tax($invoice_id, $invoice_tax_rate_id)
    {
        $this->load->model('mdl_invoice_tax_rates');
        $this->mdl_invoice_tax_rates->delete($invoice_tax_rate_id);

        $this->load->model('mdl_invoice_amounts');
        $this->mdl_invoice_amounts->calculate($invoice_id);

        redirect('invoices/view/' . $invoice_id);
    }

    public function recalculate_all_invoices()
    {
        $this->db->select('invoice_id');
        $invoice_ids = $this->db->get('fi_invoices')->result();

        $this->load->model('mdl_invoice_amounts');

        foreach ($invoice_ids as $invoice_id)
        {
            $this->mdl_invoice_amounts->calculate($invoice_id->invoice_id);
        }
    }

}

?>