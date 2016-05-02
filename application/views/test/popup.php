<html lang="en"><head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Imonggo™ - Not just a POS, It's Imonggo</title>
	<link rel="shortcut icon" href="/favicon.ico">	
	<link href="https://kamaroly.c3.imonggo.com/stylesheets/fashion.css" media="screen" rel="stylesheet" type="text/css">
	<link href="https://kamaroly.c3.imonggo.com/stylesheets/fashion_3rdparty.css" media="screen" rel="stylesheet" type="text/css">
	<link href="https://kamaroly.c3.imonggo.com/stylesheets/popup.css" media="screen" rel="stylesheet" type="text/css">
	<link href="https://kamaroly.c3.imonggo.com/stylesheets/print.css" media="print" rel="stylesheet" type="text/css">
		
	<script src="https://kamaroly.c3.imonggo.com/javascripts/prototype.js" type="text/javascript"></script>
	<script src="https://kamaroly.c3.imonggo.com/javascripts/effects.js" type="text/javascript"></script>
	<script src="https://kamaroly.c3.imonggo.com/javascripts/dragdrop.js" type="text/javascript"></script>
	<script src="https://kamaroly.c3.imonggo.com/javascripts/controls.js" type="text/javascript"></script>
	<script src="https://kamaroly.c3.imonggo.com/javascripts/application.js?20120220" type="text/javascript"></script>
	<script src="https://kamaroly.c3.imonggo.com/javascripts/popup.js" type="text/javascript"></script>
	
		<script src="https://kamaroly.c3.imonggo.com/javascripts/views/invoices.js?20121215" type="text/javascript"></script>

</head>
<body id="page" style="">
	<div id="message_area">
		<a id="logo" href="https://kamaroly.c3.imonggo.com/en/home"></a>
		<span id="upgrade"><a href="https://kamaroly.c3.imonggo.com/en/current_account/edit?popup=trial" class="em">Try Premium Features Free</a> |</span>
		
		<a href="#" onclick="new_window('https://kamaroly.c3.imonggo.com/en/helps?language=en&amp;main_topic=invoices&amp;topic=index'); return false;">help</a> |
		<a href="https://kamaroly.c3.imonggo.com/en/session" onclick="var f = document.createElement('form'); f.style.display = 'none'; this.parentNode.appendChild(f); f.method = 'POST'; f.action = this.href;var m = document.createElement('input'); m.setAttribute('type', 'hidden'); m.setAttribute('name', '_method'); m.setAttribute('value', 'delete'); f.appendChild(m);f.submit();return false;">logout</a> 
		<h6 id="welcome_message">Hello <b>Umuntu w'inaha!</b>
			
			
	    </h6>
		<div id="flash_message"></div>    	
	</div>
	<div id="page_header_office" class="page_header">
		<div id="location">OFFICE</div>
		<ul class="menu_bar">			
			 <li><a href="https://kamaroly.c3.imonggo.com/en/products" id="stockroom_menu" onmouseout="$('stockroom_menu').innerHTML = ''" onmouseover="$('stockroom_menu').innerHTML = 'stockroom'"></a></li>
			 <li><a href="https://kamaroly.c3.imonggo.com/en/store" id="store_menu" onmouseout="$('store_menu').innerHTML = ''" onmouseover="$('store_menu').innerHTML = 'store'"></a> </li>
			 <li class="active"><a href="https://kamaroly.c3.imonggo.com/en/office" id="office_menu" onmouseout="$('office_menu').innerHTML = ''" onmouseover="$('office_menu').innerHTML = 'office'"></a> </li>
		</ul>
		
	</div>
	<div id="page_content">		
				
		<div id="yield_contents">

<ul id="side_tab">
	<li class="active"><a href="https://kamaroly.c3.imonggo.com/en/invoices">Sales Invoices</a></li>
	<li><a href="https://kamaroly.c3.imonggo.com/en/reports/summary">Sales Reports</a></li>
	
	<li><a href="https://kamaroly.c3.imonggo.com/en/customers">Customers</a></li>
		
	<li><a href="https://kamaroly.c3.imonggo.com/en/admin">Control Center</a></li>
</ul>


<div id="background_container">
	<div id="background_container_header"></div>
	<div id="background_container_content">				
	<div id="main_container">
		<div id="main_container_header" style="">
		</div>
		<div id="main_container_content">
			<div class="padding">
				<h4 class="indent_bottom">Invoices</h4>
				
				<div class="toolbar_right">
					<a href="https://kamaroly.c3.imonggo.com/en/invoices">All</a> |
					<a href="https://kamaroly.c3.imonggo.com/en/invoices?from_date=2013-09-09+00%3A00%3A00+%2B0000&amp;title=Today%27s+Invoices+%28Sep+09+2013%29&amp;to_date=2013-09-09+23%3A59%3A59+%2B0000">Today</a> |
		   		    <a href="https://kamaroly.c3.imonggo.com/en/invoices?from_date=2013-09-08+00%3A00%3A00+%2B0000&amp;title=Yesterday%27s+Invoices+%28Sep+08+2013%29&amp;to_date=2013-09-08+23%3A59%3A59+%2B0000">Yesterday</a> |
					<a href="https://kamaroly.c3.imonggo.com/en/invoices?from_date=2013-09-01+00%3A00%3A00+%2B0000&amp;title=This+Month%27s+Invoices+%28Sep+2013%29&amp;to_date=2013-09-30+23%3A59%3A59+%2B0000">This Month</a> |
					<a href="https://kamaroly.c3.imonggo.com/en/invoices?from_date=2013-08-01+00%3A00%3A00+%2B0000&amp;title=Last+Month%27s+Invoices+%28Aug+2013%29&amp;to_date=2013-08-31+23%3A59%3A59+%2B0000">Last Month</a> |
					<a href="#" onclick="$('date_range_dialog').popup.show();return false;; return false;">Choose period..</a> 
				</div>
				
				<hr>
			</div>
			
			<!-- Regular Invoices  -->
			<table id="listing">
				<tbody><tr><th align="right">No.</th>
					<th>Ref#</th>					
					<th>Date</th>
					<th></th>
					<th>Amount</th>
					<th>Quantity</th>
					<th>Cashier</th>
					<th>Status</th>
				</tr>
				
				  <tr class="odd">
					<td align="left">3</td>
					<td align="left"></td>					
					<td align="center"><a href="#" onclick="new Ajax.Request('https://kamaroly.c3.imonggo.com/en/invoices/51999', {asynchronous:true, evalScripts:true, method:'get'}); return false;">Sep 09, 13</a></td>
					<td>06:25 AM</td>
					<td align="right">$2,300.00</td>
					<td align="right">8</td>
					<td>Umuntu w'inaha</td>				
					<td></td>				
				  </tr>
				
				  <tr class="even">
					<td align="left">2</td>
					<td align="left"></td>					
					<td align="center"><a href="#" onclick="new Ajax.Request('https://kamaroly.c3.imonggo.com/en/invoices/21741', {asynchronous:true, evalScripts:true, method:'get'}); return false;">Sep 09, 13</a></td>
					<td>06:16 AM</td>
					<td align="right">$1,600.00</td>
					<td align="right">4</td>
					<td>Umuntu w'inaha</td>				
					<td></td>				
				  </tr>
				
				  <tr class="odd">
					<td align="left">1</td>
					<td align="left"></td>					
					<td align="center"><a href="#" onclick="new Ajax.Request('https://kamaroly.c3.imonggo.com/en/invoices/21740', {asynchronous:true, evalScripts:true, method:'get'}); return false;">Jul 15, 13</a></td>
					<td>06:22 AM</td>
					<td align="right">$600.00</td>
					<td align="right">2</td>
					<td>Umuntu w'inaha</td>				
					<td></td>				
				  </tr>
					
			</tbody></table>						
			
			

			<div class="padding toolbar_right">
				
				<a class="g_button white_background" href="#" onclick="new_window('https://kamaroly.c3.imonggo.com/en/invoices?media=print&amp;page=1&amp;per_page=1000');; return false;"><span>Print</span></a>
			</div>			
		</div>

		<div id="main_container_footer"></div>
	</div>
	</div>
<div id="background_container_footer"></div>
</div>

<div id="document_dialog" class="popup" style="display: none;">
	<input class="popup_closebox" id="Close" name="Close" type="hidden" value="">
</div>
<script type="text/javascript">
//<![CDATA[
new Popup('document_dialog','',{modal:true, duration:0});
//]]>
</script>



<div id="email_dialog" class="popup" style="display: none;">
	<input class="popup_closebox" id="Close" name="Close" type="hidden" value="">
	<div class="s_dialog" id="email_dialog_innner"><div class="content"><div class="t"></div>		<h3>Email <span id="email_invoice_no"></span></h3>
	    <p>To:<br>	
		  </p><form id="email_form">
		  <input type="hidden" id="email_invoice_id">
		  <input id="email" name="email" type="text" style="width:300px;">
		  </form>
		<p></p>			
		<p style="margin-top:1em;"> 
			<a id="email_invoice" href="#" onclick="emailInvoice(); return false;" class="g_button white_background"><span>Send</span></a>
			<a id="cancel_email" href="#" onclick="$('email_dialog').popup.hide(); return false;" class="g_button white_background popup_closebox"><span>cancel</span></a>
		</p><br> <!-- BR for IE6 -->
	</div><div class="b"><div></div></div></div><!--[if lte IE 6.5]><iframe class='ie6_hack'></iframe><![endif]--></div>
<div id="print_dialog" class="popup" style="display: none;">
	<input class="popup_closebox" id="Close" name="Close" type="hidden" value="">
	<div class="s_dialog" id="print_dialog_innner"><div class="content"><div class="t"></div>		<h3>Print <span id="print_invoice_no"></span></h3>
  	   Choose a Format<br>
	    <p style="margin-top:1em;">
		  </p><form id="print_form">
		  	<input type="hidden" id="print_invoice_id">			
			<table>
			<tbody><tr><td><input type="radio" id="print_style_standard" name="print_style" value="standard" checked="checked"> Standard </td><td> <small>(standard paper)</small></td></tr>
			<tr><td><input type="radio" id="print_style_receipt" name="print_style" value="receipt"> Receipt</td><td> <small>(tape receipt)</small></td></tr>
			<tr><td><input type="radio" id="print_style_receipt" name="print_style" value="receipt"> Gift Receipt &nbsp;&nbsp;</td><td> <small>(same as tape receipt but no price)</small></td></tr>
			</tbody></table>
		  </form>
		<p></p>					
		<p style="margin-top:1em;"> 
			<a id="print_invoice" href="#" onclick="printInvoice(); return false;" class="g_button white_background"><span>Print</span></a>
			<a id="cancel_print" href="#" onclick="$('print_dialog').popup.hide(); return false;" class="g_button white_background popup_closebox"><span>cancel</span></a>
		</p><br> <!-- BR for IE6 -->
	</div><div class="b"><div></div></div></div><!--[if lte IE 6.5]><iframe class='ie6_hack'></iframe><![endif]--></div>

<div class="popup s_dialog" id="date_range_dialog" style="display: none;"><div class="content"><div class="t"></div>
	<h3 class="popup_draghandle">Filter Invoice by Date Range</h3>
	<p>
		</p><table>
		<tbody><tr><td><label>From </label></td><td><select id="filter_from_date_select_year" name="filter_from_date_select[year]">
<option value="2008">2008</option>
<option value="2009">2009</option>
<option value="2010">2010</option>
<option value="2011">2011</option>
<option value="2012">2012</option>
<option selected="selected" value="2013">2013</option>
<option value="2014">2014</option>
<option value="2015">2015</option>
<option value="2016">2016</option>
<option value="2017">2017</option>
<option value="2018">2018</option>
</select>
<select id="filter_from_date_select_month" name="filter_from_date_select[month]">
<option value="1">January</option>
<option value="2">February</option>
<option value="3">March</option>
<option value="4">April</option>
<option value="5">May</option>
<option value="6">June</option>
<option value="7">July</option>
<option value="8">August</option>
<option selected="selected" value="9">September</option>
<option value="10">October</option>
<option value="11">November</option>
<option value="12">December</option>
</select>
<select id="filter_from_date_select_day" name="filter_from_date_select[day]">
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
<option selected="selected" value="9">9</option>
<option value="10">10</option>
<option value="11">11</option>
<option value="12">12</option>
<option value="13">13</option>
<option value="14">14</option>
<option value="15">15</option>
<option value="16">16</option>
<option value="17">17</option>
<option value="18">18</option>
<option value="19">19</option>
<option value="20">20</option>
<option value="21">21</option>
<option value="22">22</option>
<option value="23">23</option>
<option value="24">24</option>
<option value="25">25</option>
<option value="26">26</option>
<option value="27">27</option>
<option value="28">28</option>
<option value="29">29</option>
<option value="30">30</option>
<option value="31">31</option>
</select>
</td></tr>
		<tr><td><label>To </label></td><td><select id="filter_to_date_select_year" name="filter_to_date_select[year]">
<option value="2008">2008</option>
<option value="2009">2009</option>
<option value="2010">2010</option>
<option value="2011">2011</option>
<option value="2012">2012</option>
<option selected="selected" value="2013">2013</option>
<option value="2014">2014</option>
<option value="2015">2015</option>
<option value="2016">2016</option>
<option value="2017">2017</option>
<option value="2018">2018</option>
</select>
<select id="filter_to_date_select_month" name="filter_to_date_select[month]">
<option value="1">January</option>
<option value="2">February</option>
<option value="3">March</option>
<option value="4">April</option>
<option value="5">May</option>
<option value="6">June</option>
<option value="7">July</option>
<option value="8">August</option>
<option selected="selected" value="9">September</option>
<option value="10">October</option>
<option value="11">November</option>
<option value="12">December</option>
</select>
<select id="filter_to_date_select_day" name="filter_to_date_select[day]">
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
<option selected="selected" value="9">9</option>
<option value="10">10</option>
<option value="11">11</option>
<option value="12">12</option>
<option value="13">13</option>
<option value="14">14</option>
<option value="15">15</option>
<option value="16">16</option>
<option value="17">17</option>
<option value="18">18</option>
<option value="19">19</option>
<option value="20">20</option>
<option value="21">21</option>
<option value="22">22</option>
<option value="23">23</option>
<option value="24">24</option>
<option value="25">25</option>
<option value="26">26</option>
<option value="27">27</option>
<option value="28">28</option>
<option value="29">29</option>
<option value="30">30</option>
<option value="31">31</option>
</select>
</td></tr>
		</tbody></table>
	<p></p>

	<p class="indent_top">
		<a class="g_button white_background submit" href="#" onclick="show_invoice_by_date_range();return false;; return false;"><span>OK</span></a>
		<a class="g_button white_background popup_closebox" href="#" onclick="; return false;"><span>Cancel</span></a>								
	</p>
	 <p class="indent_top"></p>	
</div><div class="b"><div></div></div></div><!--[if lte IE 6.5]><iframe class='ie6_hack'></iframe><![endif]-->

	
<script type="text/javascript">	
 	new Popup('email_dialog','',{modal:true, duration:0});
 	new Popup('print_dialog','',{modal:true, duration:0});
 	new Popup('date_range_dialog','',{modal:true, duration:0});
</script></div>
				
	</div>	
	<div id="page_footer">
		<a href="http://www.imonggo.com" target="blank">Imonggo Home</a> |
		<a href="#" onclick="new Ajax.Request('https://kamaroly.c3.imonggo.com/en/feedbacks/new', {asynchronous:true, evalScripts:true}); return false;">Support</a> |
		<a href="#" onclick="new_window('https://kamaroly.c3.imonggo.com/en/helps?language=en&amp;main_topic=public&amp;topic=privacy'); return false;" t1="public" t2="privacy">Privacy</a> |		
		<a href="#" onclick="new_window('https://kamaroly.c3.imonggo.com/en/helps?language=en&amp;main_topic=public&amp;topic=terms_of_use'); return false;" t1="public" t2="terms_of_use">Terms of Use</a>
		<p>Imonggo™ is a registered trademark of Movmento Pte. Ltd.<br>Copyright © 2012 Movmento Pte. Ltd.</p>
	</div>
		
	<div id="today_summary_dialog" ,="" class="popup" style="display: none;"><span class="popup_closebox"></span></div>
	<script type="text/javascript">
//<![CDATA[
new Popup('today_summary_dialog', '', {modal:true, duration:0});
//]]>
</script>
	<div id="dialog" ,="" class="popup" style="display: none;"><span class="popup_closebox"></span></div>
	<script type="text/javascript">
//<![CDATA[
new Popup('dialog', '', {modal:true, duration:0});
//]]>
</script>
	<div id="progress_popup" ,="" class="popup" style="display: none;">
		<span class="popup_closebox"></span>
		<img src="https://kamaroly.c3.imonggo.com/images/public/spinner.gif" style="float:left;margin-top:5px;">
		<div style="margin-left:50px;"><h3 id="progress_popup_title"></h3><p id="progress_popup_message"></p></div>
	</div>
	<script type="text/javascript">
//<![CDATA[
new Popup('progress_popup', '', {modal:true, duration:0});
//]]>
</script>		

	<div id="popup_dialog" ,="" class="popup" style="display: none;"><span class="popup_closebox"></span></div>
	<script type="text/javascript">
//<![CDATA[
new Popup('popup_dialog', '', {modal:true, duration:0});
//]]>
</script>
	
	


</body></html>