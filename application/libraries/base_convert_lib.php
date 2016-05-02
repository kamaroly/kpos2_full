<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * LIBRARY TO CONVERT BASES (EX:HEX TO STRING AND VIS-VERSA)
* This file is part of Kpos, a PHP based Point of sale system built for
* SME.
*
* Copyright (c) 2013 Kamaro Lambert.
* http://github.com/kamaroly/kpos
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*
* @package application/libraries
* @author Kamaroly <support@kamaroly.com>
* @copyright 2013 kamaroly.
* @version dev
* @link http://github.com/kamaroly/kpos
*
*/

class Base_convert_lib
{
	//initiating the CodeIgniter instace
	//---------------------------------
	var $CI;
    
  	function __construct()
	{
		$this->CI =& get_instance();
	}

	/**
	 * @Author Kamaro Lambert
	 * @name strToHex()
	 * @example Base_convert_lib->strToHex()
	 * @param  string $string
	 * 
	 * Method to convert string to Hexadecimal
	 */

	public function strToHex($string)
	{
		//initiating variable to return
		$hex = '';
		
		//Go through the entire string and convert one letter by one.
		for ($i=0; $i<strlen($string); $i++)
		{
			$ord = ord($string[$i]);
			$hexCode = dechex($ord);
			$hex .= substr('0'.$hexCode, -2);
		}
		//Return the result and make it upper case
		return strToUpper($hex);
	}

	/**
	 * @Author Kamaro Lambert
	 * @name hexToStr()
	 * @example Base_convert_lib->hexToStr()
	 * @param  string $hex
	 * Method to convert  Hexadecimal to string
	 */
	
	public function hexToStr($hex)
	{
		//Initializing the variable
		$string='';
		//Convert each HEX to string
		for ($i=0; $i < strlen($hex)-1; $i+=2)
		{
			$string .= chr(hexdec($hex[$i].$hex[$i+1]));
		}
		return $string;
	}
	
	
	
}
?>