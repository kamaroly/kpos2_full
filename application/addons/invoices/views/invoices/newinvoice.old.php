<script type="text/javascript" src="http://localhost/bamboo-invoice/js/bamboo.js"></script>
<script type="text/javascript" src="http://localhost/bamboo-invoice/js/prototype.js"></script>

<script type="text/javascript" src="http://localhost/bamboo-invoice/js/scriptaculous/scriptaculous.js?load=effects,dragdrop"></script>

<script type="text/javascript">
base_url = "http://localhost/bamboo-invoice//";
base_url_no_index = "http://localhost/bamboo-invoice/";
bi_currency_symbol = new String("$");
lang_invoice_date_issued = new String("Date Issued ");
lang_invoice_change = new String("Change");
lang_amount_error = new String("Please enter an amount");
lang_delete = new String("Delete");
lang_edit = new String("Edit");
lang_numbers_only = new String("Numbers only please");
lang_field_required = new String("This field is required");
lang_clients_contact_add = new String("Unable to add this contact. Please note that First Name, Last Name and a valid email are required.");
lang_error_email_recipients = new String("Please select at least 1 recipient for this invoice");
lang_error_login_username = new String("Provide a username please");
lang_error_login_password = new String("Provide a password please");
lang_invoice = new String("Invoice");
lang_client_name = new String("Client Name");
lang_amount = new String("Amount");
lang_status = new String("Status");
lang_quantity = new String("Quantity");
lang_work_description = new String("Work Description");
lang_taxable = new String("Taxable");
lang_amount = new String("Amount");
</script>
<link type="text/css" rel="stylesheet" href="http://localhost/bamboo-invoice/css/calendar.css" />
<link type="text/css" rel="stylesheet" href="http://localhost/bamboo-invoice/css/invoice.css" />
<script type="text/javascript">
var taxable = true;
var tax1_rate = 0.000;
var tax2_rate = 0.000;
var datePicker1 = "2013-07-25";
var datePicker2 = "July 25, 2013";
</script>
<script type="text/javascript" src="http://localhost/bamboo-invoice/js/createinvoice.js"></script>
<script type="text/javascript">
<!--
var form_name	= "my_form";
var format		= 'us'; // eu or us
var days		= new Array(
					'Su', // Sunday, short name
					'Mo', // Monday, short name
					'Tu', // Tuesday, short name
					'Wed', // Wednesday, short name
					'Thu', // Thursday, short name
					'Fri', // Friday, short name
					'Sat' // Saturday, short name
				);
var months		= new Array(
					'January',
					'February',
					'March',
					'April',
					'May',
					'June',
					'July',
					'August',
					'September',
					'October',
					'November',
					'December'
				);
var last_click	= new Array();
var current_month  = '';
var current_year   = '';
var last_date  = '';
	
function calendar(id, d, highlight, adjusted)
{		
	if (adjusted == undefined)
	{	
		var d = new Date(d * 1000);
	}

	this.id			= id;
	this.highlight	= highlight;
	this.date_obj	= d;
	this.write		= build_calendar;
	this.total_days	= total_days;
	this.month		= d.getMonth();
	this.date		= d.getDate();
	this.day		= d.getDay();
	this.year		= d.getFullYear();
	this.hours		= d.getHours();
	this.minutes	= d.getMinutes();
	this.seconds	= d.getSeconds();
	this.date_str	= date_str;
				
	if (highlight == false)
	{
		this.selected_date = '';
	}
	else
	{
		this.selected_date = this.year + '' + this.month + '' + this.date;
	}
			
	//	Set the "selected date"
	d.setDate(1);
	this.firstDay = d.getDay();
	
	//then reset the date object to the correct date
	d.setDate(this.date);
}
		
//	Build the body of the calendar
function build_calendar()
{
	var str = '';
	
	//	Calendar Heading
	
	str += '<div id="cal' + this.id + '">';
	str += '<table class="calendar" cellspacing="0" cellpadding="0" border="0" >';
	str += '<tr>';
	str += '<td class="calnavleft" onClick="change_month(-1, \'' + this.id + '\')">&lt;&lt;<\/td>';
	str += '<td colspan="5" class="calheading">' + months[this.month] + ' ' + this.year + '<\/td>';
	str += '<td class="calnavright" onClick="change_month(1, \'' + this.id + '\')">&gt;&gt;<\/td>';
	str += '<\/tr>';
	
	//	Day Names
	
	str += '<tr>';
	
	for (i = 0; i < 7; i++)
	{
		str += '<td class="caldayheading">' + days[i] + '<\/td>';
	}
	
	str += '<\/tr>';
	
	//	Day Cells
		
	str += '<tr>';
	
	selDate = (last_date != '') ? last_date : this.date;
	
	for (j = 0; j < 42; j++)
	{
		var displayNum = (j - this.firstDay + 1);
		
		if (j < this.firstDay) // leading empty cells
		{
			str += '<td class="calblanktop">&nbsp;<\/td>';
		}
		else if (displayNum == selDate && this.highlight == true) // Selected date
		{
			str += '<td id="' + this.id +'selected" class="caldayselected" onClick="set_date(this,\'' + this.id + '\')">' + displayNum + '<\/td>';
		}
		else if (displayNum > this.total_days())
		{
			str += '<td class="calblankbot">&nbsp;<\/td>'; // trailing empty cells
		}
		else  // Unselected days
		{
			str += '<td id="" class="caldaycells" onClick="set_date(this,\'' + this.id + '\'); return false;"  onMouseOver="javascript:cell_highlight(this,\'' + displayNum + '\',\'' + this.id + '\');" onMouseOut="javascript:cell_reset(this,\'' + displayNum + '\',\'' + this.id + '\');" >' + displayNum + '<\/td>';
		}
		
		if (j % 7 == 6)
		{
			str += '<\/tr><tr>';
		}
	}

	str += '<\/tr>';	
	str += '<\/table>';
	str += '<\/div>';
	
	return str;
}

//	Total number of days in a month
function total_days()
{	
	switch(this.month)
	{
		case 1: // Check for leap year
			if ((  this.date_obj.getFullYear() % 4 == 0
				&& this.date_obj.getFullYear() % 100 != 0)
				|| this.date_obj.getFullYear() % 400 == 0)
				return 29;
			else
				return 28;
		case 3:
			return 30;
		case 5:
			return 30;
		case 8:
			return 30;
		case 10:
			return 30
		default:
			return 31;
	}
}

//	Highlight Cell on Mouseover
function cell_highlight(td, num, cal)
{
	cal = eval(cal);

	if (last_click[cal.id]  != num)
	{
		td.className = "caldaycellhover";
	}
}		

//	Reset Cell on MouseOut
function cell_reset(td, num, cal)
{	
	cal = eval(cal);

	if (last_click[cal.id] == num)
	{
		td.className = "caldayselected";
	}
	else
	{
		td.className = "caldaycells";
	}
}		

//	Clear Field
function clear_field(id)
{				
	eval("document." + form_name + "." + id + ".value = ''");
	
	document.getElementById(id + "selected").className = "caldaycells";
	document.getElementById(id + "selected").id = "";	
	
	cal = eval(id);
	cal.selected_date = '';		
}		


//	Set date to specified time
function set_to_time(id, raw)
{			
	if (document.getElementById(id + "selected"))
	{			
		document.getElementById(id + "selected").className = "caldaycells";
		document.getElementById(id + "selected").id = "";	
	}
	
	document.getElementById('cal' + id).innerHTML = '<div id="tempcal'+id+'">&nbsp;<'+'/div>';				
		
	var nowDate = new Date();
	nowDate.setTime = raw * 1000;
	
	current_month	= nowDate.getMonth();
	current_year	= nowDate.getFullYear();
	current_date	= nowDate.getDate();
	
	oldcal = eval(id);
	oldcal.selected_date = current_year + '' + current_month + '' + current_date;				

	cal = new calendar(id, nowDate, true, true);		
	cal.selected_date = current_year + '' + current_month + '' + current_date;	
	
	last_date = cal.date;
	
	document.getElementById('tempcal'+id).innerHTML = cal.write();	
	
	insert_date(cal);
}

//	Set date to what is in the field
var lastDates = new Array();

function update_calendar(id, dateValue)
{
	if (lastDates[id] == dateValue) return;
	
	lastDates[id] = dateValue;
	
	var fieldString = dateValue.replace(/\s+/g, ' ');
	
	while (fieldString.substring(0,1) == ' ')
	{
		fieldString = fieldString.substring(1, fieldString.length);
	}
	
	var dateString = fieldString.split(' ');
	var dateParts = dateString[0].split('-')

	if (dateParts.length < 3) return;
	var newYear  = dateParts[0];
	var newMonth = dateParts[1];
	var newDay   = dateParts[2];
	
	if (isNaN(newDay)  || newDay < 1 || (newDay.length != 1 && newDay.length != 2)) return;
	if (isNaN(newYear) || newYear < 1 || newYear.length != 4) return;
	if (isNaN(newMonth) || newMonth < 1 || (newMonth.length != 1 && newMonth.length != 2)) return;
	
	if (newMonth > 12) newMonth = 12;
	
	if (newDay > 28)
	{
		switch(newMonth - 1)
		{
			case 1: // Check for leap year
				if ((newYear % 4 == 0 && newYear % 100 != 0) || newYear % 400 == 0)
				{
					if (newDay > 29) newDay = 29;
				}
				else
				{
					if (newDay > 28) newDay = 28;
				}
			case 3:
				if (newDay > 30) newDay = 30;
			case 5:
				if (newDay > 30) newDay = 30;
			case 8:
				if (newDay > 30) newDay = 30;
			case 10:
				if (newDay > 30) newDay = 30;
			default:
				if (newDay > 31) newDay = 31;
		}
	}
	
	if (document.getElementById(id + "selected"))
	{			
		document.getElementById(id + "selected").className = "caldaycells";
		document.getElementById(id + "selected").id = "";	
	}
	
	document.getElementById('cal' + id).innerHTML = '<div id="tempcal'+id+'">&nbsp;<'+'/div>';				
		
	var nowDate = new Date();
	nowDate.setDate(newDay);
	nowDate.setMonth(newMonth - 1);
	nowDate.setYear(newYear);
	nowDate.setHours(12);
	
	current_month	= nowDate.getMonth();
	current_year	= nowDate.getFullYear();

	cal = new calendar(id, nowDate, true, true);						
	document.getElementById('tempcal'+id).innerHTML = cal.write();	
}

//	Set the date
function set_date(td, cal)
{					

	cal = eval(cal);
	
	// If the user is clicking a cell that is already
	// selected we'll de-select it and clear the form field
	
	if (last_click[cal.id] == td.firstChild.nodeValue)
	{
		td.className = "caldaycells";
		last_click[cal.id] = '';
		remove_date(cal);
		cal.selected_date =  '';
		return;
	}
				
	// Onward!
	if (document.getElementById(cal.id + "selected"))
	{
		document.getElementById(cal.id + "selected").className = "caldaycells";
		document.getElementById(cal.id + "selected").id = "";
	}
									
	td.className = "caldayselected";
	td.id = cal.id + "selected";

	cal.selected_date = cal.date_obj.getFullYear() + '' + cal.date_obj.getMonth() + '' + cal.date;			
	cal.date_obj.setDate(td.firstChild.nodeValue);
	cal = new calendar(cal.id, cal.date_obj, true, true);
	cal.selected_date = cal.date_obj.getFullYear() + '' + cal.date_obj.getMonth() + '' + cal.date;			
	
	last_date = cal.date;

	//cal.date
	last_click[cal.id] = cal.date;
				
	// Insert the date into the form
	insert_date(cal);
}
/*
//	Insert the date into the form field
function insert_date(cal)
{
	cal = eval(cal);
	fval = eval("document." + form_name + "." + cal.id);	
	
	if (fval.value == '')
	{
		fval.value = cal.date_str('y');
	}
	else
	{
		time = fval.value.substring(10);
		new_date = cal.date_str('n') + time;
		fval.value = new_date;
	}	
}
*/		
//	Remove the date from the form field
function remove_date(cal)
{
	cal = eval(cal);
	fval = eval("document." + form_name + "." + cal.id);	
	fval.value = '';
}

//	Change to a new month
function change_month(mo, cal)
{		
	cal = eval(cal);

	if (current_month != '')
	{
		cal.date_obj.setMonth(current_month);
		cal.date_obj.setYear(current_year);
	
		current_month	= '';
		current_year	= '';
	}
				
	var newMonth = cal.date_obj.getMonth() + mo;
	var newDate  = cal.date_obj.getDate();
	
	if (newMonth == 12)
	{
		cal.date_obj.setYear(cal.date_obj.getFullYear() + 1)
		newMonth = 0;
	}
	else if (newMonth == -1)
	{
		cal.date_obj.setYear(cal.date_obj.getFullYear() - 1)
		newMonth = 11;
	}
	
	if (newDate > 28)
	{
		var newYear = cal.date_obj.getFullYear();
		
		switch(newMonth)
		{
			case 1: // Check for leap year
				if ((newYear % 4 == 0 && newYear % 100 != 0) || newYear % 400 == 0)
				{
					if (newDate > 29) newDate = 29;
				}
				else
				{
					if (newDate > 28) newDate = 28;
				}
			case 3:
				if (newDate > 30) newDate = 30;
			case 5:
				if (newDate > 30) newDate = 30;
			case 8:
				if (newDate > 30) newDate = 30;
			case 10:
				if (newDate > 30) newDate = 30;
			default:
				if (newDate > 31) newDate = 31;
		}
	}
	
	cal.date_obj.setDate(newDate);
	cal.date_obj.setMonth(newMonth);
	new_mdy	= cal.date_obj.getFullYear() + '' + cal.date_obj.getMonth() + '' + cal.date;
	
	highlight = (cal.selected_date == new_mdy) ? true : false;			
	cal = new calendar(cal.id, cal.date_obj, highlight, true); 			
	document.getElementById('cal' + cal.id).innerHTML = cal.write();	
}

//	Finalize the date string
function date_str(time)
{
	var month = this.month + 1;
	if (month < 10)
		month = '0' + month;
		
	var day		= (this.date  < 10) 	?  '0' + this.date		: this.date;
	var minutes	= (this.minutes  < 10)	?  '0' + this.minutes	: this.minutes;
		
	if (format == 'us')
	{
		var hours	= (this.hours > 12) ? this.hours - 12 : this.hours;
		var ampm	= (this.hours > 11) ? 'PM' : 'AM'
	}
	else
	{
		var hours	= this.hours;
		var ampm	= '';
	}
	
	if (time == 'y')
	{
		return this.year + '-' + month + '-' + day + '  ' + hours + ':' + minutes + ' ' + ampm;		
	}
	else
	{
		return this.year + '-' + month + '-' + day;
	}
}

//-->
</script>


<h2><?php echo $this->lang->line('invoice_new_invoice_to') . ' ' . $row->name;?></h2>

<!-- This is here only so that we can clone it when trying to create a new itemized 
series. It is outside the form, and thus will not submit (as an empty item) with the 
rest of the itemized items. -->
<div id="itemized_invoice_node" style="display: none;">
<p><label><?php echo $this->lang->line('invoice_item');?> <input type="text" class="item" name="item" size="40" /></label> <label><?php echo $this->lang->line('invoice_quantity');?> <input type="text" class="quantity" name="quantity" size="5" value="1" onblur="recalculate_items();" /></label> <label><?php echo $this->lang->line('invoice_amount');?> <?php echo $this->settings_model->get_setting('currency_symbol');?><input type="text" class="amount" name="amount" size="5" value="0.00" onblur="recalculate_items();" /></label></p>
</div>

<?php echo form_open('invoices/newinvoice', array('id' => 'createInvoice', 'name' => 'my_form', 'autocomplete' => 'off'));?>
	<input type="hidden" name="client_id" value="<?php echo $row->id;?>" />
<?php if ($row->tax_status):?>
	<input type="hidden" name="tax1_description" value="<?php echo $tax1_desc;?>" />
	<input type="hidden" name="tax1_rate" value="<?php echo $tax1_rate;?>" />
	<input type="hidden" name="tax2_description" value="<?php echo $tax2_desc;?>" />
	<input type="hidden" name="tax2_rate" value="<?php echo $tax2_rate;?>" />
<?php endif;?>
	<p>
		<label><?php echo $this->lang->line('invoice_number');?> <input type="text" name="invoice_number" id="invoice_number" value="<?php echo ($this->validation->invoice_number) ? ($this->validation->invoice_number) : ($suggested_invoice_number);?>" /></label> 
		<em>(<?php echo $this->lang->line('invoice_last_used') . ' ' . $lastInvoiceNumber;?>)</em> <?php echo $this->validation->invoice_number_error; ?>
	</p>
	<p id="dateIssuedContainer">
		<label><?php echo $this->lang->line('invoice_date_issued_full');?> <input type="text" name="dateIssued" id="dateIssued" value="<?php echo $invoiceDate; ?>"/></label><?php echo $this->validation->dateIssued_error; ?>
	</p>
<div id="cal1Container" style="display: none;">
<?php echo js_calendar_write('entry_date', time(), true);?>
</div>

<div class="work_description">
	<table class="invoice_items">
		<thead>
		<tr>
			<th><?php echo $this->lang->line('invoice_quantity');?></th>
			<th><?php echo $this->lang->line('invoice_work_description');?></th>
			<th><?php echo $this->lang->line('invoice_taxable');?></th>
			<th><?php echo $this->lang->line('invoice_amount_item');?></th>
			<th>&nbsp;</th>
		</tr>
		</thead>
		<tbody id="item_area">
		<tr class="item_row">
			<td><p><label><span><?php echo $this->lang->line('invoice_quantity');?></span><input type="text" name="items[1][quantity]" size="3" value="1" onkeyup="recalculate_items();" /></label></p></td> 
			<td>
				<p>
				<label><span><?php echo $this->lang->line('invoice_work_description');?></span>
				<textarea name="items[1][work_description]" id="work_description" cols="10" rows="3"></textarea>
				</label>
				</p>
			</td>
			<td><p><label><input type="checkbox" name="items[1][taxable]" value="1" onclick="recalculate_items();" <?php if ($row->tax_status) {echo 'checked="checked" ';}?>/><span><?php echo $this->lang->line('invoice_taxable');?>?</span></label></p></td>
			<td nowrap="nowrap"><p><label><span><?php echo $this->lang->line('invoice_amount');?></span><?php echo $this->settings_model->get_setting('currency_symbol');?><input type="text" id="amount" name="items[1][amount]" size="5" value="0.00" onkeyup="recalculate_items();" value="" /></label></p></td>
			<td>&nbsp;</td>
		</tr>
		</tbody>
	</table>

	<p class="button" style="display:none;" id="new_item"><a href="#" onclick="return create_itemized_fields();" class="clientnew"><img src="<?php echo base_url();?>img/add_row.png" style="margin-bottom:-3px;" alt="" /> <?php echo $this->lang->line('invoice_new_item');?></a></p>

</div>

<div class="amount_listing">
	<p><?php echo $this->lang->line('invoice_amount');?> <?php echo $this->settings_model->get_setting('currency_symbol');?><span id="item_amount">0.00</span></p>
<?php if ($row->tax_status):?>
	<p><?php echo $tax1_desc;?> (<?php echo $tax1_rate;?>%) <?php echo $this->settings_model->get_setting('currency_symbol');?><span id="item_tax1amount">0.00</span></p>
	<?php if ($tax2_rate != 0):?>
	<p><?php echo $tax2_desc;?> (<?php echo $tax2_rate;?>%) <?php echo $this->settings_model->get_setting('currency_symbol');?><span id="item_tax2amount">0.00</span></p>
	<?php endif;?>
	<p><?php echo $this->lang->line('invoice_total');?> <?php echo $this->settings_model->get_setting('currency_symbol');?><span id="item_total_amount">0.00</span></p>
<?php else:?>
	<p class="error"><?php echo $this->lang->line('invoice_tax_exempt');?></p>
<?php endif;?>
</div>

	<p>
	<label><?php echo $this->lang->line('invoice_note');?> <?php echo $this->validation->invoice_note_error; ?><br />
	<textarea name="invoice_note" id="invoice_note" cols="80" rows="3"><?php echo ($this->validation->invoice_note) ? ($this->validation->invoice_note) : ($invoice_note_default);?></textarea>
	</label>
	</p>
	<p>
	<input type="submit" name="createInvoice" id="createInvoice" value="<?php echo $this->lang->line('actions_create_invoice');?>" />
	</p>
</form>

