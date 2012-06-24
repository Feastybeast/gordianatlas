<?php
/**
 * This controller is responsible for user management for the Gordian Atlas.
 * 
 * Tasks include registration, login / logout behaviors and so forth.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 2
 * @license GPL 3
 */

if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

class User extends CI_Controller 
{
	public function view()
	{
		$this->load->model('User_model');
		$page_data = array();		
		$this->load->view('user/view', $page_data);
	}
	
	/**
	 * 
	 */
	public function register()
	{
		$this->load->view('user/register');	
	}
	
	/**
	 * 
	 */
	public function validate($ruleset)
	{
		echo "Something";
	}
	
	public function register_process()
	{
		echo "do something else";
	}
}
?>