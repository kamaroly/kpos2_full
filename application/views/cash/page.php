<?php $this->load->view('partial/header');?>
<link rel='stylesheet' href='<?php echo base_url();?>/css/default.css' type='text/css'>
       <script src="<?php echo base_url()?>js/jquery-1.7.1.min.js"></script>
      
      <link href="<?php echo base_url()?>css/select2.css" rel="stylesheet"/>
<script src="<?php echo base_url()?>js/select2.js"></script>
<head>
    <link href="select2.css" rel="stylesheet"/>
    <script src="select2.js"></script>
    <script>
        $(document).ready(function() { $("#tto").select2(); });
        $(document).ready(function() { $("#tfrom").select2(); });
        $(document).ready(function() { $("#select2").select2(); });
        
    </script>
</head>

<table>
<tr>
	<td class="leftPanel"><?php   $this->load->view('cash/left_menu');?></td>
	<td class="mainPanelScroll">
		<table class="default">
			<tr>
				<td class="headerLeft">
					<?=$page_title?>
				</td>
				<td class="headerRight">
					<?=$this->today_date?>
				</td>
			</tr>
		</table>
		<br>
		<table class="default">
			<tr>
				<td>
					<?php   echo $body;?>
				</td>
			</tr>
		</table>
	</td>
</tr>
</table>
<hr>
<?php $this->load->view('partial/footer');?>