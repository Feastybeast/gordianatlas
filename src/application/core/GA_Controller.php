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
	public function current_record()
	{
		$data_array = explode('/', uri_string());
	 	return (count($data_array) >= 3) ? $data_array[2]: FALSE;
	}
	
	public function get_vars()
	{
		if (!$this->get_vars)
		{
			$this->get_vars = array();

			foreach($_GET as $k => $v)
			{
				$ret[strtolower($k)] = $this->input->get($k);
			}			
		}
		
		return $this->post_vars;
	}

	public function post_vars()
	{
		if (!$this->post_vars)
		{
			$this->post_vars = array();

			foreach($_POST as $k => $v)
			{
				$ret[strtolower($k)] = $this->input->post($k);
			}			
		}
		
		return $this->post_vars;
	}
}