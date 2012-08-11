<?php
if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

/**
 * The success screen for registering a new account.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 4
 * @license GPL 3
 */
 
class Gordian_event 
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
	 * Default Constructor.
	 */
	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->model('Gordian_event_model');
		$this->CI->load->library('gordian_wiki');
		$this->CI->load->library('gordian_concept');
		$this->CI->lang->load('gordian_timeline');
	}

	public function add_concept($id, $concept_alias, $concept_content)
	{
		if (!$this->find($id))
		{
			return FALSE;
		}
		
		$concept_id = $this->CI->gordian_concept->add($concept_alias, $concept_content);
		$this->CI->gordian_concept->attach_event($id, $concept_id);
	}

	public function edit($occured_on, $occured_range, $occured_duration, 
					$occured_unit, $alias, $description, $id)
	{
		/*
		 * Prevent things that occured at the same time with the same name from being added.
		 */
		$this->reset_errors(); 
		
		$existing_event = $this->find($id);
		
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
		 
		 if (!in_array($occured_unit, Gordian_event::$durations))
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
		$this->CI->Gordian_event_model->edit(
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

		$wiki_details = $this->CI->gordian_wiki->referenced_by('event', $id);

		if ($wiki_details->Content != $description)
		{
			$this->CI->gordian_wiki->revise($wiki_details->IdWikiPage, $description);
		}
		
		
		return TRUE;						
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
	public function find()
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
				
				return $this->CI->Gordian_event_model->find($date, $title);
			
			case 1: // By Id.
				$id = func_get_arg(0);
				
				if (!is_numeric($id))
				{
					return FALSE;
				}
			
				return $this->CI->Gordian_event_model->find($id);
			
			default: // Invalid.
				return FALSE;
		}		
	}

	/**
	 * Updates the concepts associated to the provided event.
	 * 
	 * @param numeric The event Id to update.
	 * @param array The related concepts to associate to the event.
	 */
	public function relate_concepts($event_id, $new_relations)
	{
		$this->CI->Gordian_event_model->relate_concepts($event_id, $new_relations);
	}
	
	/**
	 * Returns a datastructure containing essential associated records.
	 * 
	 * @param numeric The ID of the event in question.
	 * 
	 * @return mixed Either the data in question, or FALSE.
	 */
	public function related_concepts($event_id)
	{
		return $this->CI->Gordian_event_model->related_concepts($event_id);
	}
	
	/**
	 * Updates the Locations associated to the provided event.
	 * 
	 * @param numeric The event Id to update.
	 * @param array The related events to associate to the event.
	 */
	public function relate_locations($event_id, $new_relations)
	{
		$this->CI->Gordian_event_model->relate_locations($event_id, $new_relations);
	}
	
	/**
	 * Returns a datastructure containing essential associated records.
	 * 
	 * @param numeric The ID of the location in question.
	 * 
	 * @return mixed Either the data in question, or FALSE.
	 */
	public function related_locations($event_id)
	{
		return $this->CI->Gordian_event_model->related_locations($event_id);
	}
	
	/**
	 * Removes a location pin from a given Timeline's map.
	 * 
	 * @param numeric The Id of the element to remove from the given timeline.
	 */
	public function remove($id)
	{
		$this->CI->Gordian_event_model->remove($id);
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
}