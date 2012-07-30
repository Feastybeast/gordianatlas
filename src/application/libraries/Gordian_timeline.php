<?php
if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

/**
 * Timeline managment library.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 3
 * @license GPL 3
 */
class Gordian_timeline
{
	// The reference to the CodeIgniter Library.
	private $CI;
	// Used to store error messages generated during operations.
	private $errors; 
	// Valid durations for the system to use.
	private static $durations = 
		array('MINUTE','HOUR','DAY','WEEK','MONTH',
				'YEAR','DECADE','CENTURY','MILLENIA');
	/**
	 * Default constructor. 
	 * 
	 * Prepares references to the core software models used throughout 
	 * timeline management for the Atlas.
	 */
	public function __construct()
	{
		$this->CI =& get_instance();
		
		// Directly load your models ...
		$this->CI->load->model('Gordian_timeline_model');
		//And your language pack
		$this->CI->lang->load('gordian_timeline');
		// But not others ...
		$this->CI->load->library('Gordian_group');
		$this->CI->load->library('Gordian_view');
	}
	
	public function add($occured_on, $occured_range, $occured_duration, $occured_unit, $initial_alias, $description)
	{
		/*
		 * Prevent things that occured at the same time with the same name from being added.
		 */
		$this->reset_errors(); 
		 
		if (is_object($this->find_event($occured_on, $initial_alias)))
		{
			$this->set_error($this->CI->lang->line('gordian_timeline_error_add_duplicate'));
		}
		
		/*
		 * Validation
		 */
		 
		 // Must be a valid date.
		 if (strlen($occured_on) != 10)
		 {
		 	$this->set_error($this->CI->lang->line('gordian_timeline_error_add_occured_on'));
		 }
		 
		 // Range must be at least 0.
		 if (!is_numeric($occured_range) || $occured_range < 0)
		 {
		 	$this->set_error($this->CI->lang->line('gordian_timeline_error_add_occured_range'));
		 }
		 
		 // Duration must be at least 0.
		 if (!is_numeric($occured_duration) || $occured_duration < 0)
		 {
		 	$this->set_error($this->CI->lang->line('gordian_timeline_error_add_occured_duration'));
		 }
		 
		 // Is the initial Alias valid?
		 if (!is_string($initial_alias) || strlen($initial_alias) < 1)
		 {
			$this->set_error($this->CI->lang->line('gordian_timeline_error_add_alias_invalid'));	 	
		 }

		 // Is the initial Alias valid?
		 if (!is_string($description))
		 {
			$this->set_error($this->CI->lang->line('gordian_timeline_error_add_description_invalid'));
		 }
		 
		 // Is the occurance interval understood?
		 $occured_unit = strtoupper($occured_unit);
		 
		 if (!in_array($occured_unit, Gordian_timeline::$durations))
		 {
			$this->set_error($occured_unit + $this->CI->lang->line('gordian_timeline_error_add_occurance_unit'));			 	
		 }

		if (count($this->errors) > 0)
		{
			return FALSE;
		}

		/*
		 * Begin writing data.
		 */

		// Add the event to the database.
		$event_id = $this->CI->Gordian_timeline_model->add(
			$occured_on, $occured_range, 
			$occured_duration, $occured_unit
		);
		
		// Then add its initial alias.
		$alias_id = $this->add_alias($event_id, $initial_alias);

		// And attaching it to it's initial timeline.
		// TODO: Timeline presently hardcoded to 1.
		$is_attached = $this->attach_timeline($event_id, 1);
		
		// Then associate it to it's wikipage.
		$this->CI->load->library("Gordian_wiki"); 
		
		$wiki_id = $this->CI->gordian_wiki->add($initial_alias, $description);

		// TODO: Timeline presently hardcoded to 1.
		if (is_numeric($wiki_id))
		{
			$this->CI->gordian_wiki->associate_event(1, $event_id, $wiki_id);		
		}
		
		return TRUE;
	}
	
	/**
	 * Adds a new alias to a given event.
	 * 
	 * @param numeric The Id of the event to add to.
	 * @param string The alias to associate to the event.
	 * 
	 * @return mixed The new ID if it was successfully added, or FALSE.
	 */
	public function add_alias($event_id, $alias)
	{
		/*
		 * Prevent things that occured at the same time with the same name from being added.
		 */
		$this->reset_errors(); 
				
		// Duration must be at least 0.
		if (!is_numeric($event_id) || $event_id < 1)
		{
			$this->set_error($this->CI->lang->line('gordian_timeline_error_invalid_id'));
		}
		 
		// Is the initial Alias valid?
		if (!is_string($alias) || strlen($alias) < 1)
		{
			$this->set_error($this->CI->lang->line('gordian_timeline_error_invalid_alias'));	 	
		}
		 
 		if (count($this->errors) > 0)
		{
			return FALSE;
		}
		
		/*
		 * We're fine. Add it.
		 */
		return $this->CI->Gordian_timeline_model->add_alias($event_id, $alias);
	}
	
	public function attach_timeline($event_id, $timeline_id)
	{
		/*
		 * Prevent things that occured at the same time with the same name from being added.
		 */
		$this->reset_errors(); 
				
		// Duration must be at least 0.
		if (!is_numeric($event_id) || $event_id < 1)
		{
			$this->set_error($this->CI->lang->line('gordian_timeline_error_invalid_id'));
		}

		// Duration must be at least 0.
		if (!is_numeric($timeline_id) || $timeline_id < 1)
		{
			$this->set_error($this->CI->lang->line('gordian_timeline_error_invalid_id'));
		}
		
 		if (count($this->errors) > 0)
		{
			return FALSE;
		}
		
		/*
		 * We're fine. Add it.
		 */
		return $this->CI->Gordian_timeline_model->attach_timeline($event_id, $timeline_id);		
	}
	
	/**
	 * Creates a new timeline entry in the Atlas database.
	 * 
	 * The timeline is associated to an owning group.
	 * 
	 * @param numeric The owning group ID of the timeline.
	 * @param string The human readable title of the timeline
	 * @param string The human readable long form description.
	 * 
	 * @return boolean if the timeline was successfully created.
	 */
	public function create($owning_group, $title, $description)
	{	
		// Verify that the timeline is NOT present.
		$timeline_exists = $this->exists($title);
		// But that the group requesting the timeline IS.
		$group_exists = $this->CI->gordian_group->exists($owning_group);

		if (!$timeline_exists && $group_exists)
		{
			// View is implicitly created in this timeline command.
			$created_timeline = $this->CI->Gordian_timeline_model->create($title, $description);

			// Assign the Timeline to the Group
			$is_assigned = $this->CI->Gordian_timeline_model->assign_to($created_timeline, $owning_group);

			if ($is_assigned)
			{
				// Create the new view ...
				$view_id = $this->CI->gordian_view->create($owning_group, $title, $description);

				if(is_numeric($view_id))
				{	
					// And associate it ...
					$is_assigned = $this->CI->gordian_view->assign_to($view_id, $created_timeline);
					
					if ($is_assigned) // We made it this far, we're done!
					{
						return $created_timeline; 
					}
				}
			}
		}
			
		return FALSE;
	}			
	
	/**
	 * TODO: Implement.
	 */
	public function delete($id)
	{
		// NYI
	}
	
	public function edit_event($occured_on, $occured_range, $occured_duration, 
					$occured_unit, $alias, $description, $id)
	{
		/*
		 * Prevent things that occured at the same time with the same name from being added.
		 */
		$this->reset_errors(); 
		
		$existing_event = $this->find_event($id);
		
		if (!is_object($existing_event))
		{
			$this->set_error($this->CI->lang->line('gordian_timeline_error_edit_existence'));
		}
		
		/*
		 * Validation
		 */
		 
		 // Must be a valid date.
		 if (strlen($occured_on) != 10)
		 {
		 	$this->set_error($this->CI->lang->line('gordian_timeline_error_edit_occured_on'));
		 }
		 
		 // Range must be at least 0.
		 if (!is_numeric($occured_range) || $occured_range < 0)
		 {
		 	$this->set_error($this->CI->lang->line('gordian_timeline_error_edit_occured_range'));
		 }
		 
		 // Duration must be at least 0.
		 if (!is_numeric($occured_duration) || $occured_duration < 0)
		 {
		 	$this->set_error($this->CI->lang->line('gordian_timeline_error_edit_occured_duration'));
		 }
		 
		 // Is the initial Alias valid?
		 if (!is_string($alias) || strlen($alias) < 1)
		 {
			$this->set_error($this->CI->lang->line('gordian_timeline_error_edit_alias_invalid'));	 	
		 }

		 // Is the initial Alias valid?
		 if (!is_string($description))
		 {
			$this->set_error($this->CI->lang->line('gordian_timeline_error_edit_description_invalid'));
		 }
		 
		 // Is the occurance interval understood?
		 $occured_unit = strtoupper($occured_unit);
		 
		 if (!in_array($occured_unit, Gordian_timeline::$durations))
		 {
			$this->set_error($occured_unit + $this->CI->lang->line('gordian_timeline_error_add_occurance_unit'));			 	
		 }

		if (count($this->errors) > 0)
		{
			return FALSE;
		}
		 
		/*
		 * Begin writing data.
		 */

		// Add the event to the database.
		$this->CI->Gordian_timeline_model->edit_event(
			$id, $occured_on, $occured_range, 
			$occured_duration, $occured_unit
		);
		
		// Then add its initial alias.
		// Update aliases if necessary.
		if (!in_array($alias, $existing_event->aliases))
		{
			$alias_id = $this->add_alias($id, $alias);
		}
		
		// Then associate it to it's wikipage.
		$this->CI->load->library("Gordian_wiki"); 

		$wiki_details = $this->CI->gordian_wiki->referenced_by('timeline', $id);

		if ($wiki_details->Content != $description)
		{
			$this->CI->gordian_wiki->revise($wiki_details->IdWikiPage, $description);
		}
		
		
		return TRUE;						
	}

	/**
	 * Checks to see if a Timeline exists given a title or Id.
	 * 
	 * @param mixed The title string or group Id for the expected Timeline.
	 * @return boolean If the Timeline in question exists.
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
			return $this->CI->Gordian_timeline_model->exists($arg);
		}
		
		return FALSE;		
	}

	/**
	 * Returns information about a Timeline identified by a title or Id.
	 * 
	 * @param mixed The title string or group Id for the expected Timeline.
	 * @return boolean If the Timeline in question exists.
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
			return $this->CI->Gordian_timeline_model->find($arg);
		}
		
		return FALSE;		
	}
	
	/**
	 * Finds an event given a date and title.
	 * 
	 * Accepts either a given Id, or a combo of {Occurance, Title}
	 * 
	 * @param numeric The Id of a given event.
	 * 
	 * @param string A date of a given event, as {YYYY-MM-DD}
	 * @param string An alias of the given event.
	 */
	public function find_event()
	{
		switch(func_num_args())
		{
			case 2: // Date & Title
				$date = func_get_arg(0);
				$title = func_get_arg(1);
				
				if (!is_string($date) || !is_string($title))
				{
					return FALSE;
				}
				
				return $this->CI->Gordian_timeline_model->find_event($date, $title);
			
			case 1: // By Id.
				$id = func_get_arg(0);
				
				if (!is_numeric($id))
				{
					return FALSE;
				}
			
				return $this->CI->Gordian_timeline_model->find_event($id);
			
			default: // Invalid.
				return FALSE;
		}		
	}

	/**
	 * Returns the JSON data for the given timeline.
	 * 
	 * @param numeric The Id of the timeline to view.
	 * 
	 * @return string JSON data describing the timeline.
	 */
	public function load($timeline_id)
	{
		$timeline_data = $this->CI->Gordian_timeline_model->load($timeline_id);
		
		$json = array('dateTimeFormat' => 'iso8601');
		
		foreach($timeline_data->result() as $row)
		{			
			$json['events'][] = 
				array(
					'start'=> $row->OccuredOn,
        			'title' => $row->Title,
					'isDuration' => ($row->OccuredDuration == 0) ? 0 : 1,
        			'classname'=> 'events id'.$row->IdEvent
				);
		}
		
		return json_encode($json);		
	}
	
	/**
	 * Removes a location pin from a given Timeline's map.
	 * 
	 * @param numeric The Id of the element to remove from the given timeline.
	 */
	public function remove_event($id)
	{
		$this->CI->Gordian_timeline_model->remove_event($id);
	}

	/*
	 * Support Methods
	 */
	 /**
	  * Returns all errors that occured from the last operation.
	  * 
	  * @return array The errors that occcured in the last operation.
	  */
	 public function get_errors()
	 {
	 	return $this->errors;
	 }
	 
	 /**
	  * Empties all known errors, called previous to a new operation.
	  */
	 private function reset_errors()
	 {
	 	$this->errors = array();
	 }
	
	/**
	 * Logs an error message due to faulty operations.
	 * 
	 * @param string The error message to log.
	 */
	private function set_error($error)
	{
		$this->errors[] = $error;
	}
	
	/**
	 * Timeline creation and editing widget.
	 * @param $config See <http://code.google.com/p/gordianatlas/wiki/Gordian_timeline>
	 */
	public function ui_create_edit($config)
	{
		$this->CI->lang->load('gordian_timeline');
		
		/*
		 * Header Details
		 */
		$header_data = (array_key_exists('header', $config)) 
			? $config['header'] 
			: $this->CI->lang->line('gordian_timeline_heading'); 
		$header_label = heading($header_data, 3); 
		
		/*
		 * Title field details
		 */
		$title_data = (array_key_exists('title', $config))
			? $config['title'] 
			: $this->CI->lang->line('gordian_timeline_title_label');
		$title_label = form_label($title_data, 'Nickname');
		$title_field = form_input(array(
							'name' => 'Title', 
							'id' => 'Title', 
							'value' => set_value('Title', '')
						));

		/*
		 * Description field details
		 */
		$description_data = (array_key_exists('description', $config))
			? $config['description'] 
			: $this->CI->lang->line('gordian_timeline_description_label');
		$description_label = form_label($description_data, 'Description');
		$description_field = form_input(array(
							'name' => 'Description', 
							'id' => 'Description', 
							'value' => set_value('Description', '')
						));		
		
		/*
		 * Submit Field Details
		 */
		$submit_label = (array_key_exists('button', $config))
			? $config['button'] 
			: $this->CI->lang->line('gordian_timeline_button');
		$submit_field = form_submit(array(
							'name' => 'postBack', 'value' => set_value('submitValue', $submit_label)
						));
		
		/*
		 * Output the main UI. Note there is no way to supress the Email and Password fields.
		 */
		if (!(array_key_exists('header', $config) && $config['header'] === FALSE))
		{
			echo $header_label;	
		}

		/*
		 * Boilerplate Error Widget output. 
		 * TODO: Consider shifting into seperate component.
		 */
		
		if (strlen(validation_errors()) > 0)
		{	
			echo '<fieldset>';
			echo '<legend>' . $this->CI->lang->line('gordian_auth_widget_header') . '</legend>';
			echo validation_errors();
			echo '</fieldset>';
		}
		
		echo form_open();
		echo '<table>';

		echo '  <tr>';
		echo '		<td align="right">' . $title_label . '</td>';
		echo '		<td align="right" width="1">'. $title_field .'</td>';
		echo '</tr>';		

		echo '  <tr>';
		echo '		<td align="right">' . $description_label . '</td>';
		echo '		<td align="right" width="1">'. $description_field .'</td>';
		echo '</tr>';	
		
		echo '	<tr>';
		echo '		<td></td>';
		echo '		<td align="right">'. $submit_field . '</td>';
		echo '	</tr>';
		
		echo '</table>';
		echo form_close();	
	}
}