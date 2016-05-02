<?php $this->load->view('partial/header');?>

<link rel='stylesheet' href='<?php echo base_url();?>/css/default.css' type='text/css'>
 
<table>
<tr>
	<td class="leftPanel"><?php   $this->load->view('invoices/left_menu');?></td>
	<td class="mainPanelScroll">
		<table class="default">
			<tr>
			   <?php if(!isset($right_header)):?>
				<td class="headerLeft">
					<?php echo $page_title;?>
				</td>
				<?php endif;?>
				
				<td class="headerRight" <?php echo isset($right_header)?'colspan="2"':''?>>
					<?php echo isset($right_header)?$right_header:date('D-M-Y');?>
				    
				    	
				</td>
			</tr>
		</table>
		<br>
		<table class="table  table-striped table-bordered table-hover">
			<tr>
				<td>
				<?php if($this->session->flashdata('error')):?>
                   <div class='alert-danger alert'> <strong><?php echo $this->session->flashdata('error');?></strong></div>
                <?php elseif($this->session->flashdata('success')):?>
                  <div class='alert-success alert'> <strong><?php echo $this->session->flashdata('success');?></strong></div>
                <?php endif;?>
					<?php   echo $body;?>
				</td>
			</tr>
		</table>
	</td>
</tr>
</table>
<hr>
<?php $this->load->view('partial/footer');?>