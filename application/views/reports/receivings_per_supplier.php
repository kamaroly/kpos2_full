

<?php echo anchor(current_url().'/print',$this->lang->line('reports_send_to_efd'),'"style="color:#ffffff;" class="btn btn-small btn-inverse"  ');?>
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
<th  class="primary"><?php echo $this->lang->line('date_applied');?></th>
<th  class="primary"><?php echo $this->lang->line('recvs_id');?></th>
  <th  class="primary"><?php echo $this->lang->line('common_supplier_tin');?></th>
  <th  class="primary"><?php echo $this->lang->line('total_paid');?></th>
  <th  class="primary"><?php echo $this->lang->line('taxes');?></th>
 </tr>
</thead>
<tbody>
<?php if(isset($purchase_per_suppliers)>0 AND count($purchase_per_suppliers)):?>
<?php foreach ($purchase_per_suppliers as $purchase_per_supplier):?>
<tr >
     <td ><?php echo $purchase_per_supplier['receive_date'];?></td>
     <td ><?php echo 'RECV '.$purchase_per_supplier['receiving_id'];?></td>
     <td ><?php echo $purchase_per_supplier['tin'];?></td>
     <td ><?php echo $purchase_per_supplier['received_amount'];?></td>
     <td ><?php echo $purchase_per_supplier['tax_amount'];?></td>
       
 </tr>
 <?php endforeach;?>
 <?php else:?>
 <tr >
    <td colspan="5">
     <?php echo $this->lang->line('common_no_data_available');?>
     </td>
 </tr>
 <?php endif;?>
</tbody>
</table>