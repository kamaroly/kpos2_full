<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<base href="<?php echo base_url();?>" />
	<title><?php echo $this->config->item('company').' '.$this->lang->line('common_powered_by').' - Kpos 2' ?></title>
	  
	<link rel="stylesheet" rev="stylesheet" href="<?php echo base_url();?>css/kpharmacy.css" />
	<link rel="stylesheet" rev="stylesheet" href="<?php echo base_url();?>css/ospos_print.css"  media="print"/>
	<script>BASE_URL = '<?php echo site_url(); ?>';</script>

    <script src="<?php echo base_url();?>js/jquery-1.2.6.min.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<script src="<?php echo base_url();?>js/jquery.color.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<script src="<?php echo base_url();?>js/jquery.metadata.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<script src="<?php echo base_url();?>js/jquery.form.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<script src="<?php echo base_url();?>js/jquery.tablesorter.min.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<script src="<?php echo base_url();?>js/jquery.ajax_queue.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<script src="<?php echo base_url();?>js/jquery.bgiframe.min.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<script src="<?php echo base_url();?>js/jquery.autocomplete.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<script src="<?php echo base_url();?>js/jquery.validate.min.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<script src="<?php echo base_url();?>js/jquery.jkey-1.1.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<script src="<?php echo base_url();?>js/thickbox.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<script src="<?php echo base_url();?>js/common.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<script src="<?php echo base_url();?>js/manage_tables.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<script src="<?php echo base_url();?>js/swfobject.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<script src="<?php echo base_url();?>js/date.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<script src="<?php echo base_url();?>js/datepicker.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
	
	 
	   
<style type="text/css">
html {
    overflow: auto;
}
</style>

</head>
<body>
<div id="menubar">
	<div id="menubar_container">
		<div id="menubar_company_info" >
		<span id="company_title">
		<?php
$image_properties = array(
		'src' => 'images/company_logo/logo.png',
		'alt' => $this->config->item('company').'-KPharmacy',
		'class' => 'img-polaroid',
		'width' => '110',
		'height' => '30',
		'title' => 'That was quite a night',
		'rel' => 'lightbox',
);
echo img($image_properties);?>
		</span><br />
		<span style='font-size:8pt;'></span>
	</div>

		<div id="menubar_navigation">
			<?php
			foreach($allowed_modules->result() as $module)
			{
				if ($module->module_id!='config/backup')
				{
			?>
			<div class="menu_item">
				<a href="<?php echo site_url("$module->module_id");?>">
				<img src="<?php echo base_url().'images/menubar/'.$module->module_id.'.png';?>" border="0" alt="Menubar Image" width="40px"/></a><br />
				<a href="<?php echo site_url("$module->module_id");?>"><?php echo $this->lang->line("module_".$module->module_id) ?></a>
			</div>
			<?php
			    }
			}   
			?>
			 <div ><a href="<?php echo site_url('reports/inventory_low');?>">
			 <span class="label" style="border-bottom: 1px solid #ffffff;">Stock Out &nbsp; &nbsp; &nbsp; <sup  class="badge badge-warning"><?php echo $this->Item->count_all_lowstock();?></sup></span> </a>
			
        <a href="<?php echo site_url('reports/inventory_expiring');?>"> <span class="label">Expiring soon <sup  class="badge badge-info">
        <?php echo $this->Item->count_all_expiring();?></sup></span> </a>
		</div>
		<a href="<?php echo site_url('reports/smsview/-1/width:360');?>" class="thickbox none" title="Sending SMS report"><div class="btn btn-small btn-inverse" style="float: left;">SMS Report</div></a>
			   </div>

		<div id="menubar_footer">
		<?php echo $this->lang->line('common_welcome')." $user_info->first_name $user_info->last_name! | "; ?>
		<?php echo anchor("home/logout",$this->lang->line("common_logout")); ?>
		</div>
       
  
		<div id="menubar_date">
		<?php echo date('F d, Y h:i a') ?>
		</div>

	</div>
</div>
<div id="content_area_wrapper">
<?php if($this->uri->segment(1)!='home'):?>
<style>
<!--
#content_area {
position: relative;
margin: 0 auto;
width: 1000px;
text-align: left;
}
-->
</style>
<?php endif;?>
<script type="text/javascript">
<!--
$(document).ready(function() {

	// Dropdown Navigation on hover
	$("alert").click(function ()
			{
		this.hide();
	});
	});
//-->
</script>
<div id="content_area">
<?php if(file_exists ('install/index.php')):?>
<div class="alert-danger alert">
  <strong>Please delete the entire "Install" folder</strong> then refresh this page. 
        Leaving the Install folder makes your Kpos very unsafe and open for attacks
        
</div>
<?php elseif(isset($errors)):?>
	<div class="alert-danger alert">
  <h2><?php echo $errors;?></h2> 
        
</div>
<?php elseif(isset($successes)):?>
	<div class="alert-success alert">
  <h2><?php echo $successes;?></h2> 
        
</div>
<?php endif;?>




   