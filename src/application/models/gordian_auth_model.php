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
	
	public function is_logged_in()
	{
		return (boolean) $this->session->userdata('gordian_id');
	}
		
	public function login($email, $password)
	{
		if (!$this->is_logged_in())
		{
			$this->db->select('IdUser')->from('User')->where('Email', $email)->where('Pass', $password)->limit(1);
			$res = $this->db->get();

			if ($res->num_rows == 1)
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
	
	public function logout()
	{
		$logout_notice = $this->lang->line('gordian_auth_logout_flash');
		$this->session->set_flashdata('message', $logout_notice);		
				$this->session->unset_userdata('gordian_id');
	}

	
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
				'Pass' => $password,
				'Salt' => strlen($password),
				'IsAdministrator' => false
			);
			
			$this->db->insert('User', $inbound_data);
			
			$this->db->select('IdUser')->from('User')->where('Email', $email)->limit(1);
			$res = $this->db->get();
		
			return ($res->num_rows == 1) ? true : false;	
		}
	}
	
	/*
	 * Support functions
	 */
	 private function set_error($error_string)
	 {
	 	$this->errors[] = $error_string;
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
}