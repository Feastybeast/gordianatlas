<?php
if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

/**
 * The Gordian Atlas view management model.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 3
 * @license GPL 3
 */
class Gordian_view_model extends CI_Model 
{
	/**
	 * Default Constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Assigns a given timeline to the view in question.
	 * @param numeric The view to be added to.
	 * @param numeric The timeline to add to the view.
	 * @return boolean If the view was successfully assigned to.
	 */
	public function assign_to($view_id, $timeline_id)
	{
		// Add the Timeline to the Group
		$query = 'INSERT INTO ViewHasTimeline ' .
			'(View_IdView, Timeline_IdTimeline) VALUES (?,?)';
				
		$this->db->query($query, array($view_id, $timeline_id));
		
		if ($this->db->affected_rows() == 1) 
		{
			return TRUE;
		}
		
		return FALSE;		
	}
	
	/**
	 * Creates a view for a given group with the provided title and description.
	 * 
	 * @param numeric The Id of the Group that will own the view.
	 * @param string The title of the view - Likely the Title of it's singular timeline.
	 * @param string The description of the view, likely that of it's singular timeline.
	 */
	public function create($owning_group, $title, $description)
	{
		if (!$this->exists($title))
		{
			$query = 'INSERT INTO `View` (Group_IdGroup, Title, Content) VALUES (?,?,?)';
			$res = $this->db->query($query, array($owning_group, $title, $description));
			
			if ($this->db->affected_rows() == 1) 
			{
				$inserted = $this->find($title);
				
				return $inserted->Id;
			}
		}

		return FALSE;			
	}
	
	/**
	 * Verifies that a view indicated by a title or ID exists.
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
	
	/**
	 * Locates a view either via a provided title or Id.
	 * @param mixed Either a string title or an Id of a view.
	 * @return mixed FALSE if not located, otherwise a standard object.
	 */
	public function find()
	{
		if (func_num_args() != 1)
		{
			return FALSE;
		}
		else 
		{
			// SELECT statement ...
			$predicate = func_get_arg(0);

			
			if (is_numeric($predicate))
			{
				$query = 'SELECT IdView AS Id, Group_IdGroup AS Group_Id, Title, Content'
						. ' FROM View '
						. 'WHERE IdView = ?';

				$res = $this->db->query($query, array($predicate));
			}
			else
			{
				$query = 'SELECT IdView AS Id, Group_IdGroup AS Group_Id, Title, Content'
						. ' FROM View '
						. 'WHERE Title = ?';

				$res = $this->db->query($query, array($predicate));
			}
			


			if ($res->num_rows() > 0)
			{
				return $res->row();	
			}
		}
	}
	
	/**
	 * Returns all Views owned by the indicated group Id.
	 * @param $group_id The numeric ID of the group in question.
	 * @return mixed FALSE if not located, or an array of objects.
	 */
	public function owned_by($group_id)
	{
		// TODO: NYI.	
	}
}