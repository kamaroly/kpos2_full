<?php $this->load->view("partial/header"); ?>


<script type="text/javascript">
$(document).ready(function()
{
    init_table_sorting();
    enable_select_all();
    enable_checkboxes();
    enable_row_selection();
    enable_search('<?php echo site_url("$controller_name/suggest")?>','<?php echo $this->lang->line("common_confirm_search")?>');
    enable_delete('<?php echo $this->lang->line($controller_name."_confirm_delete")?>','<?php echo $this->lang->line($controller_name."_none_selected")?>');
    enable_bulk_edit('<?php echo $this->lang->line($controller_name."_none_selected")?>');

    $('#generate_barcodes').click(function()
    {
    	var selected = get_selected_values();
    	if (selected.length == 0)
    	{
    		alert('<?php echo $this->lang->line('items_must_select_item_for_barcode'); ?>');
    		return false;
    	}

    	$(this).attr('href','index.php/items/generate_barcodes/'+selected.join(':'));
    });

    $("#low_inventory").click(function()
    {
    	$('#items_filter_form').submit();
    });

    $("#is_serialized").click(function()
    {
    	$('#items_filter_form').submit();
    });

    $("#no_description").click(function()
    {
    	$('#items_filter_form').submit();
    });

});

function init_table_sorting()
{
	//Only init if there is more than one row
	if($('.tablesorter tbody tr').length >1)
	{
		$("#sortable_table").tablesorter(
		{
			sortList: [[1,0]],
			headers:
			{
				0: { sorter: false},
				8: { sorter: false},
				9: { sorter: false}
			}

		});
	}
}

function post_item_form_submit(response)
{
	if(!response.success)
	{
		set_feedback(response.message,'error_message',true);
	}
	else
	{
		//This is an update, just update one row
		if(jQuery.inArray(response.item_id,get_visible_checkbox_ids()) != -1)
		{
			update_row(response.item_id,'<?php echo site_url("$controller_name/get_row")?>');
			set_feedback(response.message,'success_message',false);

		}
		else //refresh entire table
		{
			do_search(true,function()
			{
				
				//highlight new row
				hightlight_row(response.item_id);
				location.reload();
				set_feedback(response.message,'success_message',false);
				
			});
		}
	}
}

function post_bulk_form_submit(response)
{
	if(!response.success)
	{
		set_feedback(response.message,'error_message',true);
	}
	else
	{
		var selected_item_ids=get_selected_values();
		for(k=0;k<selected_item_ids.length;k++)
		{
			update_row(selected_item_ids[k],'<?php echo site_url("$controller_name/get_row")?>');
		}
		location.reload();
		set_feedback(response.message,'success_message',false);
		
	}
}

function show_hide_search_filter(search_filter_section, switchImgTag) {
        var ele = document.getElementById(search_filter_section);
        var imageEle = document.getElementById(switchImgTag);
        var elesearchstate = document.getElementById('search_section_state');
        if(ele.style.display == "block")
        {
                ele.style.display = "none";
				imageEle.innerHTML = '<img src=" <?php echo base_url()?>images/plus.png" style="border:0;outline:none;padding:0px;margin:0px;position:relative;top:-5px;" >';
                elesearchstate.value="none";
        }
        else
        {
                ele.style.display = "block";
                imageEle.innerHTML = '<img src=" <?php echo base_url()?>images/minus.png" style="border:0;outline:none;padding:0px;margin:0px;position:relative;top:-5px;" >';
                elesearchstate.value="block";
        }
}

</script>

<div id="title_bar">
	<div class="button green float_left "><?php echo $this->lang->line('common_list_of').' '.$this->lang->line('module_'.$controller_name); ?></div>
	<div id="new_button">
	<?php echo anchor("items",
		"<div class='btn btn-small btn-primary' style='float: left;'>".$this->lang->line('manage_items')."</div>",
		array('class'=>'','title'=>$this->lang->line($controller_name.'_new')));
		?>
	
		<?php echo anchor("$controller_name/view//width:$form_width",
		"<div class='btn btn-small btn-primary' style='float: left;'>".$this->lang->line($controller_name.'_new')."</div>",
		array('class'=>'thickbox none','title'=>$this->lang->line($controller_name.'_new')));
		?>
		<?php echo anchor("$controller_name/excel_import/width:$form_width",
		"<div class='btn btn-small btn-primary' style='float: left;'>Excel Import</div>",
		array('class'=>'thickbox none','title'=>'Import Items from Excel'));
		?>
	</div>
</div>


<?php echo $this->pagination->create_links();?>
<div id="table_action_header">
	<ul>
		<li class="float_left"><?php echo anchor("$controller_name/delete",$this->lang->line("common_delete"),array('id'=>'delete','class'=>'btn btn-small btn-primary small')); ?></li>
		<li class="float_right">
		<img src='<?php echo base_url()?>images/spinner_small.gif' alt='spinner' id='spinner' />
		<?php echo form_open("$controller_name/search",array('id'=>'search_form')); ?>
		<input type="text" name ='search' id='search'/>
		</form>
		</li>
	</ul>
</div>
<?php echo form_open("$controller_name/delete")?>
<div id="rounded-corner">
<?php echo $manage_table; ?>
</div>
<?php echo form_close();?>
<div id="feedback_bar"></div>
<?php $this->load->view("partial/footer"); ?>