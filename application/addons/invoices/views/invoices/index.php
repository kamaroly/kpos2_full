<?php
Header('Cache-Control: no-cache');
Header('Pragma: no-cache');
?>


	<?php echo form_open('invoices/retrieveInvoices/', array('id' => 'filter', 'class' => 'class="form-inline"'));?>
    
    <!-- Checking if the page isnot for the quotations  -->
     
     <?php if($this->uri->segment(2)!='quotations'):?>
     <?php $status_array=array('all'     =>$this->lang->line('invoice_all_invoices'),
     		                   'overdue' =>$this->lang->line('invoice_overdue'),
     		                   'open'    =>$this->lang->line('invoice_open'),
     		                   'closed'  =>$this->lang->line('invoice_closed'),
     		                  );?>
    <?php echo form_dropdown('status',$status_array,$this->input->post('status')?$this->input->post('status'):'','id="auto_submit" onchange="getInvoices();"');?>                 
	<?php endif;?>	   	
	
		   	<select name="client_id" id="client_id" onchange="getInvoices();">
				<option value="null" selected="selected"><?php echo $this->lang->line('invoice_all_clients');?></option>
				<?php foreach($clientList->result() as $row): ?>
				<option value="<?php echo $row->person_id;?>" <?php echo ($row->person_id==$this->input->post('client_id'))?'Selected':'';?>><?php echo $row->last_name.' '.$row->first_name;?></option>
				<?php endforeach; ?>
			</select>
			
	<?php echo  form_submit('submit','Search','class="btn btn-small btn-primary"')?>
	<?php echo form_close();?>

<?php $this->load->view('invoices/invoices/invoice_table');?>