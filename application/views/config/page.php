<?php $this->load->view('partial/header');?>
<link rel='stylesheet' href='<?php echo base_url();?>/css/default.css' type='text/css'>
      


<table>
<tr>
	<td class="leftPanel"><?php   $this->load->view('config/left_menu');?></td>
	<td class="mainPanelScroll">
		<table class="default">
			<tr>
				<td class="headerLeft">
					<?php echo $page_title;?>
				</td>
				<td class="headerRight">
					<?php echo date('D-M-Y');?>
				</td>
			</tr>
		</table>
		<br>
		<table class="table  table-striped table-bordered table-hover">
		 <tr>
		    <td>
   <!-- This is the notification area for all operations done by the application -->
    <?php if($this->session->flashdata('type')):?>
    <div class="alert-<?php echo $this->session->flashdata('type');?>">
    <a class="close"><?php echo htmlentities('×')?></a>
    <p><strong><?php echo $this->session->flashdata('type');?>!</strong> <?php echo $this->session->flashdata('message');?>.</p>
     <!-- Let's check if there is any form that has generated error then display errors -->   
    <?php echo validation_errors(); ?>
   </div>
   <?php endif;?>
    <!-- end of the notification area -->
		    </td>
		 </tr>
			<tr>
				<td>
					<?php   echo $body;?>
				</td>
			</tr>
		</table>
	</td>
</tr>
</table>
<hr>
<?php $this->load->view('partial/footer');?>