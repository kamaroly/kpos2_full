
<?php echo form_open('config/currency_default')?>
<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('config_enable_multi_currency').':', 'enable_multi_currency',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_checkbox(array(
		'name'=>'enable_multi_currency',
		'id'=>'enable_multi_currency',
		'value'=>'enable_multi_currency',
		'checked'=>$this->config->item('enable_multi_currency')));?>
	</div>
</div>

 <input type="submit" name="update_currency" value="Update Currencies" class="btn btn-small btn-primary">
<?php if (validation_errors()) : ?>
<div class="error"><?php echo validation_errors(); ?></div>
<?php endif; ?>

<?php echo anchor('config/currency_new/width:300','Add Currency','class="thickbox btn btn-small btn-success" title ="New Currency "style="color:#ffffff;"');?>
<br/>
<?php echo $this->pagination->create_links();?>
<style>
<!--

.success {
color: #ffffff;
text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
background-color: #5bb75b;
background-image: -moz-linear-gradient(top, #62c462, #51a351);
background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#62c462), to(#51a351));
background-image: -webkit-linear-gradient(top, #62c462, #51a351);
background-image: -o-linear-gradient(top, #62c462, #51a351);
background-image: linear-gradient(to bottom, #62c462, #51a351);
background-repeat: repeat-x;
border-color: #51a351 #51a351 #387038;
border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff62c462', endColorstr='#ff51a351', GradientType=0);
filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
}
.primary {
color: #ffffff;
text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
background-color: #006dcc;
background-image: -moz-linear-gradient(top, #0088cc, #0044cc);
background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#0088cc), to(#0044cc));
background-image: -webkit-linear-gradient(top, #0088cc, #0044cc);
background-image: -o-linear-gradient(top, #0088cc, #0044cc);
background-image: linear-gradient(to bottom, #0088cc, #0044cc);
background-repeat: repeat-x;
border-color: #0044cc #0044cc #002a80;
border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff0088cc', endColorstr='#ff0044cc', GradientType=0);
filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
}



-->
</style>
<table border="1" class="table table-bordered table-striped">
<thead>
<tr>
  <th  class="primary">Name</th>
  <th  class="primary">Exchange Rate</th>
  <th  class="primary">Symbol</th>
  <th  class="primary">Suffix</th>
  <th  class="primary">Thousand</th>
  <th  class="primary">Decimal</th>
  <th  class="primary">Status</th>
  <th  class="primary">Default</th>
  <th  class="primary" colspan="2">Action</th>
 </tr>
</thead>
<tbody>

<?php foreach ($currencies as $currency):?>
<tr >
     <td class="<?php echo ($currency->Default==1)?'success':'';?>"><?php echo $currency->Name;?></td>
     <td class="<?php echo ($currency->Default==1)?'success':'';?>"><?php echo $currency->Exchange_Rate;?></td>
     <td class="<?php echo ($currency->Default==1)?'success':'';?>"><?php echo $currency->Symbol;?></td>
     <td class="<?php echo ($currency->Default==1)?'success':'';?>"><?php echo ($currency->Symbol_Suffix==1)?'Enabled ':'Disabled';?></td>
     <td class="<?php echo ($currency->Default==1)?'success':'';?>"><?php echo $currency->Thousand_Separator;?></td>
     <td class="<?php echo ($currency->Default==1)?'success':'';?>"><?php echo $currency->Decimal_Separator;?></td>
     <td class="<?php echo ($currency->Default==1)?'success':'';?>"><?php echo ($currency->Status==1)?'Enabled ':'Disabled';?></td>
     <td class="<?php echo ($currency->Default==1)?'success':'';?>"><input type="radio" name="Default" value="<?php echo $currency->curr_id;?>" <?php echo ($currency->Default==1)?'Checked':'';?> /><?php echo ($currency->Default==1)?'Default':'';?></td>
     <td class="<?php echo ($currency->Default==1)?'success':'';?>"><?php echo anchor('config/currency_edit/'.$currency->curr_id.'/width:200','Edit ','class="thickbox" title="Update Item"');?></td>
     <td class="<?php echo ($currency->Default==1)?'success':'';?>"><?php echo anchor('config/currency_delete/'.$currency->curr_id,'Delete ');?></td>
     
 </tr>
 <?php endforeach;?>
 <tr><td colspan="10">
 <input type="submit" name="update_currency" value="Update Currencies" class="btn btn-small btn-primary">
</td>
</tr>
<?php echo form_close();?>
</tbody>
</table>