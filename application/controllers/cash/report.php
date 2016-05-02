<?php

/**
 * Report Controller Class
 *
 * @package     MyFina
 * @subpackage  Controllers
 * @author      Jamalulkhair Khairedin (jalte)
 * @copyright   Copyright (c) 2007, www.ridinglinux.org
 * @license		http://myfina.ridinglinux.org/public/myfina_license.txt
 * @link 		http://myfina.ridinglinux.org
 * @version 	0.1
 *
 */
require_once (APPPATH."controllers/secure_area.php");
class Report extends Secure_area
 {

	var $datestring = "%D %d %M %Y - %h:%i %a"; 

	function __construct()
	{
		 parent::__construct();
		 $time = time();
	     $this->today_date = mdate($this->datestring, $time);
	     
	     //Load the library
	     $this->load->library('session');
		
	}

	
	function index()
	{
		redirect('/cash/accounts/summary', 'location');
	}
	
	

	function income()
	{	

		if($this->input->post('month')) 
		{
			$this->session->set_userdata('income_pie_month', $this->input->post('month'));
			$this->session->set_userdata('income_pie_year', $this->input->post('year'));
			redirect('/report/income', 'location');
		}
		if (!$this->session->userdata('income_pie_month')) {
			$this->session->set_userdata('income_pie_month', mdate('%m', time()));
			$this->session->set_userdata('income_pie_year', mdate('%Y', time()));
		}
		
		$this->load->model('Reportmodel', '', TRUE);
		$data['page_title'] = 'Income Breakdown Report';

		$this->load->library('formdate');
		$formdate = new FormDate();
		$formdate->year['start'] = 2006;
		$formdate->year['end'] = DATE('Y');
		$formdate->month['values'] = 'numbers';
		$formdate->month['selected'] = $this->session->userdata('income_pie_month');
		$formdate->year['selected'] = $this->session->userdata('income_pie_year');
		$data['formdate'] = $formdate;

	   $data1 = $this->Reportmodel->get_income_pie($this->session->userdata('income_pie_month'),$this->session->userdata('income_pie_year'));
	   $graph_data=array();
	   foreach ($data1 as $k => $v){
			$graph_data[$v['cname']] =$data[$v['cname']] = abs($v['amount']);
		
			$data['label'][] = $v['cname']." (".abs($v['amount']).")";
			$data['plot'][] = abs($v['amount']); 	
		}
		if (!$data1) {$data['plot'][] = 1; $data['label'][] = "No Data";}
		
		$data = array('plot'=>$data['plot'],
				       'label'=>$data['label'],
				       'page_title'=> $data['page_title'],
				       'formdate'=>$formdate,
				       'data'=>$graph_data?$graph_data:array());  
		$data['body'] = $this->load->view('cash/report/income', $data, TRUE);
		$this->load->view('cash/page', $data);
	 }

	function expenses()
	{	

		if($this->input->post('month')) {
			$this->session->set_userdata('expenses_pie_month', $this->input->post('month'));
			$this->session->set_userdata('expenses_pie_year', $this->input->post('year'));
			redirect('/report/expenses', 'location');
		}
		if (!$this->session->userdata('expenses_pie_month')) {
			$this->session->set_userdata('expenses_pie_month', mdate('%m', time()));
			$this->session->set_userdata('expenses_pie_year', mdate('%Y', time()));
		}
	    
		$this->load->model('Reportmodel', '', TRUE);
		$data['page_title'] = 'Expenditure Breakdown Report';

		$this->load->library('formdate');
		$formdate = new FormDate();
		$formdate->year['start'] = 2006;
		$formdate->year['end'] = DATE('Y');
		$formdate->month['values'] = 'numbers';
		$formdate->month['selected'] = $this->session->userdata('expenses_pie_month');
		$formdate->year['selected'] = $this->session->userdata('expenses_pie_year');
		$data['formdate'] = $formdate;

		$data1 = $this->Reportmodel->get_expenses_pie($this->session->userdata('expenses_pie_month'),$this->session->userdata('expenses_pie_year'));
		$graph_data=array();
		foreach ($data1 as $k => $v){
			$graph_data[$v['cname']] =$data[$v['cname']] = abs($v['amount']);
		
			$data['label'][] = $v['cname']." (".abs($v['amount']).")";
			$data['plot'][] = abs($v['amount']); 	
		}
		if (!$data1) {$data['plot'][] = 1; $data['label'][] = "No Data";}
		
		$data = array('plot'=>$data['plot'],
				       'label'=>$data['label'],
				       'page_title'=> $data['page_title'],
				       'formdate'=>$formdate,
				       'data'=>$graph_data);  
		
		$data['body'] = $this->load->view('cash/report/income', $data, TRUE);
		$this->load->view('cash/page', $data);
	}

	function income_vs_expenses()
	{	

		if($this->input->post('month')) {
			$this->session->set_userdata('income_vs_expenses_mth', $this->input->post('month'));
			$this->session->set_userdata('income_vs_expenses_year', $this->input->post('year'));
			redirect('/report/income_vs_expenses', 'location');
		}
		if (!$this->session->userdata('income_vs_expenses_mth')) {
			$this->session->set_userdata('income_vs_expenses_mth', mdate('%m', time()));
			$this->session->set_userdata('income_vs_expenses_year', mdate('%Y', time()));
		}
		$this->load->model('Reportmodel', '', TRUE);
		$data['page_title'] = 'Income Vs Expenses';

		$this->load->library('formdate');
		$formdate = new FormDate();
		$formdate->year['start'] = 2006;
		$formdate->year['end'] = DATE('Y');
		$formdate->month['values'] = 'numbers';
		$formdate->month['selected'] = $this->session->userdata('income_vs_expenses_mth');
		$formdate->year['selected'] = $this->session->userdata('income_vs_expenses_year');
		$data['formdate'] = $formdate;
		$graph_data=array();
		$data1 = $this->Reportmodel->get_sum_income($this->session->userdata('income_vs_expenses_mth'),$this->session->userdata('income_vs_expenses_year'));
		$data2 = $this->Reportmodel->get_sum_expenses($this->session->userdata('income_vs_expenses_mth'),$this->session->userdata('income_vs_expenses_year'));
		$graph_data['income'] =$data['income'] = abs($data1['amount']);
		$graph_data['expenses'] = $data['expenses']=abs($data2['amount']);
		
        $data['data']=$graph_data;
		
		
		$data['balance'] = $data['income'] - $data['expenses'];
		
		$data['body'] = $this->load->view('cash/report/income_expense', $data, TRUE);
		$this->load->view('cash/page', $data);
	}
	
	function budgetvsactual()
	{	

		if($this->input->post('month')) {
			$this->session->set_userdata('budgetvsactual_mth', $this->input->post('month'));
			$this->session->set_userdata('budgetvsactual_yr', $this->input->post('year'));
			redirect('/report/budgetvsactual', 'location');
		}
		if (!$this->session->userdata('budgetvsactual_yr')) {
			$this->session->set_userdata('budgetvsactual_mth', mdate('%m', time()));
			$this->session->set_userdata('budgetvsactual_yr', mdate('%Y', time()));
		}
		
		$this->load->model('Reportmodel', '', TRUE);
		$this->load->model('Myfinamodel', '', TRUE);
		

		$this->load->library('formdate');
		$formdate = new FormDate();
		$formdate->year['start'] = 2006;
		$formdate->year['end'] = DATE('Y');
		$formdate->month['values'] = 'numbers';
		$formdate->month['selected'] = $this->session->userdata('budgetvsactual_mth');
		$formdate->year['selected'] = $this->session->userdata('budgetvsactual_yr');
		$data['formdate'] = $formdate;

		$data1 = $this->Reportmodel->get_expenses_pie($this->session->userdata('budgetvsactual_mth'),$this->session->userdata('budgetvsactual_yr'), 'all');
		$graph_data=array();
		 foreach ($data1 as $k => $v){
			$graph_data[$v['cname']] =$data[$v['cname']] = abs($v['amount']);
		
			$data['label'][] = $v['cname']." (".abs($v['amount']).")";
			$data['plot'][] = abs($v['amount']); 	
		}
		if (!$data1) {$data['plot'][] = 1; $data['label'][] = "No Data";}
		
		$data = array('plot'=>$data['plot'],
				       'label'=>$data['label'],
				       'page_title'=> 'Budget Vs Actual',
				       'formdate'=>$formdate,
				       'data'=>$graph_data);  
		$data['body'] = $this->load->view('cash/report/income', $data, TRUE);
		
		
		
		$this->load->view('cash/page', $data);
		}

}
?>
