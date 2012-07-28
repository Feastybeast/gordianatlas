<?php
if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

/**
 * This is the App logic intermediary for controllers and the Auth model.
 * 
 * It contains a number of basic methods directly related to the goal
 * of user authorization.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 3
 * @license GPL 3
 */
class Gordian_auth
{
	// Reference to CodeIgniter library.`
	private $CI; 
	
	/**
	 * Default constructor.
	 * 
	 * Prepares a reference to the CodeIgniter instance for user in 
	 * further methods.
	 */
	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->lang->load('gordian_auth');
		$this->CI->load->model('gordian_auth_model');
	}
	
	/**
	 * Indicates if the current user is logged in.
	 * @return If the user is logged in or not.
	 */
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
		$this->logout();
				
		return $this->CI->gordian_auth_model->login($email, $password);
	}
	
	/**
	 * Logs a user out of the Gordian Atlas.
	 */
	public function logout()
	{
		$this->CI->gordian_auth_model->logout();
	}
	
	/**
	 * Attempts to register a new user account
	 * 
	 * @param $email A valid email address as the common login key.
	 * @param $password The password used to identify the user later on.
	 * @param $nickname An option "human readable" field to display to others.
	 * @return The new user Id, or false if unsuccessful.
	 */
	public function register($email, $password, $nickname)
	{
		$nickname = empty($nickname) ? "" : $nickname;
		
		// Register the new user account.
		$user_id = $this->CI->gordian_auth_model->register($email, $password, $nickname);
		
		if ($user_id != FALSE)
		{
			$this->CI->load->library('gordian_group');
			// 	Associate the user to default groups.
			$res_defaults = $this->CI->gordian_group->add_defaults($user_id);
			
			
			if (!$res_defaults)
			{
				exit("Wombat");
				$groups_notice = $this->lang->line('gordian_auth_register_defaults_failed');
				$this->CI->session->set_flashdata('message', $groups_notice);
			}
		}
		
		return $user_id;		
	}
	
	/**
	 * Updates the site-administrator status of the identified user to $state.
	 * 
	 * @param $user_id The Id of the user account to alter.
	 * @param $state The boolean indication of their state as an administrator.
	 * @return TRUE if update took, FALSE if update was invalid.
	 */
	public function set_admin_rights($user_id, $state)
	{		
		if (!is_numeric($user_id) ||  !is_bool($state))
		{
			return false;
		}
		
		return $this->CI->gordian_auth_model->set_admin_rights($user_id, $state);
	}
	
	/**
	 * Indicates if the identified user is an admin or not.
	 * 
	 * @param $user_id OPTIONAL. Value defaults to current session user id.  
	 */
	public function is_admin()
	{
		return $this->CI->gordian_auth_model->is_admin();
	}
	
	/**
	 * 
	 */
	 public function edit_user($nickname)
	 {
	 	$this->CI->gordian_auth_model->edit_user($nickname);
	 }
}
