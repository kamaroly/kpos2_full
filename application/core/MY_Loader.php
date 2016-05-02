<?php
(defined('BASEPATH')) or exit('No direct script access allowed');

/* load the HMVC_Loader class */
require APPPATH . 'third_party/HMVC/Loader.php';

class MY_Loader extends HMVC_Loader {
		/**
 * function to return the available com port
 * @return arrays comm_list
 */
public function avalable_comport_list() 
{ 

        $comm = shell_exec('mode'); 

        if(substr_count($comm,'COM')<1) { 
            $comm_list[0] = 'None'; 
        } else { 

            $conn = explode(' ',$comm); 
            $count = count($conn); 
            for($i=0;$i<$count;$i++) { 
                if(substr_count($conn[$i],'COM')<1) { 
                    $comm_list[$i] = ''; 
                } else { 
                    $comm_list[$i] = str_replace(':','',substr($conn[$i],0,5)).'-'; 
                } 
            } 

        } 

        $comm = implode('',$comm_list); 
        $comm = trim($comm); 
        $comm = trim(str_replace('-',' ',$comm)); 
        $comm_list = explode(' ',$comm); 
        
        $comm_list_dropdown=array();
        foreach ($comm_list as $key => $value) {
        	# code...
        	 $comm_list_dropdown[$value]=$value;
        }

      return $comm_list_dropdown ; 
    } 

    
}