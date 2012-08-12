<?php
if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

/**
 * The Gordian Concept management library.
 * 
 * @author Rob Mann <mannro@metrostate.edu>
 * @since Elaboration 5
 * @license GPL 3
 */
class Gordian_concept
{
	// CodeIgniter Reference
	private $CI;
	
	/**
	 * Default Constructor.
	 */
	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->model('Gordian_concept_model');
		$this->CI->load->library('Gordian_wiki');
	}
	
	/**
	 * Adds a new concept to the database.
	 * 
	 * @param string The title of the concept.
	 * @param string The description of the concept.
	 */
	public function add($title, $content)
	{
		return $this->CI->Gordian_concept_model->add($title,$content);
	}

	/**
	 * Removes a concept from a given event in the current timeline.
	 * 
	 * @param numeric The Id of the event to attach to.
	 * @param numeric The Id of the concept to remove from the given timeline.
	 */
	public function attach_event($record_id, $concept_id)
	{
		return $this->CI->Gordian_concept_model->attach('event', $record_id, $concept_id);
	}

	/**
	 * Removes a concept from a given event in the current timeline.
	 * 
	 * @param numeric The Id of the location to attach to.
	 * @param numeric The Id of the concept to remove from the given timeline.
	 */
	public function attach_location($record_id, $concept_id)
	{
		return $this->CI->Gordian_concept_model->attach('location', $record_id, $concept_id);
	}

	/**
	 * Removes a concept from a given person in the current timeline.
	 * 
	 * @param numeric The Id of the person to attach to.
	 * @param numeric The Id of the concept to remove from the given timeline.
	 */
	public function attach_person($record_id, $concept_id)
	{
		return $this->CI->Gordian_concept_model->attach('person', $record_id, $concept_id);
	}

	/**
	 * Removes a concept from a given event in the current timeline.
	 * 
	 * @param numeric The Id of the event to detach from.
	 * @param numeric The Id of the concept to remove from the given timeline.
	 */
	public function detach_event($record_id, $concept_id)
	{
		return $this->CI->Gordian_concept_model->detach('event', $record_id, $concept_id);
	}

	/**
	 * Removes a concept from a given event in the current timeline.
	 * 
	 * @param numeric The Id of the location to detach from.
	 * @param numeric The Id of the concept to remove from the given timeline.
	 */
	public function detach_location($record_id, $concept_id)
	{
		return $this->CI->Gordian_concept_model->detach('location', $record_id, $concept_id);
	}

	/**
	 * Removes a concept from a given person in the current timeline.
	 * 
	 * @param numeric The Id of the person to detach from.
	 * @param numeric The Id of the concept to remove from the given timeline.
	 */
	public function detach_person($record_id, $concept_id)
	{
		return $this->CI->Gordian_concept_model->detach('person', $record_id, $concept_id);
	}

	/**
	 * Edit a concept for a timeline.
	 */
	public function edit($record_id, $updated_content)
	{
		$the_concept = $this->find($record_id);

		if (!is_object($the_concept))
		{
			return FALSE;
		}
				
		return $this->CI->gordian_wiki->revise($the_concept->wikidata->IdWikiPage, $updated_content);
	}
	
	/**
	 * Find a concept via ID or Title.
	 * 
	 * @param mixed either an ID or title
	 **/
	public function find()
	{
		if (func_num_args()!=1)
		{
			return FALSE;
		}
		
		$arg=func_get_arg(0);
		
		if (!is_string($arg) && !is_numeric($arg))
		{
			return FALSE;
		}
		
		return $this->CI->Gordian_concept_model->find($arg);
	}
	
	/**
	 * Updates the events associated to the provided concept.
	 * 
	 * @param numeric The location Id to update.
	 * @param array The related events to associate to the location.
	 */
	public function relate_events($concept_id, $new_relations)
	{
		$this->CI->Gordian_concept_model->relate_events($concept_id, $new_relations);
	}
	
	/**
	 * Returns a datastructure containing essential associated records.
	 * 
	 * @param numeric The ID of the location in question.
	 * 
	 * @return mixed Either the data in question, or FALSE.
	 */
	public function related_events($concept_id)
	{
		return $this->CI->Gordian_concept_model->related_events($concept_id);
	}	
}