<?php
function do_upload()
{
	$CI =& get_instance();
	
	$config['upload_path'] = './images/company_logo/';
	$config['allowed_types'] = 'gif|jpg|png';
	$config['max_size']	= '100';
	$config['max_width']  = '1024';
	$config['max_height']  = '768';
	$config['file_name']='logo';
	$config['overwrite']=TRUE;

	$this->$CI ->load->library('upload', $config);

	if ( ! $this->$CI ->upload->do_upload())
	{
	
		Return	$error = array('error' => $this->$CI ->upload->display_errors());
			
	}
	else
	{
		$data = array('upload_data' => $this->$CI ->upload->data());
	
		return	$data['upload_data']['file_name'];
				
	}


}
//location: application/helpers/do_upload_helper.php