<?php
if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

/**
 * Group management database access for Gordian Atlas
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 3
 * @license GPL 3
 */
class Gordian_group_model extends CI_Model
{
	/**
	 * Default Constructor. 
	 */
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Adds the user to the group identified as an administrator.
	 * 
	 * @param numeric The group Id to add to.
	 * @param numeric The user to promote to administrator.
	 * @return boolean If they were successfully added.
	 */
	function administrate($group_id, $user_id)
	{
		$query = 'INSERT INTO `UserRoleInGroup` (Group_IdGroup, User_IdUser, Role) VALUES (?,?,?)';
		$res = $this->db->query($query, array($group_id, $user_id, 'OWN'));
		
		if ($this->db->affected_rows() == 1) 
		{
			return TRUE;
		}

		return FALSE;
	}

	/**
	 * Checks to see if a Timeline exists given a Title or Id.
	 * 
	 * @param string Title of the group being created.
	 * @param string The description of the group being created. 
	 * @return boolean True if created successfully.
	 */	
	public function create($title, $description)
	{
		if (!$this->exists($title))
		{
			$query = 'INSERT INTO `Group` (Title, Content) VALUES (?,?)';
			$res = $this->db->query($query, array($title, $description));
			
			if ($this->db->affected_rows() == 1) 
			{
				// This may not be perfectly safe for concurrency ...
				return $this->db->insert_id();
			}
		}

		return FALSE;
	}
	
	/**
	 * Checks to see if a Timeline exists given a Title or Id.
	 * 
	 * @param mixed Either an Id or Title used to locate the group in question.
	 * @return boolean True if the group identified by the argument exists.
	 */
	public function exists()
	{
		if (func_num_args() != 1)
		{
			$this->load->lang('gordian_exceptions');
			$ex = $this->lang->line('gordian_exceptions_illegal_arg');
			throw new Exception($ex);
		}
		else 
		{
			$arg = func_get_arg(0);
			
			if (is_numeric($arg) || is_string($arg))
			{
				return (boolean) $this->find($arg);	
			}			
		}
		
		return FALSE;		
	}
	
	/**
	 * Returns information about a Timeline identified via a Title or Id.
	 * 
	 * @param mixed Either an Id or Title used to locate the group in question.
	 * @return resource The Database result row if located, or FALSE.
	 */
	public function find()
	{
		if (func_num_args() != 1)
		{
			$this->load->lang('gordian_exceptions');
			$ex = $this->lang->line('gordian_exceptions_illegal_arg');
			throw new Exception($ex);
		}
		else 
		{
			$arg = func_get_arg(0); 
						
			if (is_numeric($arg))
			{
				$qry = 'SELECT IdGroup, Title, Content, Status FROM `Group` WHERE IdGroup = ?';
				$res = $this->db->query($qry, array($arg));
			} 
			else if (is_string($arg))
			{				
				$qry = 'SELECT IdGroup, Title, Content, Status FROM `Group` WHERE Title = ?';
				$res = $this->db->query($qry, array($arg));
			}
			else
			{
				$this->load->lang('gordian_exceptions');
				$ex = $this->lang->line('gordian_exceptions_illegal_arg');
				throw new Exception($ex);				
			}
			
			/*
			 * Return the result of the query.
			 * NOTE: 
			 * mySQL seems to have a problem with utf-8 collations and UNIQUE indices
			 * on VARCHAR fields. No major research at this point, but see
			 * <http://bugs.mysql.com/bug.php?id=14990>. Sheesh ...
			 */ 
			return ($res->num_rows() > 0) ? $res->row() : FALSE;
		}	
	}
}