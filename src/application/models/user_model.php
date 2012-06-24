<?php
/**
 * This model is the primary means to access user data for the application.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 3
 * @license GPL 3
 */

if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}
 
class User_model extends CI_Model 
{
	private $isAdmin = false;
	private $email = "";
	private $pseudonym = "";
	private $memberOfGroups = array();
	private $Id = 0;
	private $moderationState = null;
	
	function __construct()
	{
		parent::__construct();
	}

	function AddUser($options = array())
	{
		
	}
	
	function DeleteUser($options = array())
	{
		
	}
	
	function UpdateUser($options = array())
	{
		
	}
	
	function GetUsers($options = array())
	{
		
	}
}
?>
