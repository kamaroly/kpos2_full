<link rel="stylesheet" rev="stylesheet" href="<?php echo base_url();?>css/navigationmenu.css" />

     <nav>
       <ul id="top_nav" class="clearfix"> 
          <li><?php 	echo "<div class='btn btn-small btn-primary' id='finish_sale_button' style='float:right;margin-top:5px;'><span>".$this->lang->line('sales_complete_sale')."</span></div>";?></li>
	     <li><?php 	echo "<div class='btn btn-small btn-success' id='quotation_sale_button' style='float:right;margin-top:5px;'><span>".'Quotation'."</span></div>";?></li>
         <li><?php 	echo "<div class='btn btn-small btn-warning' id='suspend_sale_button' style='float:right;margin-top:5px;'><span>".'Save Draft'."</span></div>";?>	</li>
         <li><div class='btn btn-small btn-danger' id='cancel_sale_button' style='margin-top:5px;'>
			<span><?php echo $this->lang->line('sales_cancel_sale'); ?></span>
		</div></li>
        </ul>
      </nav>