
<?php
Class Test_controller extends  CI_controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->load->library('base_convert_lib');
		
		$this->load->library('Cis_sdc_lib');
		
	}
	//012420E53031323E03
	//$var='012420E53031323E03'
	function index($var="0xE5")
	{
     if($this->_open_port('COM8'))
     {
	 //Setting DTR to False
     ser_setDTR(False);
     
     //Waiting for 1 second...
     sleep(1);
     
     //Checking the serial Numbers of the SDC 
       
       $string="1 36 39 -27 5 48 49 51 53 3";
       
       $string_array=explode(' ', $string);
       
       //Dumpling the array on the screen
      // var_dump($string_array);
       
       //Go through each byte and write byte by byte to sdc
       foreach ($string_array as $stri=>$value)
       {
        ser_writebyte("$value\r\n");
       }
     sleep(1);
       
   //Send request to the SDC asking the response 
    $str = ser_read();

    //Displaying the Response
    echo $this->_cleandata($str);
     
     //Closing the response
     ser_close();
     }
     else 
     {
     	echo 'Unable to open the port';
     }
	}
//01 25 24 EE 42 05 30 31 37 3E 03
function journal()
  {

  	 //Opening Port...
	 $this->_open_port('COM8');
    //Setting DTR to False
     ser_setDTR(False);
     
     //Waiting for 1 second...
     sleep(1);
     
     //Checking the serial Numbers of the SDC 

       $string=substr($this->_string_to_hex(), 1);
       
      $string_array=explode(' ', '01 25 24 EE 42 05 30 31 37 3E 03');
       $bytes=" ";
       //write the first bit
       foreach ($string_array as $string_hex=>$value)
       {
       	 $bytes=" ".(('0x'.$value+128) % 256) - 128;
       	 ser_writebyte("$bytes\r\n");
       }
    
   sleep(1);
       
   //Send request to the SDC asking the response 
    $str = ser_read();

    //Displaying the Response
    echo $str;
     
    //Closing the response
    ser_close();
  }
  
  
  function getsignature()
  {
  
	  $this->_open_port();
	  //sending receipt data
	  $this->receipt_to_sdc();
	  
      
	  $sdc_data=explode(',',$this->request_sign());
	 
	  echo 'SDC ID :'.substr($sdc_data[0],4);
	  echo '<BR/>TNumber :'.$sdc_data[1];
	  echo '<BR/><GNumber :'.$sdc_data[2];
	  echo '<BR/>RLabel :'.$sdc_data[3];
	  $date_time=explode(' ',$sdc_data[4]);
	  echo '<BR/>Date  :'.$date_time[0];
	  echo '<BR/>TIME  :'.$date_time[1];
	  echo '<BR/>Receipt Signature :'.$sdc_data[5];
	  $internal=explode('.',$sdc_data[6]);
      echo '<BR/>Internal Data  :'.substr($internal[0], 0,strlen($internal)-12);
	  //ser_close();
    
  }
function request_sign()
{
	 //$string=substr($this->_string_to_hex(), 1);   
      $string_dig="01 26 27 C8 32 36 05 30 31 38 32 03";
      $string_array_dig=explode(' ',$string_dig);
       $bytes_dig=" ";        
       foreach ($string_array_dig as $string_hex_dig=>$value_dig)
       {
       	 $bytes_dig=" ".(('0x'.$value_dig+128) % 256) - 128;
       	 ser_writebyte("$bytes_dig\r\n");
       }

     //Send request to the SDC asking the response 
    $str = ser_read();
    //Displaying the Response
    return $str;
    
}	
function receipt_to_sdc()
  {

  
     $string='01 98 27 C6 '.substr($this->_string_to_hex(), 1).' 05 31 38 30 33 03';   
     //$string="01 98 27 C6 4E 53 41 4C 47 30 31 30 30 30 30 30 33 2C 31 30 30 36 30 30 35 37 30 2C 33 31 2F 31 32 2F 32 30 31 33 20 31 38 3A 32 39 3A 30 2C 32 36 2C 30 2E 30 30 2C 31 38 2E 30 30 2C 30 2E 30 30 2C 30 2E 30 30 2C 30 2E 30 30 2C 38 2E 32 32 2C 30 2E 30 30 2C 30 2E 30 30 2C 30 2E 30 30 2C 31 2E 32 35 2C 30 2E 30 30 2C 30 2E 30 30 2C 31 32 33 34 35 36 37 38 39 05 31 38 30 33 03";
    
      $string_array=explode(' ',$string);
       $bytes=" ";
       
       //write the first bit
       // var_dump($string_array);           
       foreach ($string_array as $string_hex=>$value)
       {
       	 $bytes=" ".(('0x'.$value+128) % 256) - 128;
       	 ser_writebyte("$bytes\r\n");
       }

   //getting the response from SDC
   $str = ser_read();
   echo  $str ;
    //Displaying the Response
    $this->request_sign();
    
    
  }
  
  function hex_to_bytes()
  {
  	   $string=$this->_string_to_hex();
       
       $string_array=explode(' ', $string);
       $bytes=" ";
       foreach ($string_array as $string_hex=>$value)
       {
       	 $bytes.="".(('0x'.$value+128) % 256) - 128;
       }
       echo $bytes;
  }
  
  function _string_to_hex($var=" ")
  {
  	echo $string=$this->receipt_data();
  	//$string='NSTES01012345,100600570,28/12/2013 09:29:37,1,0.00,18.00,0.00,0.00,11.00,12.00,0.00,0.00,0.00,1.83,0.00,0.00';
    
  	return $this->_strTohex($string);
  }
 
  function test()
  {
  	$string='NSTES01012345,100600570,17/07/2013 09:29:37,1,0.00,18.00,0.00,0.00,11.00,12.00,0.00,0.00,0.00,1.83,0.00,0.001679';
    $string_array=explode(',', $string);
    echo count($string_array);
    var_dump($string_array);
  }
  function _strTohex($string)
  {
  	return $this->base_convert_lib->strToHex($string);
  }
  
  function _getsdcid_hex($unclean_string)
  {
  	

  	return substr(preg_replace('/( *)/', '', $this->base_convert_lib->strToHex($unclean_string)), 8,24);
  }
  
  function _cleandata($string)
  {
  	//let's remove space
  	return $this->base_convert_lib->HexToStr($this->_getsdcid_hex($string));
  }
  
    /**
     * @author Kamaro Lambert
     * @method to open a port
     */
  function _open_port($port="COM8")
  {
  	 //Opening Port...
	 ser_open($port, 9600, 8, "None", 1, "None");
   //Check if the port is open 
	 if (ser_isopen() == true )
	 {
	 	//Yes the port is open
       RETURN TRUE;
	 }
	 else
	 {
	 	//No the port is not open
         RETURN FALSE;
	 }
  }
    /**
     * @author Kamaro Lambert
     * @method to close  port
     */
  function _close_port()
  {
  	ser_close();
  }
  
 /**
  * @author Kamaro Lambert
  * Function to get the  string between 01 and 05 of the SDC ...
  * @param string $string
  */ 
function get_between_01_05_array($string)
{
preg_match('/01(.*?)05/',$string, $display);

  $display[1]=ltrim(rtrim($display[1])).' 05';
  
  return explode(' ',$display[1]);
}
  /**
   * @author Kamaro Lambert
   * Method to get the BCC for the message to be sent to SDC ...
   * @param STRING $bcc
   */
 function get_bcc($bcc)
  {
  $bcc_return="";
  if(strlen($bcc)<=3)
  {
     $bcc_return="30";
    for($i=0;$i<strlen($bcc);$i++)
    {
      $bcc_return.=" 3".$bcc[$i];
     
    }
  }
   else
    {
    for($i=0;$i<strlen($bcc);$i++)
    {
      $bcc_return.=" 3".$bcc[$i];
     
    }
  }
 return  $bcc_return;
  }
 /**
  * Author Kamaro Lambert
  * Method to get the checksum of the hex...
  * @param array of hex bytes $hex_array
  */ 
function get_checsum_value($hex_array=array())
{
 
  if(count($hex_array>0))
  {
    $sdc_checksum=0;
    foreach($hex_array as $hex=>$value)
    {
      $sdc_checksum+= hexdec($value);
      
    }
  }
  return $sdc_checksum;
}

 function test_bcc()
 {
 echo	$string = '01 98 27 C6 4E 53 41 4C 47 30 31 30 30 30 30 30 33 2C 31 30 30 36 30 30 35 37 30 2C 33 31 2F 31 32 2F 32 30 31 33 20 31 38 3A 32 39 3A 30 2C 32 36 2C 30 2E 30 30 2C 31 38 2E 30 30 2C 30 2E 30 30 2C 30 2E 30 30 2C 30 2E 30 30 2C 38 2E 32 32 2C 30 2E 30 30 2C 30 2E 30 30 2C 30 2E 30 30 2C 32 33 34 35 36 37 38 39 05 31 38 30 33 03';
 echo '<BR/>';
 $cksum_array=$this->get_between_01_05_array($string);
 //var_dump(array_values($cksum_array));
echo '<BR/>';
 echo dechex($this->string_to_ascii(implode('', $cksum_array)));
 echo '<BR/>';
   // input strings are not case-snsitive
  echo $this->hex2str("48656c6c6f2c20576f726c64210a");
  echo  $this->hex2str("48656C6C6F2C20576F726C64210A");

/* Output:
  Hello, World!
  Hello, World!
*/
 
 }
 
function string_to_ascii($string)
{
    $ascii = NULL;
	
    for ($i = 0; $i < strlen($string); $i++) 
    { 
    	$ascii += ord($string[$i]); 
    }
    
    return($ascii);
}

function to_ascii($string)
{
	$ascii_string = '';
	foreach (str_split($string) as $char) 
	{ 
		$ascii_string .= '&#' . ord($char) . ';'; 
	}
	return $ascii_string;
}


  /* convert a string of hex values to an ascii string */
  function hex2str($hex)
   {
   	$str=0;
    for($i=0;$i<strlen($hex);$i+=2)
    
    $str += ord(chr(hexdec(substr('0x'.$hex,$i,2))));

    return dechex($str);
  }

  
  function receipt_data()
  {
  		$data=array(
  	            'RtypeTTypeMRC'=>'NSTES01012345',
  	            'TIN'          =>'100600570',
  	            'DateTime'     =>'28/12/2013 09:29:37',
  	            'RNum'         =>1,
  	            'TaxRate1'     =>$this->cis_sdc_lib->_sdc_number_format('0.00'),
  	            'TaxRate2'     =>$this->cis_sdc_lib->_sdc_number_format('18.00'),
  	            'TaxRate3'     =>$this->cis_sdc_lib->_sdc_number_format('0.00'),
  	            'TaxRate4'     =>$this->cis_sdc_lib->_sdc_number_format('0.00'),
                'Amount1' 	   =>$this->cis_sdc_lib->_sdc_number_format('11.00'),
  	            'Amount2'      =>$this->cis_sdc_lib->_sdc_number_format('12.00'),
  	            'Amount3'      =>$this->cis_sdc_lib->_sdc_number_format('0.00'),
  	            'Amount4'      =>$this->cis_sdc_lib->_sdc_number_format('0.00'),
  	            'Tax1'         =>$this->cis_sdc_lib->_sdc_number_format('0.00'),
  	            'Tax2'         =>$this->cis_sdc_lib->_sdc_number_format('1.83'),
  	            'Tax3'         =>$this->cis_sdc_lib->_sdc_number_format('0.00'),
  	            'Tax4'         =>$this->cis_sdc_lib->_sdc_number_format('0.00'),
  	            'ClientsTin'   =>null
  	);
  	return implode(',', $data);
  }


}