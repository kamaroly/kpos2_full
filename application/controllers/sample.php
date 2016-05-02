<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sample extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        //$this->output->enable_profiler(TRUE);
    }

    function index()
    {
   
$file = 'application';
$newfile = 'hahahahah';
$this->stream_copy($file, $newfile);

    }
   /**
    * @author Kamaro Lambert
    * @name   copy_directory
    * @method to copy entire directory with php
    * @param string $source
    * @param string $destination
    * @license MIT
    */
    function copy_directory($source,$destination) 
    {
    	$dir = opendir($src);
    	@mkdir($dst);
    	while(false !== ( $file = readdir($dir)) ) {
    		if (( $file != '.' ) && ( $file != '..' )) {
    			if ( is_dir($src . '/' . $file) ) {
    				$this->copy_directory($src . '/' . $file,$dst . '/' . $file);
    			}
    			else 
    			{
    				copy($src . '/' . $file,$dst . '/' . $file);
    			}
    		}
    	}
    	closedir($dir);
    }
    function stream_copy($src, $dest)
    {
    	$fsrc = fopen($src,'r');
    	$fdest = fopen($dest,'w+');
    	$len = stream_copy_to_stream($fsrc,$fdest);
    	fclose($fsrc);
    	fclose($fdest);
    	return $len;
    }
    
    function test_base()
    {
    	$this->load->library('Base_convert_lib');
    	
    	echo $this->Base_convert_lib->strToHex('kamaro');
    	echo '<br/>';
    	echo $this->base_convert_lib->hexToStr('kamaro');
    	
    }
}

/* End of file sample.php */
/* Location: ./application/controllers/admin/sample.php */
