<?php
if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

/**
 * Gordian Atlas Timeline management model.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 3
 * @license GPL 3
 */
class Gordian_timeline_model extends CI_Model
{
	/**
	 * Default Constructor.
	 */
	public function __construct()
	{
		parent::__construct();		
	}
	
	/**
	 * Assigns a previously unassociated timeline to a group.
	 * 
	 * @param numeric ID of timeline to associate to a group.
	 * @param numeric ID of group to associate timeline to.
	 * @return boolean True if timeline is successfully assigned to group.
	 */
	public function assign_to($timeline_id, $group_id)
	{
		// Add the Timeline to the Group
		$query = 'INSERT INTO GroupHasTimeline ' .
			'(Group_IdGroup, Timeline_TimelineId) VALUES (?,?)';
				
		$this->db->query($query, array($group_id, $timeline_id));
		
		return ($this->db->affected_rows() == 1);
	}
	
	/**
	 * Creates a timeline and associated view, then gives it to the indicated group.
	 * 
	 * Note there are MANY steps going on throughout this action.
	 * 	1) If the timeline doesn't exist ...
	 * 	2) Add it into the timeline.
	 */
	public function create($title, $description)
	{
		if (!$this->exists($title))
		{
			$query = 'INSERT INTO `Timeline` (Title, Content) VALUES (?,?)';
			$res = $this->db->query($query, array($title, $description));
			
			if ($this->db->affected_rows() == 1) 
			{
				// This may not be perfectly safe for concurrency ...
				return $this->db->insert_id();
			}
		}

		return FALSE;		
	}
	
	public function deactivate($timeline_id)
	{
		
	}

	/**
	 * Locates essential information about the provided timeline.
	 * 
	 * @param mixed Either a numeric ID or Title string of the timeline.
	 * @return mixed Object containing timeline data or FALSE if not found.
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
				$qry = 'SELECT IdTimeline, Title, Content FROM `Timeline` WHERE IdTimeline = ?';
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
	
	/**
	 * Verifies that a timeline provided a title or ID exists.
	 * 
	 * @param mixed Either a numeric ID or Title string of the timeline.
	 * @return boolean TRUE if the timeline identified exists.
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
}