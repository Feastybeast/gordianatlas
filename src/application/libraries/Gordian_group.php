<?php
if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

/**
 * Gordian Groups management library.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 3
 * @license GPL 3
 */
class Gordian_group 
{
	// CodeIgniter Reference.
	private $CI;
	
	/**
	 * Default Constructor.
	 */
	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->model('Gordian_group_model');
	}
	
	/**
	 * Inspects existing DEFAULT groups within the Atlas and adds the user to them.
	 * 
	 * @param numeric The user ID to add to default groups.
	 */
	function add_defaults($user_id)
	{
		return $this->CI->Gordian_group_model->add_defaults($user_id);
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
		return $this->CI->Gordian_group_model->administrate($group_id, $user_id);
	}
	
	/**
	 * Creates a new Group for the Gordian Atlas.
	 * 
	 * @param String The title for the new group
	 * @param String The description of the new group
	 * @return boolean True if the group is successfully created.
	 */
	public function create($title, $description)
	{
		$group_exists = $this->exists($title);
			
		if (!$group_exists)
		{			
			return $this->CI->Gordian_group_model->create($title, $description);
		}
		
		return FALSE;
	}
	
	/**
	 * Checks to see if a Group exists given a title or Id.
	 * 
	 * @param mixed The title string or group Id for the expected Group
	 * @return boolean If the Group in question exists.
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
			return $this->CI->Gordian_group_model->exists($arg);		
		}
		
		return FALSE;
	}
}