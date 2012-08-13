<?php
if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

/**
 * Gordian Person Management Library
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 4
 * @license GPL 3
 */
class Gordian_person
{
	private $CI;
	
	/**
	 * Default Constructor.
	 */
	public function __construct()
	{
		$this->CI =& get_instance();
		
		$this->CI->load->model('Gordian_person_model');
		
		$this->CI->load->library('gordian_event');
		$this->CI->load->library('gordian_location');		
	}
	
	/**
	 * Locates a person via name or ID
	 * 
	 * @param mixed either an alias or an ID
	 * 
	 * @return mixed An object or FALSE.
	 */
	public function find($criteria)
	{
		if (!is_string($criteria) && !is_numeric($criteria))
		{
			return FALSE;
		}
		
		return $this->CI->Gordian_person_model->find($criteria);
	}
	
	/**
	 * Adds a new person to the database.
	 * 
	 * @param array The criteria to associate to the given person.
	 */
	public function add($criteria)
	{
		return $this->CI->Gordian_person_model->add($criteria);
	}
	
	/**
	 * Attaches an event to the given personality.
	 * 
	 * @param numeric The person to associate.
	 * @param numeric the event to attribute them to.
	 * 
	 * @return boolean Indicates success of operation.
	 */
	public function attach_event($person_id, $event_id)
	{
		return $this->CI->Gordian_person_model->attach_event($person_id, $event_id);
	}

	/**
	 * Edits a given person within the database.
	 * 
	 * @param numeric The person to edit.
	 * @param numeric The updated criteria to associate to the given person.
	 * 
	 * @return boolean Indicates success of operation.
	 */	
	public function edit($id, $criteria)
	{
		return $this->CI->Gordian_person_model->edit($id, $criteria);		
	}
	
	/**
	 * Returns all locations known within the database to use in the LoB / LoD widgets.
	 */
	public function related_locations()
	{
		return $this->CI->Gordian_person_model->related_locations();
	}
	
	public function remove()
	{
		//TODO: NYI, unclear how remove impacts information.
	}
} 