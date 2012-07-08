<?php
/**
 * The success screen for registering a new account.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 3
 * @license GPL 3
 */

if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

class Gordian_auth
{
	// Reference to CodeIgniter library.`
	private $CI; 
	
	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->lang->load('gordian_auth');
		$this->CI->load->model('gordian_auth_model');
	}
	
	public function is_logged_in()
	{
		return $this->CI->gordian_auth_model->is_logged_in();
	}
	
	/**
	 * Attempts to login a user into the Gordian Atlas using the provided details.
	 * 
	 * Automatically forces a failure state in the event a user is already logged in.
	 * 
	 * @param $username The username (email address) provided to login.
	 * @param $password The password completing the tuple provided to the login.
	 * @return boolean If the login request was approved by the model.
	 */
	public function login($email, $password)
	{
		if ($this->is_logged_in())
		{
			return false;
		}
		
		$result = $this->CI->gordian_auth_model->login($email, $password);

		return result;
	}
	
	/**
	 * Logs a user out of the Gordian Atlas.
	 */
	public function logout()
	{
		$this->CI->gordian_auth_model->logout();
		redirect('/', 'refresh');
	}
	
	public function register($email, $password, $nickname)
	{
		$nickname = empty($nickname) ? "" : $nickname;
		
		// Register the new user account.
		$user_id = $this->CI->gordian_auth_model->register($email, $password, $nickname);
		
		if ($user_id != FALSE)
		{
			// 	Associate the user to default groups.
			// $res_defaults = $this->CI->gordian_groups_model->set_defaults_for($user_id);
			
			$res_defaults = true;
			
			if (!$res_defaults)
			{
				$groups_notice = $this->lang->line('gordian_auth_register_defaults_failed');
				$this->CI->session->set_flashdata('message', $groups_notice);
			}
		}		
	}
}
