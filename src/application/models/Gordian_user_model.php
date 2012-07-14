<?php
if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

/**
 * The Gordian_user_model is designed to interact with user accounts 
 * as data objects rather than for purposes of authentication and user
 * validation.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 3
 * @license GPL 3
 */
class Gordian_user_model extends CI_Model
{
	/**
	 * Default Constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		// Note: Depends on Session Library, loaded from autoload config.
	}

	/**
	 * Polls for information about the current user using session data.
	 */
	public function current()
	{
		return $this->find($this->session->userdata('gordian_id'));
	}
	
	/**
	 * Locates essential data for the provided user Id.
	 * @param $user_id the Id of the user to look for.
	 * @return mixed FALSE if not found, an object containing data otherwise.
	 */
	public function find($user_id)
	{
		$query = $this->db->select('IdUser AS id, Email, Nickname, Created, IsAdministrator')->get_where('User', array('IdUser' => $user_id));

	 	if ($query->num_rows() != 1)
	 	{
	 		return FALSE;
	 	}
	 	else
	 	{
	 		return $row = $query->result();
	 	}
	}
}