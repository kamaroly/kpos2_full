<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * FusionInvoice
 * 
 * A free and open source web based invoicing system
 *
 * @package		FusionInvoice
 * @author		Jesse Terry
 * @copyright	Copyright (c) 2012 - 2013, Jesse Terry
 * @license		http://www.fusioninvoice.com/support/page/license-agreement
 * @link		http://www.fusioninvoice.com
 * 
 */

class Invoices extends Guest_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model('invoices/mdl_invoices');
    }

    public function index()
    {
        // Display open invoices by default
        redirect('guest/invoices/status/open');
    }

    public function status($status = 'open', $page = 0)
    {
        // Determine which group of invoices to load
        switch ($status)
        {
            case 'open':
                $this->mdl_invoices->is_open()->where_in('fi_invoices.client_id', $this->user_clients);
                break;
            case 'closed':
                $this->mdl_invoices->is_closed()->where_in('fi_invoices.client_id', $this->user_clients);
                break;
            case 'overdue':
                $this->mdl_invoices->is_overdue()->where_in('fi_invoices.client_id', $this->user_clients);
                break;
        }

        $this->mdl_invoices->paginate(site_url('guest/invoices/status/' . $status), $page);
        $invoices = $this->mdl_invoices->result();

        $this->layout->set(
            array(
                'invoices'           => $invoices,
                'status'             => $status,
                'filter_display'     => TRUE,
                'filter_placeholder' => lang('filter_invoices'),
                'filter_method'      => 'filter_invoices'
            )
        );

        $this->layout->buffer('content', 'guest/invoices_index');
        $this->layout->render('layout_guest');
    }

    public function view($invoice_id)
    {
        $this->load->model('invoices/mdl_items');
        $this->load->model('invoices/mdl_invoice_tax_rates');
        
        $invoice = $this->mdl_invoices->where('fi_invoices.invoice_id', $invoice_id)->where_in('fi_invoices.client_id', $this->user_clients)->get()->row();
        
        if (!$invoice)
        {
            show_404();
        }

        $this->layout->set(
            array(
                'invoice'           => $invoice,
                'items'             => $this->mdl_items->where('invoice_id', $invoice_id)->get()->result(),
                'invoice_tax_rates' => $this->mdl_invoice_tax_rates->where('invoice_id', $invoice_id)->get()->result(),
                'invoice_id'        => $invoice_id
            )
        );

        $this->layout->buffer(
            array(
                array('content', 'guest/invoices_view')
            )
        );

        $this->layout->render('layout_guest');
    }

    public function generate_pdf($invoice_id, $stream = TRUE, $invoice_template = NULL)
    {
        $this->load->model('invoices/mdl_items');
        $this->load->model('invoices/mdl_invoice_tax_rates');

        $invoice = $this->mdl_invoices->where('fi_invoices.invoice_id', $invoice_id)->where_in('fi_invoices.client_id', $this->user_clients)->get()->row();

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

        return pdf_create($html, lang('invoice') . '_' . $invoice->invoice_number, $stream);
    }

}

?>