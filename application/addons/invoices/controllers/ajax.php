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


include(APPPATH.'controllers/secure_area.php');

class Ajax extends Secure_area {

    public $ajax_controller = TRUE;
    public function __construct()
    {
    	parent::__construct();
    	$this->load->language('invoices/invoices');
    	/**set validations rules**/
    	$this->recurring_validation=array(
    			array(
    					'field' => 'sale_id',
    					'rules' => 'required'
    			),
    			array(
    					'field' => 'recur_start_date',
    					'label' => $this->lang->line('start_date'),
    					'rules' => 'required'
    			),
    			array(
    					'field' => 'recur_end_date',
    					'label' => $this->lang->line('end_date')
    			),
    			array(
    					'field' => 'recur_frequency',
    					'label' => $this->lang->line('every'),
    					'rules' => 'required'
    			),
    	);
    	
    }

    /**
     * 
     */
    public function create_recurring()
    {
    	//Load library
    	//------------
    	$this->load->library('form_validation');
    	
    	//Load models
    	//------------
    	 $this->load->model('invoices/invoice/mdl_invoices_recurring');
    	 
    	 
    	 $this->form_validation->set_rules($this->recurring_validation);
    	 $submited_data=$this->input->post();
        if ($this->form_validation->run())
        {
        	
        	$submited_data['recur_start_date']=date('Y-m-d',strtotime($this->input->post('recur_start_date')));
        	$submited_data['recur_end_date']=date('Y-m-d',strtotime($this->input->post('recur_end_date')));
       
        	
            if($this->mdl_invoices_recurring->create_recurring($submited_data))
            {
            	$this->session->set_flashdata('success', $this->lang->line('recurr_create_success'));
            	redirect('invoices/view/'.$submited_data['sale_id']);
            }
            else 
            {
            	$this->session->set_flashdata('error', $this->lang->line('recurr_create_error').' '.$this->lang->line('common_please_try_again'));
            	redirect('invoices/view/'.$submited_data['sale_id']);
            }
            
           
        }
        else
        {
        	$this->session->set_flashdata('error', validation_errors().' '.$this->lang->line('common_please_try_again'));
            redirect('invoices/view/'.$submited_data['sale_id']);
        }
        
    }

  /**
   * @Author Kamaro Lambert
   * @method get_recur_start_date()
   * 
   * 
   */
    public function get_recur_start_date()
    {
        $invoice_date = $this->input->post('invoice_date');
        $recur_frequency = $this->input->post('recur_frequency');
        
        echo increment_user_date($invoice_date, $recur_frequency);
    }

    public function modal_copy_invoice()
    {
        $this->load->module('layout');

        $this->load->model('invoices/mdl_invoices');
        $this->load->model('invoice_groups/mdl_invoice_groups');
        $this->load->model('tax_rates/mdl_tax_rates');

        $data = array(
            'invoice_groups' => $this->mdl_invoice_groups->get()->result(),
            'tax_rates'      => $this->mdl_tax_rates->get()->result(),
            'invoice_id'     => $this->input->post('invoice_id'),
            'invoice'        => $this->mdl_invoices->where('fi_invoices.invoice_id', $this->input->post('invoice_id'))->get()->row()
        );

        $this->layout->load_view('invoices/modal_copy_invoice', $data);
    }

    public function copy_invoice()
    {
        $this->load->model('invoices/mdl_invoices');
        $this->load->model('invoices/mdl_items');
        $this->load->model('invoices/mdl_invoice_tax_rates');

        if ($this->mdl_invoices->run_validation())
        {
            $target_id = $this->mdl_invoices->save();
            $source_id = $this->input->post('invoice_id');

            $this->mdl_invoices->copy_invoice($source_id, $target_id);

            $response = array(
                'success'    => 1,
                'invoice_id' => $target_id
            );
        }
        else
        {
            $response = array(
                'success'           => 0,
                'validation_errors' => json_errors()
            );
        }

        echo json_encode($response);
    }

}

?>