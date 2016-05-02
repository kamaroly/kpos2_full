<?php $this->load->view("partial/header"); ?>
<br />
<h3 id="title_bar"><?php echo $this->lang->line('common_welcome_message'); ?></h3>
<div id="home_module_list">
	<?php
	foreach($allowed_modules->result() as $module)
	{
		if ($module->module_id!='home')
		{
			
	?>
	<div class="module_item">
		<a href="<?php echo site_url("$module->module_id");?>">
		<img src="<?php 
		if($module->module_id=='config/backup')
		{
		echo base_url().'images/menubar/backup.png';
		}
		else
		{
		echo base_url().'images/menubar/'.$module->module_id.'.png';
		}
		?>" width="48px" height="48px" border="0" alt="Menubar Image" />		
		<span><?php echo $this->lang->line("module_".$module->module_id) ?></span></a>
		<span>-<?php echo $this->lang->line('module_'.$module->module_id.'_desc');?></span>
	</div>
	<?php
		}
	}
	?>
	
<?php $this->load->view("partial/footer"); ?>