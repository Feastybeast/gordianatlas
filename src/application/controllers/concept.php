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
class Concept extends GA_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * 
	 */
	public function add()
	{
		if ($this->input->is_ajax_request() && $this->gordian_auth->is_logged_in())
		{
			exit("This is the add screen.");
		}
	}
	
	public function delete()
	{
		if ($this->current_record() == FALSE)
		{
			return FALSE;
		}		
	}
	
	public function edit()
	{
		if ($this->current_record() == FALSE)
		{
			return FALSE;
		}
	}
	
	public function wiki()
	{
		if ($this->current_record() == FALSE)
		{
			return FALSE;
		}		
	}
}