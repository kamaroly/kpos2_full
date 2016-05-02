<!DOCTYPE html>
<html>
  
<head>
    <title>Kamaro Point of Sale Installer</title>
    <!-- Bootstrap -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/css/prettify.css" rel="stylesheet">
  </head>
<?php

error_reporting(0); //Setting this to E_ALL showed that that cause of not redirecting were few blank lines added in some php files.

$db_config_path = '../application/config/database.php';

// Only load the classes in case the user submitted the form
if($_POST) {

	// Load the classes and create the new objects
	require_once('includes/core_class.php');
	require_once('includes/database_class.php');

	$core = new Core();
	$database = new Database();


    //validations
    $data=$_POST;
    if ($data['password']!=$data['repeat_password']) 
         {
         	# code...
			$message = $core->show_message('error',"Loging Passwords doesn't .");
         }
    elseif (strlen($data['tin'])!=9 && !is_numeric($data['tin'])) {
         	# code...
         	
			$message = $core->show_message('error',"Incorrect TIN , TIN must be 9 numbers .");
    }
	// Validate the post data
	elseif($core->validate_post($_POST) == true)
	{

		// First create the database, then create tables, then write config file
		if($database->create_database($_POST) == false) 
		{
			$message = $core->show_message('error',"The database could not be created, please verify your settings.");
		} 
		else if ($database->create_tables($_POST) == false) 
		{
			$message = $core->show_message('error',"The database tables could not be created, please verify your settings.");
		} 
		else if ($core->write_config($_POST) == false) 
		{
			$message = $core->show_message('error',"The database configuration file could not be written, please chmod application/config/database.php file to 777");
		}

		// If no errors, redirect to registration page
		if(!isset($message)) 
		{
          //CREATING THE FILE
          $my_file = '../kpos.dll';
          $handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file); //implicitly creates file
          $handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
          $data = 'kpos is installed SUCCESSFULLY';
          fwrite($handle, $data);
          fclose($handle);

		  $redir = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
          $redir .= "://".$_SERVER['HTTP_HOST'];
          $redir .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
          $redir = str_replace('install/','',$redir); 

	    echo '<button type="button" class="btn btn-default btn-lg">
         <span class="glyphicon glyphicon-ok"></span> KPOS INSTALLATION DONE SUCCESSFULLY, we are taking you to KPOS
         </button>';
		header('Refresh: 5;'.$redir);
		
		}
     exit;
	}
	else 
	{
		$message = $core->show_message('error','Not all fields have been filled in correctly. The host, username, password, and database name are required.');
	}
}

?>

  <body>
    <div class='container'>
		
		<div class="span12">
			<section id="wizard">

	           <form id="repeat_password" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<div id="rootwizard">
					<div class="navbar navbar-inverse">
					  <div class="navbar-inner ">
					    <div class="container">
					<ul>
					  	<li><a href="#tab1" data-toggle="tab">Database settings</a></li>
						<li><a href="#tab2" data-toggle="tab">Account Setup</a></li>
						<li><a href="#tab3" data-toggle="tab">Application Configuration</a></li>

					</ul>
					 </div>

					  </div>
					</div>
					<div>  <?php if(is_writable($db_config_path)):?>

		                     <?php if(isset($message)) {echo '<p class="alert alert-danger">' . $message . '</p>';}?>
		              </div>
					<div id="bar" class="progress progress-striped active">
					  <div class="bar"></div>
					</div>
						<ul class="pager wizard">
							<li class="previous"><a href="#" class="btn btn-small btn-primary">Previous</a></li>
						  	<li class="next"><a href="#" class="btn btn-small btn-primary">Next</a></li>
						</ul>
					<div class="tab-content">
					    <div class="tab-pane" id="tab1">
					      <legend>Database settings</legend>
                          <label for="hostname">Hostname</label><input type="text" id="hostname" value="localhost" class="input_text" name="hostname" />
                          <label for="username">Username</label><input type="text" id="dbusername" class="input_text" name="dbusername" />
                          <label for="password">Password</label><input type="password" id="dbpassword" class="input_text" name="dbpassword" />
                          <label for="database">Database Name</label><input type="text" id="database" class="input_text" name="database" />
                          <label for="dbprefix">DB prefix</label><input type="text" id="dbprefix" class="input_text" name="dbprefix" />
    
					    </div>
					    <div class="tab-pane" id="tab2">


                             <legend>Employee Login Info</legend>
                             <div class="field_row clearfix">	
                               <label for="first_name" class="required">First Name:</label>	<div class="form_field">
	                            <input type="text" name="first_name" PlaceHolder="First Name" id="first_name" class="">	</div>
                             </div>
                             <div class="field_row clearfix">	
                              <label for="last_name" class="required">Last Name:</label>	<div class="form_field">
	                          <input type="text" name="last_name" Placeholder="Last Name" id="last_name" class="">	</div>
                              </div>
                             <div class="field_row clearfix">	
                             <label for="username" class="required">Username:</label>	<div class="form_field">
	                             <input type="text" name="username" PlaceHolder="admin" id="username">	</div>
                             </div>
           

                             <div class="field_row clearfix">	
                             <label for="password">Password:</label>	<div class="form_field">
	                             <input type="password" name="password" PlaceHolder="Password" id="password">	</div>
                             </div>


                             <div class="field_row clearfix">	
                             <label for="repeat_password">Password Again:</label>	<div class="form_field">
	                             <input type="password" name="repeat_password" PlaceHolder="Confirm Paassword" id="repeat_password">	</div>
                             </div>

                             <div class="field_row clearfix">	
                             <label>Language:</label>	<div class="form_field">
                             <select name="language">
                             <option value="english" selected="selected">English</option>
                             <option value="kinyarwanda">kinyarwanda</option>
                             <option value="france">france</option>
                             <option value="spanish">spanish</option>
                             </select>	</div>
                             </div>

                             </fieldset>
					    </div>
						<div class="tab-pane" id="tab3">

<legend>Store Configuration Information</legend>
<div class="field_row clearfix">	
<label for="company" class="wide required">Company Name:</label>	<div class="form_field">
	<input type="text" name="company" placeholder="COMPANY LTD" id="company">	</div>
</div>

<div class="field_row clearfix">	
<label for="company" class="wide required">TIN(Tax Identification Number):</label>	<div class="form_field">
	<input type="text" name="tin" placeholder="100600570" id="tin">	</div>
</div>
<div class="field_row clearfix">	
<label for="currency" class="wide required">Currency:</label>	<div class="form_field">
	<input type="text" name="currency_name"   placeholder="Rwandan Francs" id="currency_name">	
	<input type="text" name="currency_symbol" placeholder="RWF" id="currency_symbol">	
	</div>
</div>
<div class="field_row clearfix">	
<label for="address" class="wide required">Company Address:</label>	<div class="form_field">
	<textarea name="address" cols="17" rows="4" id="address"></textarea>	</div>
</div>

<div class="field_row clearfix">	
<label for="phone" class="wide required">Company Phone:</label>	<div class="form_field">
	<input type="text" name="phone" placeholder="25072200000" id="phone">	</div>
</div>

<div class="field_row clearfix">	
<label for="email" class="wide required">E-Mail:</label>	<div class="form_field">
	<input type="text" name="email" placeholder="contact@example.com" id="email">	</div>
</div>


<div class="field_row clearfix">	
<label for="fax" class="wide">Fax:</label>	<div class="form_field">
	<input type="text" name="fax" placeholder="1234" id="fax">	</div>
</div>

<div class="field_row clearfix">	
<label for="website" class="wide">Website:</label>	<div class="form_field">
	<input type="text" name="website" placeholder="www.example.com" id="website">	</div>
</div>

<div class="field_row clearfix">	
<label for="timezone" class="wide required">Timezone:</label>	<div class="form_field">
	<select name="timezone" id="select2">
<option value="Pacific/Midway">(GMT-11:00) Midway Island, Samoa</option>
<option value="America/Adak">(GMT-10:00) Hawaii-Aleutian</option>
<option value="Etc/GMT+10">(GMT-10:00) Hawaii</option>
<option value="Pacific/Marquesas">(GMT-09:30) Marquesas Islands</option>
<option value="Pacific/Gambier">(GMT-09:00) Gambier Islands</option>
<option value="America/Anchorage">(GMT-09:00) Alaska</option>
<option value="America/Ensenada">(GMT-08:00) Tijuana, Baja California</option>
<option value="Etc/GMT+8">(GMT-08:00) Pitcairn Islands</option>
<option value="America/Los_Angeles">(GMT-08:00) Pacific Time (US &amp; Canada)</option>
<option value="America/Denver">(GMT-07:00) Mountain Time (US &amp; Canada)</option>
<option value="America/Chihuahua">(GMT-07:00) Chihuahua, La Paz, Mazatlan</option>
<option value="America/Dawson_Creek">(GMT-07:00) Arizona</option>
<option value="America/Belize">(GMT-06:00) Saskatchewan, Central America</option>
<option value="America/Cancun">(GMT-06:00) Guadalajara, Mexico City, Monterrey</option>
<option value="Chile/EasterIsland">(GMT-06:00) Easter Island</option>
<option value="America/Chicago">(GMT-06:00) Central Time (US &amp; Canada)</option>
<option value="America/New_York">(GMT-05:00) Eastern Time (US &amp; Canada)</option>
<option value="America/Havana">(GMT-05:00) Cuba</option>
<option value="America/Bogota">(GMT-05:00) Bogota, Lima, Quito, Rio Branco</option>
<option value="America/Caracas">(GMT-04:30) Caracas</option>
<option value="America/Santiago">(GMT-04:00) Santiago</option>
<option value="America/La_Paz">(GMT-04:00) La Paz</option>
<option value="Atlantic/Stanley">(GMT-04:00) Faukland Islands</option>
<option value="America/Campo_Grande">(GMT-04:00) Brazil</option>
<option value="America/Goose_Bay">(GMT-04:00) Atlantic Time (Goose Bay)</option>
<option value="America/Glace_Bay">(GMT-04:00) Atlantic Time (Canada)</option>
<option value="America/St_Johns">(GMT-03:30) Newfoundland</option>
<option value="America/Araguaina">(GMT-03:00) UTC-3</option>
<option value="America/Montevideo">(GMT-03:00) Montevideo</option>
<option value="America/Miquelon">(GMT-03:00) Miquelon, St. Pierre</option>
<option value="America/Godthab">(GMT-03:00) Greenland</option>
<option value="America/Argentina/Buenos_Aires">(GMT-03:00) Buenos Aires</option>
<option value="America/Sao_Paulo">(GMT-03:00) Brasilia</option>
<option value="America/Noronha">(GMT-02:00) Mid-Atlantic</option>
<option value="Atlantic/Cape_Verde">(GMT-01:00) Cape Verde Is.</option>
<option value="Atlantic/Azores">(GMT-01:00) Azores</option>
<option value="Europe/Belfast">(GMT) Greenwich Mean Time : Belfast</option>
<option value="Europe/Dublin">(GMT) Greenwich Mean Time : Dublin</option>
<option value="Europe/Lisbon">(GMT) Greenwich Mean Time : Lisbon</option>
<option value="Europe/London">(GMT) Greenwich Mean Time : London</option>
<option value="Africa/Abidjan">(GMT) Monrovia, Reykjavik</option>
<option value="Europe/Amsterdam">(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna</option>
<option value="Europe/Belgrade">(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague</option>
<option value="Europe/Brussels">(GMT+01:00) Brussels, Copenhagen, Madrid, Paris</option>
<option value="Africa/Algiers">(GMT+01:00) West Central Africa</option>
<option value="Africa/Windhoek">(GMT+01:00) Windhoek</option>
<option value="Asia/Beirut">(GMT+02:00) Beirut</option>
<option value="Africa/Cairo" selected="selected">(GMT+2:00) Rwanda , Kigali</option>
<option value="Asia/Gaza">(GMT+02:00) Gaza</option>
<option value="Africa/Blantyre">(GMT+02:00) Harare, Pretoria</option>
<option value="Asia/Jerusalem">(GMT+02:00) Jerusalem</option>
<option value="Europe/Minsk">(GMT+02:00) Minsk</option>
<option value="Asia/Damascus">(GMT+02:00) Syria</option>
<option value="Europe/Moscow">(GMT+03:00) Moscow, St. Petersburg, Volgograd</option>
<option value="Africa/Addis_Ababa">(GMT+03:00) Nairobi</option>
<option value="Asia/Tehran">(GMT+03:30) Tehran</option>
<option value="Asia/Dubai">(GMT+04:00) Abu Dhabi, Muscat</option>
<option value="Asia/Yerevan">(GMT+04:00) Yerevan</option>
<option value="Asia/Kabul">(GMT+04:30) Kabul</option>
<option value="Asia/Yekaterinburg">(GMT+05:00) Ekaterinburg</option>
<option value="Asia/Tashkent">(GMT+05:00) Tashkent</option>
<option value="Asia/Kolkata">(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi</option>
<option value="Asia/Katmandu">(GMT+05:45) Kathmandu</option>
<option value="Asia/Dhaka">(GMT+06:00) Astana, Dhaka</option>
<option value="Asia/Novosibirsk">(GMT+06:00) Novosibirsk</option>
<option value="Asia/Rangoon">(GMT+06:30) Yangon (Rangoon)</option>
<option value="Asia/Bangkok">(GMT+07:00) Bangkok, Hanoi, Jakarta</option>
<option value="Asia/Krasnoyarsk">(GMT+07:00) Krasnoyarsk</option>
<option value="Asia/Hong_Kong">(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi</option>
<option value="Asia/Irkutsk">(GMT+08:00) Irkutsk, Ulaan Bataar</option>
<option value="Australia/Perth">(GMT+08:00) Perth</option>
<option value="Australia/Eucla">(GMT+08:45) Eucla</option>
<option value="Asia/Tokyo">(GMT+09:00) Osaka, Sapporo, Tokyo</option>
<option value="Asia/Seoul">(GMT+09:00) Seoul</option>
<option value="Asia/Yakutsk">(GMT+09:00) Yakutsk</option>
<option value="Australia/Adelaide">(GMT+09:30) Adelaide</option>
<option value="Australia/Darwin">(GMT+09:30) Darwin</option>
<option value="Australia/Brisbane">(GMT+10:00) Brisbane</option>
<option value="Australia/Hobart">(GMT+10:00) Hobart</option>
<option value="Asia/Vladivostok">(GMT+10:00) Vladivostok</option>
<option value="Australia/Lord_Howe">(GMT+10:30) Lord Howe Island</option>
<option value="Etc/GMT-11">(GMT+11:00) Solomon Is., New Caledonia</option>
<option value="Asia/Magadan">(GMT+11:00) Magadan</option>
<option value="Pacific/Norfolk">(GMT+11:30) Norfolk Island</option>
<option value="Asia/Anadyr">(GMT+12:00) Anadyr, Kamchatka</option>
<option value="Pacific/Auckland">(GMT+12:00) Auckland, Wellington</option>
<option value="Etc/GMT-12">(GMT+12:00) Fiji, Kamchatka, Marshall Is.</option>
<option value="Pacific/Chatham">(GMT+12:45) Chatham Islands</option>
<option value="Pacific/Tongatapu">(GMT+13:00) Nuku'alofa</option>
<option value="Pacific/Kiritimati">(GMT+14:00) Kiritimati</option>
</select>	</div>
</div>

						<input type="submit" value="Install" id="submit" class="btn btn-small btn-success" />
					    </div>
						<ul class="pager wizard">
							<li class="previous"><a href="#" class="btn btn-small btn-inverse">Previous</a></li>
						  	<li class="next"><a href="#" class="btn btn-small btn-inverse">Next</a></li>
						</ul>
					</div>	
      <?php else: ?>
      <p class="error">Please make the application/config/database.php file writable. <strong>Example</strong>:<br /><br /><code>chmod 777 application/config/database.php</code></p>
	  <?php endif; ?>
					 </form>
				</div>
			     
				
			</section>
<div class="navbar navbar-inverse">
					  <div class="navbar-inner ">
					    <div class="container">
					<ul>
				<li><a href="#tab1" data-toggle="tab">
				<img src="../images/company_logo/logo.png" width="90px">
						Installation Wizard
						</a>
						</li>
				</ul>
				</div>
				</div>
				</div>
				</form>
				</section>
			</div>
 		</div>
	</div>
    <script src="assets/js/jquery-latest.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
	<script src="assets/js/jquery.bootstrap.wizard.js"></script>
	<script src="assets/js/prettify.js"></script>
	<script>
	$(document).ready(function() 
	{
	  	$('#rootwizard').bootstrapWizard({onTabShow: function(tab, navigation, index) {
			var $total = navigation.find('li').length;
			var $current = index+1;
			var $percent = ($current/$total) * 100;
			$('#rootwizard').find('.bar').css({width:$percent+'%'});
		}});	
		window.prettyPrint && prettyPrint()
	});	


	</script>
  </body>
</html>