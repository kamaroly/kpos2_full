
<?php if(isset($message)):?>

<?php foreach ($message as $alert=>$values):?>
<div class="alert-<?php echo ($alert=='error')?'danger':'success';?> alert">
  <strong><?php echo $values;?></strong></div>
 <?php endforeach;?>
<?php endif;?>

<?php
$image_properties = array(
		'src' => 'images/company_logo/'.$this->config->item('company_logo'),
		'alt' => 'KPharmacy',
		'class' => 'img-polaroid',
		'width' => '200',
		'height' => '100',
		'title' => 'That was quite a night',
		'rel' => 'lightbox',
);
echo img($image_properties);?>
<?php echo form_open_multipart('config/do_upload');?>

<input type="file" name="userfile" size="10"  class="btn btn-small btn-info" />
<br/>
<input type="submit" value="Change" size="10" style="width:260px;"  class="btn btn-small btn-success" />

<?php echo form_close();?>

