<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title>jQuery Leveraged OnChange Method</title>
 
	<style type="text/css">
 
		input.dirty {
			background-color: #660000 ;
			color: #FFFFFF ;
			}
 
	</style>
 
 <script src="<?php echo base_url();?>js/jquery-1.2.6.min.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
	
	<script type="text/javascript">
 
		// When DOM loads, init the page.
		$( InitPage );
 
		// Init the page.
		function InitPage(){
			var jInput = $( ":input" );
 
			// Bind the onchange event of the inputs to flag
			// the inputs as being "dirty".
			jInput.change(
				function( objEvent ){
					// Add dirtry flag to the input in
					// question (whose value has changed).
					$( this ).addClass( "dirty" );
				}
				);
		}

		$('html').keyup(function(e){if(e.keyCode == 8 || event.keyCode == 46)

			alert('backspace trapped')}) 
 
	</script>
</head>
<body>
 
	<h1>
		jQuery Leverages OnChange Method
	</h1>
 
	<form>
 
		<p>
			Data Item 1:
			<input type="text" id="d1" value="" />
		</p>
 
		<p>
			Data Item 2:
			<input type="text" id="d2" value="" />
		</p>
 
		<p>
			Data Item 3:
			<input type="text" id="d3" value="" />
		</p>
 
	</form>
 
</body>
</html>