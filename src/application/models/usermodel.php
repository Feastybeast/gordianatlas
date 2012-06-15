<?php
/*
 * Created on Jun 14, 2012
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
class UserModel extends CI_Model 
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

	/*
	 * Getters & Setters
	 */
	public function getEmail()
	{
		return $this->email;
	}
	
	public function isAdmin()
	{
		return $this->isAdmin;
	}

	public function getPseudonym()
	{
		return $this->pseudonym;
	}
	
	public function getGroups() 
	{
		return $this->memberOfGroups;
	}
	
	public function getId()
	{
		return $this->userId;
	}
	
	public function read($Id)
	{
		$query = $this->db->get_where('User', array('IdUser' => $Id));

		if ($query->result())
		{
		
		}
	}
	
	public function write()
	{
		if ($this->userId == 0)
		{
			$result = insertUser();
		}
		else
		{
			$result = updateUser();
		}
		
		return $result;
	}
}
?>
