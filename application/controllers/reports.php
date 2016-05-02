<?php

require_once ("secure_area.php");

class Reports extends Secure_area 
{	
	function __construct()
	{
		parent::__construct('reports');
		$this->load->helper('report');		
	}
	
	//Initial report listing screen
	function index()
	{
	
		$start_date= date('Y-m-d', strtotime('-7 day', date(now())));
		$end_date=date('Y-m-d');
		$sale_type='all';
		
		
		$data['graphical_summary_sales_graph']=$this->graphical_summary_sales_graph($start_date, $end_date, $sale_type);
		$data['income_vs_expenses']=$this->income_vs_expenses();
		$data['graphical_summary_items_graph']=$this->graphical_summary_items_graph($start_date, $end_date, $sale_type);
		$data['graphical_summary_payments_graph']=$this->graphical_summary_payments_graph($start_date, $end_date, $sale_type);
		
		
		$data_display['body']=$this->load->view("reports/dashboard",$data,true);
		$data_display['title']=$this->lang->line('reports_dashboard_report');
		$this->load->view("reports/page",$data_display);	
	}
	
	function _get_common_report_data()
	{
		$data = array();
		$data['report_date_range_simple'] = get_simple_date_ranges();
		$data['months'] = get_months();
		$data['days'] = get_days();
		$data['years'] = get_years();
		$data['selected_month']=date('n');
		$data['selected_day']=date('d');
		$data['selected_year']=date('Y');	
	
		return $data;
	}
	
	//Input for reports that require only a date range and an export to excel. (see routes.php to see that all summary reports route here)
	function date_input_excel_export()
	{
		$data = $this->_get_common_report_data();
		$data['body']=$this->load->view("reports/date_input_excel_export",$data,TRUE);	
		$this->load->view("reports/page",$data);
	}
	
	//Summary sales report
	function summary_sales($start_date, $end_date, $sale_type, $export_excel=0)
	{
		$this->load->model('reports/Summary_sales');
		$model = $this->Summary_sales;
		$tabular_data = array();
		$report_data = $model->getData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		
		foreach($report_data as $row)
		{
			$tabular_data[] = array($row['sale_date'], to_currency($row['subtotal']), to_currency($row['total']), to_currency($row['tax']),to_currency($row['profit']));
		}

		$data = array(
			"title" => $this->lang->line('reports_sales_summary_report'),
			"subtitle" => date('m/d/Y', strtotime($start_date)) .'-'.date('m/d/Y', strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"data" => $tabular_data,
			"summary_data" => $model->getSummaryData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type)),
			"export_excel" => $export_excel
		);

		$data['body']=$this->load->view("reports/tabular",$data,TRUE);
		$this->load->view("reports/page",$data);
	}
	
	//Summary categories report
	function summary_categories($start_date, $end_date, $sale_type, $export_excel=0)
	{
		$this->load->model('reports/Summary_categories');
		$model = $this->Summary_categories;
		$tabular_data = array();
		$report_data = $model->getData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		
		foreach($report_data as $row)
		{
			$tabular_data[] = array($row['category_id'], to_currency($row['subtotal']), to_currency($row['total']), to_currency($row['tax']),to_currency($row['profit']));
		}

		$data = array(
			"title" => $this->lang->line('reports_categories_summary_report'),
			"subtitle" => date('m/d/Y', strtotime($start_date)) .'-'.date('m/d/Y', strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"data" => $tabular_data,
			"summary_data" => $model->getSummaryData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type)),
			"export_excel" => $export_excel
		);

		$data['body']=$this->load->view("reports/tabular",$data,TRUE);
		$this->load->view("reports/page",$data);
		
	}
	
	//Summary customers report
	function summary_customers($start_date, $end_date, $sale_type, $export_excel=0)
	{
		$this->load->model('reports/Summary_customers');
		$model = $this->Summary_customers;
		$tabular_data = array();
		$report_data = $model->getData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		
		foreach($report_data as $row)
		{
			$tabular_data[] = array($row['customer'], to_currency($row['subtotal']), to_currency($row['total']), to_currency($row['tax']),to_currency($row['profit']));
		}

		$data = array(
			"title" => $this->lang->line('reports_customers_summary_report'),
			"subtitle" => date('m/d/Y', strtotime($start_date)) .'-'.date('m/d/Y', strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"data" => $tabular_data,
			"summary_data" => $model->getSummaryData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type)),
			"export_excel" => $export_excel
		);

		$data['body']=$this->load->view("reports/tabular",$data,TRUE);
		$this->load->view("reports/page",$data);
	}
	
	//Summary suppliers report
	function summary_suppliers($start_date, $end_date, $sale_type, $export_excel=0)
	{
		$this->load->model('reports/Summary_suppliers');
		$model = $this->Summary_suppliers;
		$tabular_data = array();
		$report_data = $model->getData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		
		foreach($report_data as $row)
		{
			$tabular_data[] = array($row['supplier'], to_currency($row['subtotal']), to_currency($row['total']), to_currency($row['tax']),to_currency($row['profit']));
		}

		$data = array(
			"title" => $this->lang->line('reports_suppliers_summary_report'),
			"subtitle" => date('m/d/Y', strtotime($start_date)) .'-'.date('m/d/Y', strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"data" => $tabular_data,
			"summary_data" => $model->getSummaryData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type)),
			"export_excel" => $export_excel
		);

		$data['body']=$this->load->view("reports/tabular",$data,TRUE);
		$this->load->view("reports/page",$data);
	}
	
	//Summary items report
	function summary_items($start_date, $end_date, $sale_type, $export_excel=0)
	{
		$this->load->model('reports/Summary_items');
		$model = $this->Summary_items;
		$tabular_data = array();
		$report_data = $model->getData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		
		foreach($report_data as $row)
		{
			$tabular_data[] = array(character_limiter($row['name'], 16), $row['quantity_purchased'], to_currency($row['subtotal']), to_currency($row['total']), to_currency($row['tax']),to_currency($row['profit']));
		}

		$data = array(
			"title" => $this->lang->line('reports_items_summary_report'),
			"subtitle" => date('m/d/Y', strtotime($start_date)) .'-'.date('m/d/Y', strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"data" => $tabular_data,
			"summary_data" => $model->getSummaryData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type)),
			"export_excel" => $export_excel
		);
		
		$data['body']=$this->load->view("reports/tabular",$data,TRUE);
		$this->load->view("reports/page",$data);
	}
	
	//Summary employees report
	function summary_employees($start_date, $end_date, $sale_type, $export_excel=0)
	{
		$this->load->model('reports/Summary_employees');
		$model = $this->Summary_employees;
		$tabular_data = array();
		$report_data = $model->getData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		
		foreach($report_data as $row)
		{
			$tabular_data[] = array($row['employee'], to_currency($row['subtotal']), to_currency($row['total']), to_currency($row['tax']),to_currency($row['profit']));
		}

		$data = array(
			"title" => $this->lang->line('reports_employees_summary_report'),
			"subtitle" => date('m/d/Y', strtotime($start_date)) .'-'.date('m/d/Y', strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"data" => $tabular_data,
			"summary_data" => $model->getSummaryData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type)),
			"export_excel" => $export_excel
		);

		$data['body']=$this->load->view("reports/tabular",$data,TRUE);
		$this->load->view("reports/page",$data);
	}
	
	//Summary taxes report
	function summary_taxes($start_date, $end_date, $sale_type, $export_excel=0)
	{
		$this->load->model('reports/Summary_taxes');
		$model = $this->Summary_taxes;
		$tabular_data = array();
		$report_data = $model->getData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		
		foreach($report_data as $row)
		{
			$tabular_data[] = array($row['percent'], to_currency($row['subtotal']), to_currency($row['total']), to_currency($row['tax']));
		}

		$data = array(
			"title" => $this->lang->line('reports_taxes_summary_report'),
			"subtitle" => date('m/d/Y', strtotime($start_date)) .'-'.date('m/d/Y', strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"data" => $tabular_data,
			"summary_data" => $model->getSummaryData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type)),
			"export_excel" => $export_excel
		);

		$data['body']=$this->load->view("reports/tabular",$data,TRUE);
		$this->load->view("reports/page",$data);
	}
	
	//Summary discounts report
	function summary_discounts($start_date, $end_date, $sale_type, $export_excel=0)
	{
		$this->load->model('reports/Summary_discounts');
		$model = $this->Summary_discounts;
		$tabular_data = array();
		$report_data = $model->getData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		
		foreach($report_data as $row)
		{
			$tabular_data[] = array($row['discount_percent'],$row['count']);
		}

		$data = array(
			"title" => $this->lang->line('reports_discounts_summary_report'),
			"subtitle" => date('m/d/Y', strtotime($start_date)) .'-'.date('m/d/Y', strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"data" => $tabular_data,
			"summary_data" => $model->getSummaryData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type)),
			"export_excel" => $export_excel
		);

		$data['body']=$this->load->view("reports/tabular",$data,TRUE);
		$this->load->view("reports/page",$data);
	}
	
	function summary_payments($start_date, $end_date, $sale_type, $export_excel=0)
	{
		$this->load->model('reports/Summary_payments');
		$model = $this->Summary_payments;
		$tabular_data = array();
		$report_data = $model->getData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		
		foreach($report_data as $row)
		{
			$tabular_data[] = array($row['payment_type'],to_currency($row['payment_amount']));
		}

		$data = array(
			"title" => $this->lang->line('reports_payments_summary_report'),
			"subtitle" => date('m/d/Y', strtotime($start_date)) .'-'.date('m/d/Y', strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"data" => $tabular_data,
			"summary_data" => $model->getSummaryData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type)),
			"export_excel" => $export_excel
		);

		$data['body']=$this->load->view("reports/tabular",$data,TRUE);
		$this->load->view("reports/page",$data);
	}
	
	//Input for reports that require only a date range. (see routes.php to see that all graphical summary reports route here)
	function date_input()
	{
		$data = $this->_get_common_report_data();
		$data['body']=$this->load->view("reports/date_input",$data,TRUE);	
		$this->load->view("reports/page",$data);
		
	}
	
	//Graphical summary sales report
	function graphical_summary_sales($start_date, $end_date, $sale_type)
	{
		$this->load->model('reports/Summary_sales');
		$model = $this->Summary_sales;

		$data = array(
			"title" => $this->lang->line('reports_sales_summary_report'),
			"graph" =>$this->graphical_summary_sales_graph($start_date, $end_date, $sale_type),
			"subtitle" => date('m/d/Y', strtotime($start_date)) .'-'.date('m/d/Y', strtotime($end_date)),
			"summary_data" => $model->getSummaryData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type))
		);

		$data['body']=$this->load->view("reports/graphical",$data,TRUE);
		$this->load->view("reports/page",$data);
	}
	
	//The actual graph data
	function graphical_summary_sales_graph($start_date, $end_date, $sale_type)
	{
		$this->load->model('reports/Summary_sales');
		$model = $this->Summary_sales;
		$report_data = $model->getData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		
		$graph_data = array();
		foreach($report_data as $row)
		{
			$graph_data[date('m/d/Y', strtotime($row['sale_date']))]= $row['total'];
		}

		$data = array(
			"title" => $this->lang->line('reports_sales_summary_report'),
			"yaxis_label"=>$this->lang->line('reports_revenue'),
			"xaxis_label"=>$this->lang->line('reports_date'),
			"data" => $graph_data
		);
		return $this->load->view("reports/graphs/line",$data,true);
		

	}
	
	//Graphical summary items report
	function graphical_summary_items($start_date, $end_date, $sale_type)
	{
		$this->load->model('reports/Summary_items');
		$model = $this->Summary_items;

		$data = array(
			"title" => $this->lang->line('reports_items_summary_report'),
			"graph" => $this->graphical_summary_items_graph($start_date, $end_date, $sale_type),
			"subtitle" => date('m/d/Y', strtotime($start_date)) .'-'.date('m/d/Y', strtotime($end_date)),
			"summary_data" => $model->getSummaryData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type))
		);

		$data['body']=$this->load->view("reports/graphical",$data,TRUE);
		$this->load->view("reports/page",$data);
	}
	
	//The actual graph data
	function graphical_summary_items_graph($start_date, $end_date, $sale_type)
	{
		$this->load->model('reports/Summary_items');
		$model = $this->Summary_items;
		$report_data = $model->getData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		
		$graph_data = array();
		foreach($report_data as $row)
		{
			$graph_data[$row['name']] = $row['total'];
		}

		$data = array(
			"title" => $this->lang->line('reports_items_summary_report'),
			"xaxis_label"=>$this->lang->line('reports_revenue'),
			"yaxis_label"=>$this->lang->line('reports_items'),
			"data" => $graph_data
		);

    RETURN	$this->load->view("reports/graphs/hbar",$data,TRUE);
		
	}
	
	//Graphical summary customers report
	function graphical_summary_categories($start_date, $end_date, $sale_type)
	{
		$this->load->model('reports/Summary_categories');
		$model = $this->Summary_categories;

		$data = array(
			"title" => $this->lang->line('reports_categories_summary_report'),
			"graph" => $this->graphical_summary_categories_graph($start_date, $end_date, $sale_type),
			"subtitle" => date('m/d/Y', strtotime($start_date)) .'-'.date('m/d/Y', strtotime($end_date)),
			"summary_data" => $model->getSummaryData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type))
		);

		$data['body']=$this->load->view("reports/graphical",$data,TRUE);
		$this->load->view("reports/page",$data);
	}
	
	//The actual graph data
	function graphical_summary_categories_graph($start_date, $end_date, $sale_type)
	{
		$this->load->model('reports/Summary_categories');
		$model = $this->Summary_categories;
		$report_data = $model->getData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		
		$graph_data = array();
		foreach($report_data as $row)
		{
			$graph_data[$row['category']] = $row['total'];
		}
		
		$data = array(
			"title" => $this->lang->line('reports_categories_summary_report'),
			"data" => $graph_data
		);

		RETURN $this->load->view("reports/graphs/pie",$data,TRUE);
		
	}
	
	function graphical_summary_suppliers($start_date, $end_date, $sale_type)
	{
		$this->load->model('reports/Summary_suppliers');
		$model = $this->Summary_suppliers;

		$data = array(
			"title" => $this->lang->line('reports_suppliers_summary_report'),
			"graph" => $this->graphical_summary_suppliers_graph($start_date, $end_date, $sale_type),
			"subtitle" => date('m/d/Y', strtotime($start_date)) .'-'.date('m/d/Y', strtotime($end_date)),
			"summary_data" => $model->getSummaryData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type))
		);

		$data['body']=$this->load->view("reports/graphical",$data,TRUE);
		$this->load->view("reports/page",$data);
	}
	
	//The actual graph data
	function graphical_summary_suppliers_graph($start_date, $end_date, $sale_type)
	{
		$this->load->model('reports/Summary_suppliers');
		$model = $this->Summary_suppliers;
		$report_data = $model->getData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		
		$graph_data = array();
		foreach($report_data as $row)
		{
			$graph_data[$row['supplier']] = $row['total'];
		}
		
		$data = array(
			"title" => $this->lang->line('reports_suppliers_summary_report'),
			"data" => $graph_data
		);

		return $this->load->view("reports/graphs/pie",$data,TRUE);
		
	}
	
	function graphical_summary_employees($start_date, $end_date, $sale_type)
	{
		$this->load->model('reports/Summary_employees');
		$model = $this->Summary_employees;

		$data = array(
			"title" => $this->lang->line('reports_employees_summary_report'),
			"graph" => $this->graphical_summary_employees_graph($start_date, $end_date, $sale_type),
			"subtitle" => date('m/d/Y', strtotime($start_date)) .'-'.date('m/d/Y', strtotime($end_date)),
			"summary_data" => $model->getSummaryData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type))
		);

		$data['body']=$this->load->view("reports/graphical",$data,TRUE);
		$this->load->view("reports/page",$data);
	}
	
	//The actual graph data
	function graphical_summary_employees_graph($start_date, $end_date, $sale_type)
	{
		$this->load->model('reports/Summary_employees');
		$model = $this->Summary_employees;
		$report_data = $model->getData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		
		$graph_data = array();
		foreach($report_data as $row)
		{
			$graph_data[$row['employee']] = $row['total'];
		}
		
		$data = array(
			"title" => $this->lang->line('reports_employees_summary_report'),
			"data" => $graph_data
		);

		return $this->load->view("reports/graphs/pie",$data,TRUE);
		
	}
	
	function graphical_summary_taxes($start_date, $end_date, $sale_type)
	{
		$this->load->model('reports/Summary_taxes');
		$model = $this->Summary_taxes;

		$data = array(
			"title" => $this->lang->line('reports_taxes_summary_report'),
			"graph" => $this->graphical_summary_taxes_graph($start_date, $end_date, $sale_type),
			"subtitle" => date('m/d/Y', strtotime($start_date)) .'-'.date('m/d/Y', strtotime($end_date)),
			"summary_data" => $model->getSummaryData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type))
		);

		$data['body']=$this->load->view("reports/graphical",$data,TRUE);
		$this->load->view("reports/page",$data);
	}
	
	//The actual graph data
	function graphical_summary_taxes_graph($start_date, $end_date, $sale_type)
	{
		$this->load->model('reports/Summary_taxes');
		$model = $this->Summary_taxes;
		$report_data = $model->getData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		
		$graph_data = array();
		foreach($report_data as $row)
		{
			$graph_data[$row['percent']] = $row['total'];
		}
		
		$data = array(
			"title" => $this->lang->line('reports_taxes_summary_report'),
			"data" => $graph_data
		);

		return $this->load->view("reports/graphs/pie",$data,TRUE);
		
	}
	
	//Graphical summary customers report
	function graphical_summary_customers($start_date, $end_date, $sale_type)
	{
		$this->load->model('reports/Summary_customers');
		$model = $this->Summary_customers;

		$data = array(
			"title" => $this->lang->line('reports_customers_summary_report'),
			"graph" => $this->graphical_summary_customers_graph($start_date, $end_date, $sale_type),
			"subtitle" => date('m/d/Y', strtotime($start_date)) .'-'.date('m/d/Y', strtotime($end_date)),
			"summary_data" => $model->getSummaryData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type))
		);

		$data['body']=$this->load->view("reports/graphical",$data,TRUE);
		$this->load->view("reports/page",$data);
	}
	
	//The actual graph data
	function graphical_summary_customers_graph($start_date, $end_date, $sale_type)
	{
		$this->load->model('reports/Summary_customers');
		$model = $this->Summary_customers;
		$report_data = $model->getData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		
		$graph_data = array();
		foreach($report_data as $row)
		{
			$graph_data[$row['customer']] = $row['total'];
		}
		
		$data = array(
			"title" => $this->lang->line('reports_customers_summary_report'),
			"xaxis_label"=>$this->lang->line('reports_revenue'),
			"yaxis_label"=>$this->lang->line('reports_customers'),
			"data" => $graph_data
		);

		RETURN $this->load->view("reports/graphs/hbar",$data,TRUE);
		
	}
	
	//Graphical summary discounts report
	function graphical_summary_discounts($start_date, $end_date, $sale_type)
	{
		$this->load->model('reports/Summary_discounts');
		$model = $this->Summary_discounts;

		$data = array(
			"title" => $this->lang->line('reports_discounts_summary_report'),
			"graph" => $this->graphical_summary_discounts_graph($start_date, $end_date, $sale_type),
			"subtitle" => date('m/d/Y', strtotime($start_date)) .'-'.date('m/d/Y', strtotime($end_date)),
			"summary_data" => $model->getSummaryData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type))
		);

		$data['body']=$this->load->view("reports/graphical",$data,TRUE);
		$this->load->view("reports/page",$data);
	}
	
	//The actual graph data
	function graphical_summary_discounts_graph($start_date, $end_date, $sale_type)
	{
		$this->load->model('reports/Summary_discounts');
		$model = $this->Summary_discounts;
		$report_data = $model->getData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		
		$graph_data = array();
		foreach($report_data as $row)
		{
			$graph_data[$row['discount_percent']] = $row['count'];
		}
		
		$data = array(
			"title" => $this->lang->line('reports_discounts_summary_report'),
			"yaxis_label"=>$this->lang->line('reports_count'),
			"xaxis_label"=>$this->lang->line('reports_discount_percent'),
			"data" => $graph_data
		);

		return $this->load->view("reports/graphs/hbar",$data,TRUE);
		
	}
	
	function graphical_summary_payments($start_date, $end_date, $sale_type)
	{
		$this->load->model('reports/Summary_payments');
		$model = $this->Summary_payments;

		$data = array(
			"title" => $this->lang->line('reports_payments_summary_report'),
			"graph" => $this->graphical_summary_payments_graph($start_date, $end_date, $sale_type),
			"subtitle" => date('m/d/Y', strtotime($start_date)) .'-'.date('m/d/Y', strtotime($end_date)),
			"summary_data" => $model->getSummaryData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type))
		);

		$data['body']=$this->load->view("reports/graphical",$data,TRUE);
		$this->load->view("reports/page",$data);
	}
	
	//The actual graph data
	function graphical_summary_payments_graph($start_date, $end_date, $sale_type)
	{
		$this->load->model('reports/Summary_payments');
		$model = $this->Summary_payments;
		$report_data = $model->getData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		
		$graph_data = array();
		foreach($report_data as $row)
		{
			$graph_data[$row['payment_type']] = $row['payment_amount'];
		}
		
		$data = array(
			"title" => $this->lang->line('reports_payments_summary_report'),
			"yaxis_label"=>$this->lang->line('reports_revenue'),
			"xaxis_label"=>$this->lang->line('reports_payment_type'),
			"data" => $graph_data
		);

		return $this->load->view("reports/graphs/pie",$data,TRUE);
		
	}
	function specific_customer_input()
	{
		$data = $this->_get_common_report_data();
		$data['specific_input_name'] = $this->lang->line('reports_customer');
		
		$customers = array();
		foreach($this->Customer->get_all()->result() as $customer)
		{
			$customers[$customer->person_id] = $customer->first_name .' '.$customer->last_name;
		}
		$data['specific_input_data'] = $customers;
		$data['body']=$this->load->view("reports/specific_input",$data,TRUE);
		$this->load->view("reports/page",$data);
	}

	function specific_customer($start_date, $end_date, $customer_id, $sale_type, $export_excel=0)
	{
		$this->load->model('reports/Specific_customer');
		$model = $this->Specific_customer;
		
		$headers = $model->getDataColumns();
		$report_data = $model->getData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'customer_id' =>$customer_id, 'sale_type' => $sale_type));
		
		$summary_data = array();
		$details_data = array();
		
		foreach($report_data['summary'] as $key=>$row)
		{
			$summary_data[] = array(anchor('sales/edit/'.$row['sale_id'], 'POS '.$row['sale_id'], array('target' => '_blank')), $row['sale_date'], $row['items_purchased'], $row['employee_name'], to_currency($row['subtotal']), to_currency($row['total']), to_currency($row['tax']),to_currency($row['profit']), $row['payment_type'], $row['comment']);
			
			foreach($report_data['details'][$key] as $drow)
			{
				$details_data[$key][] = array($drow['name'], $drow['category_id'], $drow['serialnumber'], $drow['description'], $drow['quantity_purchased'], to_currency($drow['subtotal']), to_currency($drow['total']), to_currency($drow['tax']),to_currency($drow['profit']), $drow['discount_percent'].'%');
			}
		}

		$customer_info = $this->Customer->get_info($customer_id);
		$data = array(
			"title" => $customer_info->first_name .' '. $customer_info->last_name.' '.$this->lang->line('reports_report'),
			"subtitle" => date('m/d/Y', strtotime($start_date)) .'-'.date('m/d/Y', strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"summary_data" => $summary_data,
			"details_data" => $details_data,
			"overall_summary_data" => $model->getSummaryData(array('start_date'=>$start_date, 'end_date'=>$end_date,'customer_id' =>$customer_id, 'sale_type' => $sale_type)),
			"export_excel" => $export_excel
		);

		$data['body']=$this->load->view("reports/tabular_details",$data,TRUE);
		$this->load->view("reports/page",$data);
	}
	
	function specific_employee_input()
	{
		$data = $this->_get_common_report_data();
		$data['specific_input_name'] = $this->lang->line('reports_employee');
		
		$employees = array();
		foreach($this->Employee->get_all()->result() as $employee)
		{
			$employees[$employee->person_id] = $employee->first_name .' '.$employee->last_name;
		}
		$data['specific_input_data'] = $employees;
		$data['body']=$this->load->view("reports/specific_input",$data,TRUE);
		$this->load->view("reports/page",$data);	
	}

	function specific_employee($start_date, $end_date, $employee_id, $sale_type, $export_excel=0)
	{
		$this->load->model('reports/Specific_employee');
		$model = $this->Specific_employee;
		
		$headers = $model->getDataColumns();
		$report_data = $model->getData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'employee_id' =>$employee_id, 'sale_type' => $sale_type));
		
		$summary_data = array();
		$details_data = array();
		
		foreach($report_data['summary'] as $key=>$row)
		{
			$summary_data[] = array(anchor('sales/edit/'.$row['sale_id'], 'POS '.$row['sale_id'], array('target' => '_blank')), $row['sale_date'], $row['items_purchased'], $row['customer_name'], to_currency($row['subtotal']), to_currency($row['total']), to_currency($row['tax']),to_currency($row['profit']), $row['payment_type'], $row['comment']);
			
			foreach($report_data['details'][$key] as $drow)
			{
				$details_data[$key][] = array($drow['name'], $drow['category_id'], $drow['serialnumber'], $drow['description'], $drow['quantity_purchased'], to_currency($drow['subtotal']), to_currency($drow['total']), to_currency($drow['tax']),to_currency($drow['profit']), $drow['discount_percent'].'%');
			}
		}

		$employee_info = $this->Employee->get_info($employee_id);
		$data = array(
			"title" => $employee_info->first_name .' '. $employee_info->last_name.' '.$this->lang->line('reports_report'),
			"subtitle" => date('m/d/Y', strtotime($start_date)) .'-'.date('m/d/Y', strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"summary_data" => $summary_data,
			"details_data" => $details_data,
			"overall_summary_data" => $model->getSummaryData(array('start_date'=>$start_date, 'end_date'=>$end_date,'employee_id' =>$employee_id, 'sale_type' => $sale_type)),
			"export_excel" => $export_excel
		);

		$data['body']=$this->load->view("reports/tabular_details",$data,TRUE);
		$this->load->view("reports/page",$data);	
	}
	
	function detailed_sales($start_date, $end_date, $sale_type, $export_excel=0)
	{
		$this->load->model('reports/Detailed_sales');
		$model = $this->Detailed_sales;
		
		$headers = $model->getDataColumns();
		$report_data = $model->getData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		
		$summary_data = array();
		$details_data = array();
		
		foreach($report_data['summary'] as $key=>$row)
		{
			//Get the sales type
			$register_type=($row['items_purchased']<0)?'NR ':'NS ';
			
			
			$summary_data[] = array(anchor('sales/edit/'.$row['sale_id'].'/'.$register_type, $register_type.$row['sale_id'], array('target' => '_blank')), $row['sale_date'], $row['items_purchased'], $row['employee_name'], $row['customer_name'], to_currency($row['subtotal']), to_currency($row['total']), to_currency($row['tax']),to_currency($row['profit']), $row['payment_type'], $row['comment']);
			
			foreach($report_data['details'][$key] as $drow)
			{
				$details_data[$key][] = array($drow['name'], $drow['category_id'], $drow['serialnumber'], $drow['description'], $drow['quantity_purchased'], to_currency($drow['subtotal']), to_currency($drow['total']), to_currency($drow['tax']),to_currency($drow['profit']), $drow['discount_percent'].'%');
			}
		}

		$data = array(
			"title" =>$this->lang->line('reports_detailed_sales_report'),
			"subtitle" => date('m/d/Y', strtotime($start_date)) .'-'.date('m/d/Y', strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"summary_data" => $summary_data,
			"details_data" => $details_data,
			"overall_summary_data" => $model->getSummaryData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type)),
			"export_excel" => $export_excel
		);

		$data['body']=$this->load->view("reports/tabular_details",$data,TRUE);
		$this->load->view("reports/page",$data);	
	}
	
	function detailed_receivings($start_date, $end_date, $sale_type, $export_excel)
	{
		$this->load->model('reports/Detailed_receivings');
		$model = $this->Detailed_receivings;
		
		$headers = $model->getDataColumns();
		$report_data = $model->getData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		
		$summary_data = array();
		$details_data = array();
		
		foreach($report_data['summary'] as $key=>$row)
		{
			
			
			
			$summary_data[] = array(anchor('receivings/receipt/'.$row['receiving_id'], 'RECV '.$row['receiving_id'], 
					                array('target' => '_blank')),
					                 $row['receiving_date'],
					                 $row['items_purchased'], 
					                 $row['employee_name'], 
					                 $row['supplier_name'], 
					                 to_currency($row['total']),
					                 $row['payment_type'], 
					                 $row['comment']);
			
			foreach($report_data['details'][$key] as $drow)
			{
				$details_data[$key][] = array($drow['name'], $drow['category'], 
						$drow['quantity_purchased'], to_currency($drow['total']), $drow['discount_percent'].'%'
						);
			}
		}

		$data = array(
			"title" =>$this->lang->line('reports_detailed_receivings_report'),
			"subtitle" => date('m/d/Y', strtotime($start_date)) .'-'.date('m/d/Y', strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"summary_data" => $summary_data,
			"details_data" => $details_data,
			"overall_summary_data" => $model->getSummaryData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type)),
			"export_excel" => $export_excel
		);

		$data['body']=$this->load->view("reports/tabular_details",$data,TRUE);
		$this->load->view("reports/page",$data);	
	}
			
	function excel_export()
	{
		$data['body']=$this->load->view("reports/excel_export",array(),TRUE);
		$this->load->view("reports/page",$data);			
	}
	
	function inventory_low($export_excel_or_email=0)
	{
		$this->load->model('reports/Inventory_low');
		$model = $this->Inventory_low;
		$tabular_data = array();
		$report_data = $model->getData(array());
		foreach($report_data as $row)
		{
			$tabular_data[] = array($row['name'], $row['item_number'],
					                $row['quantity'], $row['reorder_level'],$row['cost_price'],
					                $row['cost_price']*$row['quantity'],$row['unit_price'],
					                $row['quantity']*$row['unit_price']+($row['quantity']*$row['unit_price']*$row['item_tax'])/100,$row['item_tax']);
		}

		$data = array(
			"title" => $this->lang->line('reports_low_inventory_report'),
			"subtitle" => '',
			"headers" => $model->getDataColumns(),
			"data" => $tabular_data,
			"summary_data" => $model->getSummaryData(array()),
			"export_excel" => $export_excel_or_email
		);

		$data['body']=$this->load->view("reports/tabular",$data,TRUE);
		if($export_excel_or_email==3)
			
		{
		 //User wants to send email
		 $this->email($id=42,$data['body'],$email_body='Just email body');
		}
		$this->load->view("reports/page",$data);	
	}
	
	/**
	 * @author Kamaro Lambert
	 * @name inventory_expiring
	 * @param int  $export_excel
	 */
	function inventory_expiring($export_excel=0)
	{
		$this->load->model('reports/Inventory_expiring');
		$model = $this->Inventory_expiring;
		$tabular_data = array();
		$report_data = $model->getData(array());
		
		foreach($report_data as $row)
		{
			$tabular_data[] = array($row['name'], $row['item_number'],
					$row['quantity'], $row['reorder_level'],$row['cost_price'],
					$row['cost_price']*$row['quantity'],$row['unit_price'],
					$row['quantity']*$row['unit_price']+($row['quantity']*$row['unit_price']*$row['item_tax'])/100,
					$row['remain_days'],$row['expiration_date']);
		}
	
		$data = array(
				"title" => $this->lang->line('reports_expiring_inventory_report'),
				"subtitle" => '',
				"headers" => $model->getDataColumns(),
				"data" => $tabular_data,
				"summary_data" => $model->getSummaryData(array()),
				"export_excel" => $export_excel
		);
	
		$data['body']=$this->load->view("reports/tabular",$data,TRUE);
		$this->load->view("reports/page",$data);
	}
	
	function inventory_summary($export_excel=0)
	{
		$this->load->model('reports/Inventory_summary');
		$model = $this->Inventory_summary;
		$tabular_data = array();
		$report_data = $model->getData(array());
		
		foreach($report_data as $row)
		{
						$tabular_data[] = array($row['name'], $row['item_number'],
					                $row['quantity'], $row['reorder_level'],$row['cost_price'],
					                $row['cost_price']*$row['quantity'],$row['unit_price'],
					                $row['quantity']*$row['unit_price']+($row['quantity']*$row['unit_price']*$row['item_tax'])/100,$row['item_tax']);
			}

		$data = array(
			"title" => $this->lang->line('reports_inventory_summary_report'),
			"subtitle" => '',
			"headers" => $model->getDataColumns(),
			"data" => $tabular_data,
			"summary_data" => $model->getSummaryData(array()),
			"export_excel" => $export_excel
		);

		$data['body']=$this->load->view("reports/tabular",$data,TRUE);
		$this->load->view("reports/page",$data);	
	}
	function export_customer()
	{
		
		$this->load->model('customer');
		$config['base_url'] = site_url('/customers/index');
		$config['total_rows'] = $this->Customer->count_all();
		$config['per_page'] = '20';
		$config['uri_segment'] = 3;
		$this->pagination->initialize($config);
		$data['controller_name']=strtolower(get_class());
		$data['form_width']=$this->get_form_width();
		$data['manage_table']=get_people_manage_table( $this->Customer->get_all( $config['per_page'], $this->uri->segment( $config['uri_segment'] ) ), $this );
		$data['body']=$this->load->view('people/manage',$data,TRUE);
		$this->load->view("reports/page",$data);	
	}
	
	
	function income_vs_expenses($email=null)
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
		$data['title'] = 'Income Vs Expenses';
		$data['data']=$graph_data;
	
	   return $html=$this->load->view("reports/graphs/pie",$data,true);
	
	    
	}

	/**
	 * @author Kamaro Lambert
	 * @method to send email to the client
	 * @param unknown_type $id
	 * @param unknown_type $html
	 * @param unknown_type $email_body
	 */
	
	function email($id=null,$html=null,$email_body='Just email body')
	{
		$this->load->helper(array('logo', 'file','to_pdf'));
		// create and save invoice to temp
		
		$invoice_localized = 'test';
		pdf_create($html, $invoice_localized, FALSE);
		
	  $config = Array(
		
				'protocol' => 'smtp',
		
				'smtp_host' => 'ssl://smtp.googlemail.com',
		
				'smtp_port' => 465,
		
				'smtp_user' => 'lambert@hellorwanda.com',
		
				'smtp_pass' => 'Test1234',
		
				'mailtype' => 'html',
		
				'charset' => 'iso-8859-1',
		
				'wordwrap' => TRUE
		
		);
		
		$this->load->library('email', $config);
		
		$this->email->set_newline("\r\n");
		
		
		$this->email->from($this->config->item('email'));
		
		$this->email->to('gerageza@gmail.com');
		
		$this->email->subject('This is an email test');
		$this->email->attach("./pdf_temp/$invoice_localized.pdf");
		$this->email->message($email_body);
		
		
		if($this->email->send())
		
		{
		
			
			$this->_delete_stored_files(); // remove saved invoice(s)
			return true;
		
		
		}
		
		
		else
		
		{
		
			return $this->email->print_debugger();
		
		}
		
	}
	
	/**
	 * @author Kamaro Lambert
	 * @method to delete all files in temporary directory
	 */
	
	function _delete_stored_files()
	{
		delete_files("./pdf_temp/");
	}
	
	/**
	 * @author Kamaro Lambert
	 * @param array $sales_data
	 * @return boolean
	 */
	
	function sdc_receipt($receiving_data)
	{
	
	
		//Loading the file helper
		//-----------------------
		$this->load->helper('file');
	
		//Preparing the data
		//-------------------
	
		$data['r_tin']=$receiving_data['tin'];
	
	
		$data['total']=$receiving_data['received_amount'];
		$data['taxe']=($receiving_data['tax_amount']!=NULL)? $receiving_data['tax_amount']:'';
	
		//Loading the view with the data
		$receipt_fiscal=$this->load->view('sdc/sdc_receiving_receipt',$data,true);
		//Removing any space in the filename
		$receipt_file_name=str_replace(' ', '_','Bill_RECV'.$receiving_data['receiving_id']);
			
		//Now let try to get the drive letter
		$syste_driver_latter=$this->_driveLetter(__FILE__);
			
		$mypath="$syste_driver_latter:/dpool/in/";
			
	
		if (!is_dir($mypath))
		{
			mkdir($mypath, 0777, TRUE);
	
		}
	
		//Attempt file write
		//-------------------
		if ( ! write_file("$mypath$receipt_file_name.txt", $receipt_fiscal,"wd"))
		{
			//Something went wrong while writting the file
				
			return false;
		}
		else
		{
			//All Goes well
			return true;
		}
	
	}
	

	
	/**
	 * @author Kamaro Lambert
	 * @method receiving per supplier
	 * 
	 */
	
	function summary_receiving_per_suppliers($start_date, $end_date)
	{
		//Loading the model to use
		$this->load->model('receiving');
		
		$data = array(
				"title" => $this->lang->line('reports_receiving_per_suppliers_summary_report'),
				"subtitle" => date('m/d/Y', strtotime($start_date)) .'-'.date('m/d/Y', strtotime($end_date)),
				);
		
		//Getting the data to use
		$data['purchase_per_suppliers']=$this->receiving->receiving_per_supplier($start_date, $end_date)->result_array();
		
		
		
		//Check if the user want to send data to Electronical Fiscal Device
		$user_session=$this->session->userdata($start_date.$end_date);
	
		if($this->uri->segment(7)=='print' AND $user_session!='printed')
		{
			
			//Yes user wants to send receiving information to the fiscal device
			foreach($data['purchase_per_suppliers'] as $data)
			{
				$this->sdc_receipt($data);
			}
			
			$this->session->set_userdata($start_date.$end_date,'printed');
			
			$this->session->set_flashdata('type','success');
    
    		$this->session->set_flashdata('message',$this->lang->line('common_send_to_efd_succesffuly'));
    			
    	}
    	else
    	{
    		$this->session->set_flashdata('type','danger');
    		$this->session->set_flashdata('message',$this->lang->line('common_sent_already_to_efd_succesffuly'));
    		
    	}
		
		//Loading the view with the data
		$data['body']=$this->load->view("reports/receivings_per_supplier",$data,TRUE);
		
		$this->load->view("reports/page",$data);
	    
	}
  

    public function smsview(){


     	$this->load->view('reports/smsinput');
    }
	/**
	* @author Kamaro Lambert
	* 
	* Method to send sms reports
	*/

	public function smsreport(){

		if(!is_numeric($this->input->post('expenses'))){
		$data['errors']='Expenses has to be a number !';
		$this->load->view("home",$data);

		return false;
		}
		
		$destination = $this->config->item('phone_sms');     //MSISDN that should receive the SMS-FROM Configuration Module
        //Check if we have a configured numebr
        if(!is_numeric($destination)){
        $data['errors']='Please configure the number that should receive SMS under configuration menu.';
		$this->load->view("home",$data);

		return false;
        }
     
		//initializing parameters to connect to the API
        $this->load->model('reports/Summary_sales');
		$model = $this->Summary_sales;
        $todayinfos=$model->getSummaryData(array('start_date'=>DATE('Y-m-d'), 'end_date'=>DATE('Y-m-d'), 'sale_type' => 'sale'));
        $yesterdayinfos=$model->getSummaryData(array('start_date'=>date('Y-m-d',strtotime('-1 day')), 'end_date'=>date('Y-m-d',strtotime('-1 day')), 'sale_type' => 'sale'));
        
        $variance=(($todayinfos['profit']/$yesterdayinfos['profit'])-1)*100;
         if ($variance>0) {
         	# code...
         	$variance='Sales increased '.$variance.'%';
         }else{
         	$variance='Sales descreased '.$variance.'%';
         }
        //Defining the Text if you insert to spaces it will produce special characters
     
        $message = "Hi, subtotal:".to_currency($todayinfos['subtotal'])." ,total:".to_currency($todayinfos['total']).",tax ".to_currency($todayinfos['tax']).",profit:".to_currency($todayinfos['profit']).",Total Expenses:".to_currency($this->input->post('expenses')).", Net Profit (Profit-Expenses):".to_currency($todayinfos['profit']-$this->input->post('expenses')).",comment:  $variance.
        .";
        $server = "121.241.242.114"; //Server IP
        $port="";                   //Port per default it's normal http 80
        $username = "con-ujumbe";    //API username provided by the API owner
        $password = "sms123";        //API Password provided by the API owner
        

        //Convert message into URL format.
        $message = urlencode($message);
        
        //Formatting URL to send the SMS
        $url = "http://{$server}/bulksms/bulksms?username={$username}&password={$password}&type=0&dlr=1&destination={$destination}&source=kamaro&message={$message}";
        
        //Instatiate CURL library from core php
        $ch = curl_init();
        
        //now setting CURL options and tell it to get the response from the HTTP method
        curl_setopt_array(
            $ch, array( 
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
        ));
         
        //Attempting to send request to API 
        $output = curl_exec($ch);
        
        //Convert the response into array using | as separator
        $respond = explode("|",$output);
        
        //Check if the message was sent 
        if($respond[0]=='1701'){
        	//all goes well
        $data['successes']='Messages sent successefully to '.$destination;
		$this->load->view("home",$data);

        }
        else
        {
       	$data['errors']='Messages could not be sent error:'.$output;
		$this->load->view("home",$data);
        }

}
	
	/**
	 * @author Kamaro Lambert
	 *  Returns null if unable to determine drive letter (such as on a *nix box)
	 */
	
	function _driveLetter($path)
	{
		return (preg_match('/^[A-Z]:/i', $path = realpath($path))) ? $path[0] : null;
	}
	
 }
?>