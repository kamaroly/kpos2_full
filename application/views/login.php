
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta name=viewport content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
	<title>KPos2 - Log In</title>

	<meta name="robots" content="noindex, nofollow"/>

	<link rel="stylesheet" href="<?php echo base_url();?>css/workless.css" />
    <link rel="stylesheet" href="<?php echo base_url();?>css/application.css" />
    <link rel="stylesheet" href="<?php echo base_url();?>css/responsive.css" />
    <link rel="stylesheet" href="<?php echo base_url();?>css/animate.css" />
    <script src="<?php echo base_url();?>js/jquery.js"></script>
    <script src="<?php echo base_url();?>js/login.js"></script>
	
	
	</head>

<body id="login-body">

<div id="container" class="login-screen">
	<section id="content">
		<div id="content-body">

			<div class="animated fadeInDown" id="login-logo" class="form_inputs">
				<span id="company_title">
		<?php
$image_properties = array(
		'src' => 'images/company_logo/'.$this->config->item('company_logo'),
		'alt' => $this->config->item('company').'-KPharmacy',
		'class' => 'img-polaroid',
		'width' => '120',
		'height' => '60',
		'title' => 'That was quite a night',
		'rel' => 'lightbox',
);
echo img($image_properties);?>
		</span>
			</div>
			

<form action="<?php echo site_url('login')?>" method="post" accept-charset="utf-8" class="form_login">
<div style="display:none;">
<input type="hidden" name="csrf_hash_name" value="11220be1dea5c9d6662c64a8683c496a" />
</div>				<div class="form_inputs">
					<ul>
						<li>
							<div class="input animated fadeInDown" id="login-un">
							<input type="text" name="username" autocomplete="off"
							placeholder="<?php echo $this->lang->line('login_username'); ?>:"/></div>
						</li>

						<li>
							<div class="input animated fadeInDown"	 id="login-pw">
							 <input type="password" autocomplete="off"
							 name="password" placeholder="<?php echo $this->lang->line('login_password'); ?>"/></div>
						</li>
						<li class="animated fadeInDown" id="login-save">
							<label for="remember-check" id="login-remember">
								<input type="checkbox" name="remember" id="remember-check" value="1" />
								Remember Me							</label>
						</li>
						<li>
						  <label for="remember-check" id="login-remember" class="input animated fadeInDown"> 
						  <a href="forgot_password">Forgot your password?</a>
						  </label>
						</li>
					</ul>
					<div class="animated fadeIn" id="login-action">
						<div class="buttons padding-top" id="login-buttons">
							<button id="login-submit" class="btn" ontouchstart="" type="submit" name="submit" value="Log In">
								<span>Log In</span>
							</button>
						</div>
					</div>
					<!-- </div> -->
				</form>			</div>
		</div>
	</section>
</div>
<footer id="login-footer">
	<div class="wrapper animated fadeInUp" id="login-credits">
		
		 Copyright &copy;  2013-<?php echo DATE('Y');?> Quick Huguka Ltd
		<br><span id="version"><?php echo $this->config->item('application_version'); ?> Kamaro  Lambert</span>
	</div>
</div>
</footer>
</body>
</html>