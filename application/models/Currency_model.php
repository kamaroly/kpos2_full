<?php 
Namespace Model;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Currency MOdle
*
* @author   Kamaro  Lambert
* @access	public

*/
Use \Gas\Core;
Use \Gas\ORM;

Class Currency_model extends ORM
{
	public $table       ="currencies";
	public $primary_key ="curr_id";
	
	function _init()
	{
	    self::$fields = array(
	    		 'curr_id'           =>  ORM::field('auto[30]'),
	    		 'Name'              =>  ORM::field('string',array('required')),
	    		 'Exchange_Rate'     =>  ORM::field('numeric', array('required')),
	    		 'Symbol'            =>  ORM::field('char[6]'),
	    		 'Symbol_Suffix'     =>  ORM::field('char[5]'),
	    		 'Thousand_Separator'=>  ORM::field('char[1]'),
	    		 'Decimal_Separator' =>  ORM::field('char[1]'),
	    		 'Status'            =>  ORM::field('numeric'),
	    		 'Default'           =>  ORM::field('numeric'),
	    		);	
	    $this->ts_fields = array('Date');
	    
	}
}