<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Dropdown extends CI_Controller {
 
	function __construct()
	{
		parent::__construct();
	}
 
	function index()
	{
		$this->load->helper('form');
		//indexed array
		$options1=array(1,2,3,4,5);
		//assoc array
		$options2=array('op1'=>'opton1','op2'=>'opton2','op3'=>'opton3');
		//mixed array contains indexed and assoc array elements
		$options3=array('op1'=>'opton1','op2'=>'opton2',1,4);
		//assoc array
		$options4=array('op1'=>'opton1','op2'=>'opton2','op3'=>'opton3');
		//add one more element
		$options4['op4']='option4';
		//creates dynamic indexed array from 3 to 5 values
		$options=range(3,5);
 
		//array elements which are selected by default , these are keys of the options array
		$selected_opt=array('op1','op3');
		$selected_opt2=array(1,2);
		//adding extra HTML attributes to form drop down with a string
		$extra_attributes1='id="dropy" style="color:blue;"';
 
		echo form_dropdown('drop1', $options);
		echo form_dropdown('drop2', $options1);
		echo '<br/><br/>';
		echo form_dropdown('drop3', $options2);
		echo '<br/><br/>';
		echo form_dropdown('drop4', $options3);
		echo '<br/><br/>';
		echo form_dropdown('drop5', $options4);
		echo '<br/><br/>';
		echo form_dropdown('drop6', $options2,'op3');
		echo '<br/><br/>';
		echo form_dropdown('drop7', $options2,$selected_opt);
		echo '<br/><br/>';
		echo form_dropdown('drop8', $options2,$selected_opt,$extra_attributes1);
		echo '<br/><br/>';
		echo form_dropdown('drop9', $options2,$selected_opt,'id="dropu" onclick="alert("you changed options!!");"');
		echo '<br/><br/>';
		echo form_dropdown('drop9', $options2,$selected_opt,'id="dropu" size=2');
		echo '<br/><br/>';
 
 
 
 
 
	}
}
 
/* End of file select.php */
/* Location: ./application/controllers/select.php */
?>