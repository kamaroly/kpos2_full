<?php if(isset($message)):?>

<?php foreach ($message as $alert=>$values):?>
<div class="alert-<?php echo ($alert=='error')?'danger':'success';?> alert">
  <strong><?php echo $values;?></strong></div>
 <?php endforeach;?>
<?php endif;?>
<a style="float:right;width:240px;" href="<?php echo site_url("config/backup/yes");?>">
    <div class="btn btn-small  btn-success">
		<img src="<?php echo base_url().'images/menubar/backup.png';?>" width="48px" height="48px" border="0" alt="Menubar Image">		
		<span>-<?php echo $this->lang->line('module_config/backup_desc');?></span>
 </div>
</a>