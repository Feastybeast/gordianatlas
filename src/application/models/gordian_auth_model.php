<?php
/**
 * Database level interactions for the Gordian Authorization model.
 * 
 * This class is heavily patterned after the IonAuth Model, developed
 * by Ben Edmunds, documentation and contact for both Ben and 
 * IonAuth can be located at <http://benedmunds.com/ion_auth/>.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 3
 * @license GPL 3
 */

if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

class Gordian_auth_model extends CI_Model
{
	/**
	 * Default Constructor.
	 * 
	 * Loads the Cookie, Date and Gordian_auth stringpack.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('cookie');
		$this->load->helper('date');
		$this->lang->load('gordian_auth');
	}
	
	public function ban_account()
	{
		//TODO: Implement		
	}
	public function deactivate_account()
	{
		//TODO: Implement		
	}
	
	public function forgot_password()
	{
		//TODO: Implement		
	}
	
	public function forgot_password_verify()
	{
		//TODO: Implement
	}
	
	/**
	 * Verifies if the current user is logged in.
	 * @return boolean The current user's login state.
	 */
	public function is_logged_in()
	{
		return (boolean) $this->session->userdata('gordian_id');
	}

	/**
	 * Logs a user into the application, given a valid login / password combo.
	 * 
	 * Note: A user MUST be logged out or the login() method will silently fail.
	 * 
	 * @param $email The email address of the logging in user.
	 * @param $password The password of the logging in user.
	 * @return boolean If the user successfully logged in or not.
	 */		
	public function login($email, $password)
	{
		if (!$this->is_logged_in())
		{
			$qry_login = " SELECT IdUser FROM User ";
			$qry_login .= "WHERE Email = '{$email}' ";
			$qry_login .= "AND Pass = SHA2('{$password}', 256) ";
			$qry_login .= "LIMIT 1";

			$res = $this->db->query($qry_login);

			if ($res->num_rows() == 1)
			{
				$user_id = $res->row()->IdUser;
				
				set_cookie('gordian_id', $user_id);
				$this->session->set_userdata('gordian_id', $user_id);				
				$login_notice = $this->lang->line('gordian_auth_login_successful');
				$this->session->set_flashdata('message', $login_notice);		
				
				return TRUE;
			}
		}			

		$login_notice = $this->lang->line('gordian_auth_login_failed');
		$this->session->set_flashdata('message', $login_notice);		

		return FALSE;
	}
	
	/**
	 * Logs a user out of the application.
	 */
	public function logout()
	{
		$this->session->unset_userdata('gordian_id');
	}

	
	/**
	 * Registers a new email / password combination into the application's database.
	 * 
	 * @param $email The email address of the user registering for the application.
	 * @param $password The password of the user registering for the application.
	 * @param $nickname The optionally provided nickname of the registering user.
	 * @return boolean If the registration successfully took or not.
	 */
	public function register($email, $password, $nickname)
	{
		$this->db->select('IdUser')->from('User')->where('Email', $email)->limit(1);
		$res = $this->db->get();
		
		if($res->num_rows != 0)
		{
			return FALSE;
		}
		else
		{
			$inbound_data = array(
				'Email' => $email,
				'Nickname' => $nickname,
				'Pass' => 'SHA2("'.$password.'", 256)',
				'Salt' => strlen($password),
				'IsAdministrator' => false
			);
			
			$this->db->insert('User', $inbound_data);
			
			$this->db->select('IdUser')->from('User')->where('Email', $email)->limit(1);
			$res = $this->db->get()->row();
			
			return $res->IdUser;	
		}
	}
	
	/**
	 * Toggles the provided User Id's admin rights on the Atlas.
	 * 
	 * @param $user_id The Id of the user to alter.
	 * @param $state A boolean indication of the user's site admin rights.
	 */
	public function set_admin_rights($user_id, $state)
	{
		$field_updates = array(
			'IsAdministrator' => $state
		);
		
		$this->db->where('IdUser', $user_id);
		$this->db->update('User', $field_updates);
		
		return TRUE;
	}
	
	/*
	 * Support functions
	 */
	 
	 private function reset_errors()
	 {
	 	$this->errors = array();
	 }
	 
	 private function get_errors()
	 {
	 	$output = '';

	 	foreach($this->errors as $error) 
	 	{
	 		$output .= ($this->lang->line($error)) ? $this->lang->line($error) : '##' . $error . '##';
	 	}
	 	
	 	return $output;
	 }

	 /**
	  * Logs a new error condition into the 
	  */
	 private function set_error($error_string)
	 {
	 	$this->errors[] = $error_string;
	 }
}