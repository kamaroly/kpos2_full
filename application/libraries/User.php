<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
class User{

	var $userid = '';
	var $password = '';

	/**
	 * Constructor
	 *
	 * @access public
	 */
	function User()
	{
		// If the magic __get() or __set() methods are used in a Model references can't be used.
		//$this->_assign_libraries( (method_exists($this, '__get') OR method_exists($this, '__set')) ? FALSE : TRUE );
		
		// We don't want to assign the model object to itself when using the
		// assign_libraries function below so we'll grab the name of the model parent
		//$this->_parent_name = ucfirst(get_class($this));
		
		log_message('debug', "User Class Initialized");
	}

	/**
	 * Assign Libraries
	 *
	 * Creates local references to all currently instantiated objects
	 * so that any syntax that can be legally used in a controller
	 * can be used within models.  
	 *
	 * @access private
	 */	
/*
	function _assign_libraries($use_reference = TRUE)
	{
		$CI =& get_instance();				
		foreach (array_keys(get_object_vars($CI)) as $key)
		{
			if ( ! isset($this->$key) AND $key != $this->_parent_name)
			{			
				// In some cases using references can cause
				// problems so we'll conditionally use them
				if ($use_reference == TRUE)
				{
					// Needed to prevent reference errors with some configurations
					$this->$key = '';
					$this->$key =& $CI->$key;
				}
				else
				{
					$this->$key = $CI->$key;
				}
			}
		}		
	}
*/

	function authenticate ($userid, $password) 
	{
		
	} 

	function permission($group)
	{
		$priv = $this->session->userdata("count_$id");
		if (in_array($priv, $group)) {
			return true;
		}	
	    else{
			redirect('/user/login', 'location');
	    }
	} 
}
// END Model Class
?>