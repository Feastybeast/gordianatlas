<?php
if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

/**
 * The Gordian View Library, used to interact with Gordian view objects.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 3
 * @license GPL 3
 */
class Gordian_view
{
	// CodeIgniter Reference.
	private $CI;

	/**
	 * Default constructor
	 */
	public function __construct()
	{
		// Refer to the CodeIgniter engine.
		$this->CI =& get_instance();
		// Load it's underlying model.
		$this->CI->load->model('Gordian_view_model');
	}
	
	/**
	 * Adds a timeline to an existing view.
	 * 
	 * @param numeric The ID of the view to add to.
	 * @param numeric The timeline to add to the view.
	 * @return boolean true if successfully added.
	 */
	public function assign_to($view_id, $timeline_id)
	{
		// Load the timeline management library.
		$this->CI->load->library('Gordian_timeline');
		
		$timeline_exists = $this->CI->Gordian_view_model->exists($timeline_id);
		
		$view_exists = $this->exists($view_id);
		
		if ($timeline_exists && $view_exists)
		{
			return $this->CI->Gordian_view_model->assign_to($view_id, $timeline_id);
		}
		
		return FALSE;
	}
	
	/**
	 * Creates a new view based on an existing timeline.
	 * 
	 * Generally called following the creation of a new timeline.
	 * 
	 * @param string The title of the view to create.
	 * @param string The description of the view to create.
	 * @return mixed The ID of the newly created view, or FALSE.
	 */
	public function create($owning_group, $title, $description)
	{
		if (!$this->exists($title))
		{
			return $this->CI->Gordian_view_model->create($owning_group, $title, $description);
		}

		return FALSE;
	}
			
	/**
	 * Verifies if a view exists or not.
	 * 
	 * @param mixed Either a name or ID of a view to locate.
	 * @return boolean TRUE if the view was located.
	 */
	public function exists()
	{
		if (func_num_args() != 1)
		{
			$this->CI->load->lang('gordian_exceptions');
			$ex = $this->CI->lang->line('gordian_exceptions_illegal_arg');
			throw new Exception($ex);			
		}
		else
		{
			$arg = func_get_arg(0);
			return $this->CI->Gordian_view_model->exists($arg);
		}
		
		return FALSE;
	}
	
	/**
	 * Finds information about a given view.
	 * 
	 * @param mixed Either a name or ID of a view to locate.
	 * @return object The critical data of the view.
	 */
	public function find()
	{
		if (func_num_args() != 1)
		{
			$this->CI->load->lang('gordian_exceptions');
			$ex = $this->CI->lang->line('gordian_exceptions_illegal_arg');
			throw new Exception($ex);			
		}
		else
		{
			$arg = func_get_arg(0);
			return $this->CI->Gordian_view_model->find($arg);
		}
		
		return FALSE;
	}
}
?>
