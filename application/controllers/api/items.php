<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package		CodeIgniter
 * @subpackage	Rest Server
 * @category	Controller
 * @author		Phil Sturgeon
 * @link		http://philsturgeon.co.uk/code/
*/

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class Items extends REST_Controller
{
	function item_get()
    {
        if(!$this->get('id'))
        {
        	$this->response(NULL, 400);
        }

        $item=$this->Item->get_multiple_info($this->get('id'));
        // $user = $this->some_model->getSomething( $this->get('id') );
    	
		
    	$user = @$users[$this->get('id')];
    	
        if($item)
        {
            $this->response($item->row_array(), 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'User could not be found'), 404);
        }
    }
    
    function item_post()
    {
        //$this->some_model->updateUser( $this->get('id') );
        $message = array('id' => $this->get('id'), 'name' => $this->post('name'), 'email' => $this->post('email'), 'message' => 'ADDED!');
        
        $this->response($message, 200); // 200 being the HTTP response code
    }
    
    function item_delete()
    {
    	//$this->some_model->deletesomething( $this->get('id') );
        $message = array('id' => $this->get('id'), 'message' => 'DELETED!');
        
        $this->response($message, 200); // 200 being the HTTP response code
    }
    
    function items_get()
    {
    	//loading model
    
    	$items_suggestion=$this->Item->get_item_search_suggestions($this->get('id'),$limit=25,$return_array=true);
        
        
        if($items_suggestion)
        {
            $this->response($items_suggestion, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any entry!'), 404);
        }
    }

  
	public function send_post()
	{
		var_dump($this->request->body);
	}


	public function send_put()
	{
		var_dump($this->put('foo'));
	}
}