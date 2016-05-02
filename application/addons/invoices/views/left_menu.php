<style>
<!--

-->
</style>
<?php  
$cur1 = $this->uri->segment(1);
$cur2 = $this->uri->segment(2);

//$menu['general'][] = array('title' => 'Home', 'item' => 'index');
//$menu['general'][] = array('title' => 'Credit', 'item' => 'credit');


    $menu['invoices'][] = array('title'   => $this->lang->line('create_invoice').'/'.$this->lang->line('create_quote'), 'item' => 'sales');
	$menu['invoices'][] = array('title'   => $this->lang->line('view_invoices'), 'item' => 'index');
	$menu['invoices'][] = array('title'   => $this->lang->line('view_recurring_invoices'), 'item' => 'retrieveInvoices/recurring');
	$menu['invoices'][] = array('title' =>  $this->lang->line('view_quotes'), 'item' => 'quotations');
	
	

/**
	$menu['auth'][] = array('title' => 'Login', 'item' => 'login');
	$menu['auth'][] = array('title' => 'Register', 'item' => 'register');
	$menu['auth'][] = array('title' => 'Forgotten Password', 'item' => 'forgotten_password');
**/
?>

<table class="sideMenu">
<tr>
	<td class="sideNavHeading">MENU</td>
</tr>
<tr>
	<td class="sideNav">&nbsp;</td>
</tr>


<?php   foreach ($menu as $k => $v) : ?>
<tr>
<td class="sideNavSubHeader"><span><h1><?=ucfirst($k);?></h1></span></td>
</tr>
	<?php   foreach ($v as $k1 => $v1) : ?>
		<tr>
			<?php   $active = ($cur1 == $k && $cur2 == $v1['item']) ? 1 : 0; ?>
			<td class="<?= $active ? 'sideNavActive' : 'sideNav';?>"><a href="<?php echo site_url($k.'/'.$v1['item']);?>" class="sideNavLink"><?=$v1['title'];?></a></td>
		</tr>
	<?php   endforeach; ?>
<tr>
	<td class="sideNav">&nbsp;</td>
</tr>

<?php   endforeach; ?>

<tr>
	<td class="sideNav">&nbsp;</td>
</tr>

</table>

