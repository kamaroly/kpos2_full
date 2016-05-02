<?php $status_array=array(0=>'Disabled',
		            1=>'Enabled');?>

<form action="<?php echo site_url('config/currency_edit/'.$currency->curr_id);?>" accept-charset="utf-8" method="POST">
  <p>
    
    <input type="hidden" name="curr_id" value="<?php echo $currency->curr_id;?>">
  </p>
  <p>
    <label>
      Name
    </label>
    <input type="text" name="Name" value="<?php echo $currency->Name;?>">
  </p>
  <p>
    <label>
      Exchange Rate
    </label>
    <input type="text" name="Exchange_Rate" value="<?php echo $currency->Exchange_Rate;?>">
  </p>
  <p>
    <label>
      Symbol
    </label>
    <input type="text" name="Symbol" value="<?php echo $currency->Symbol;?>">
  </p>
  <p>
    <label>
      Symbol Suffix
    </label>
    <?php echo form_dropdown('Symbol_Suffix',$status_array, $currency->Symbol_Suffix)?>
    
  </p>
  <p>
    <label>
      Thousand Separator
    </label>
    <input type="text" name="Thousand_Separator" value="<?php echo $currency->Thousand_Separator;?>">
  </p>
  <p>
    <label>
      Decimal Separator
    </label>
    <input type="text" name="Decimal_Separator" value="<?php echo $currency->Decimal_Separator;?>">
  </p>
  <p>
    <label>
      Status
    </label>
    <?php echo form_dropdown('Status',$status_array, $currency->Status)?>
    
  </p>
  <p>
    <label>
      Default
    </label>
    <?php echo form_hidden('Default',$status_array, $currency->Default)?>
  </p>
  <input type="submit" name="save"  class="btn btn-small btn-primary" value="Save">
  <?php if($this->uri->segment(2)=='currency_new'):?>
  <input type="submit" name="cancel"  class="btn btn-small btn-inverse" value="Cancel">
  <?php endif;?>
</form>