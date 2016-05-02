<?php $this->load->view('partial/header');?>
<link rel='stylesheet' href='<?php echo base_url();?>/css/default.css' type='text/css'>

<table>
<tr>
	<td class="leftPanel"><?php   $this->load->view('reports/listing');?></td>
	<td class="mainPanelScroll">
		<table class="default">
			<tr>
				<td class="headerLeft">
				
					<?php echo isset($title)?$title:$this->lang->line('reports_dashboard_report'); ?>
                   </td>
				<td class="headerRight">
					 <div id="welcome_message"><?php echo $this->lang->line('reports_welcome_message'); ?></div>
				</td>
			</tr>
		</table>
		<br>
	
	<!-- This is the notification area for all operations done by the application -->
    <?php if($this->session->flashdata('type')):?>
    <div class="alert-<?php echo $this->session->flashdata('type');?>">
    <a class="close"><?php echo htmlentities('×')?></a>
    <p><strong> <?php echo $this->session->flashdata('message');?>!</strong></p>
     <!-- Let's check if there is any form that has generated error then display errors -->   
    <?php echo validation_errors(); ?>
   </div>
   <?php endif;?>
   
		<table class="default">
			<tr>
				<td>
				    
					<?php   echo isset($body)?$body:'';?>
				</td>
			</tr>
		</table>
	</td>
</tr>
</table>
<hr>
<?php $this->load->view('partial/footer');?>