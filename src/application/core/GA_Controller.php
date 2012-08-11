<?php
if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

/**
 * The success screen for registering a new account.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 4
 * @license GPL 3
 */
class GA_Controller extends CI_Controller
{
	// A data structure to save post queries against.
	private $post_vars;
	// A data structure to save GET queries against.
	
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Returns the record Id as indicated by the current URL.
	 */
	public function record_id()
	{
		$data_array = explode('/', uri_string());
	 	return (count($data_array) >= 3) ? $data_array[2] : FALSE;
	}
	
	/**
	 * Indicates the record type presently being acted upon.
	 * 
	 * @return string The text representation of the record being manipulated.
	 */
	public function record_type()
	{
		$data_array = explode('/', uri_string());
	 	return $data_array[0];
	}
	
	public function get_vars()
	{
		if (!$this->get_vars)
		{
			$this->get_vars = array();

			foreach($_GET as $k => $v)
			{
				$this->get_vars[strtolower($k)] = $this->input->get($k);
			}			
		}
		
		return $this->get_vars;
	}

	public function post_vars()
	{
		if (!$this->post_vars)
		{
			$this->post_vars = array();
			
			foreach($_POST as $k => $v)
			{
				$this->post_vars[strtolower($k)] = $this->input->post($k);
			}	
		}		
		
		return $this->post_vars;
	}
}