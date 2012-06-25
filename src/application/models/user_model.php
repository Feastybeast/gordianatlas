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
	function __construct()
	{
		parent::__construct();
	}

	function AddUser($data = array())
	{
		$inboundData = array(
			'Email' => $data['Email'],
			'Nickname' => $data['Nickname'],
			'CryptoPass' => hash("sha256", $data['Password']),
			'CryptoLen' => strlen($data['Password'])
		)
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
