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
class Gordian_location
{
	// Class reference to CodeIgniter Instance.
	private $CI; 
	
	/**
	 * Default Constructor.
	 */
	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->model('Gordian_location_model');
		$this->CI->load->library('gordian_wiki');
	}

	/**
	 * @param float The latitude the location should be added to.
	 * @param float The longitude the location should be added to.
	 * @param string The name of the location.
	 * @param string a brief initial wikipage description for the location.
	 */
	public function add($lat, $lng, $name, $description)	
	{
		$location = $this->find($lat, $lng);
		
		// Object is already present, get out.
		if (is_object($location))
		{
			return FALSE;
		}
		
		// Timeline at present is hardcoded.
		$timeline_id = 1;
		
		// First, add it to the locations database ...
		$location_id = $this->CI->Gordian_location_model->add($lat, $lng, $name);
		
		// Then associate it to at least one timeline.
		$this->CI->Gordian_location_model->attach_timeline($location_id, $timeline_id);
				
		// Then associate it to it's wikipage.
		$this->CI->load->library("Gordian_wiki"); 
		
		$wiki_id = $this->CI->gordian_wiki->add($name, $description);
		$this->CI->gordian_wiki->associate_location($timeline_id, $location_id, $wiki_id);

		return TRUE;
	}

	/**
	 * Updates information for the given location identified by Id.
	 * 
	 * @param numeric The latitude of the given location.
	 * @param numeric The longitude of the given location.
	 * @param string The updated name of the given location.
	 * @param string The revised wikipage of the given location.
	 * @param numeric The Location ID to update.
	 */
	public function edit($lat, $lng, $name, $description, $id)
	{
		// Update the Alias if required.
		$existing_details = $this->find($id);
						
		if (!is_object($existing_details))
		{
			return FALSE;
		}		

		// Update the coordinates IIF they're not trampling somewhere else.
		if ($existing_details->Lat != $lat || $existing_details->Lng != $lng)
		{
			$check_loc = $this->find($lat, $lng);
			
			if ($check_loc == FALSE)
			{
				$this->CI->Gordian_location_model->update_latlng($id, $lat, $lng);
			}
		}

		// Update aliases if necessary.
		if (!in_array($name, $existing_details->aliases))
		{
			$this->CI->Gordian_location_model->add_alias($id, $name);
		}
		
		// Then update it's wikipage if necessary.
		$this->CI->load->library("Gordian_wiki"); 
		
		$wiki_details = $this->CI->gordian_wiki->referenced_by('location', $id);
		
		if ($wiki_details->Content != $description)
		{
			$this->CI->gordian_wiki->revise($wiki_details->IdWikiPage, $description);
		}
		
		return TRUE;
	}
	
	/**
	 * Locates a given Location in the database given either
	 * 
	 * @param mixed {lat,lng} pair as seperate arguments, or an Id.
	 * 
	 * @return mixed An object containing relevant data, or FALSE.
	 */
	public function find()
	{		
		if (func_num_args() == 2)
		{
			$lat = func_get_arg(0);
			$lng = func_get_arg(1);
			
			if (!is_numeric($lat) || $lat > 90 || $lat < -90)
			{
				return FALSE;
			}
			else if (!is_numeric($lng) || $lat > 180 || $lat < -180 )
			{
				return FALSE;
			}

			return $this->CI->Gordian_location_model->find($lat, $lng);
		}
		else if (func_num_args() == 1)
		{
			$id = func_get_arg(0);
			
			if (!is_numeric($id))
			{
				return FALSE;
			}
			
			return $this->CI->Gordian_location_model->find($id);
		}	
	}
	
	/**
	 * Updates the events associated to the provided location.
	 * 
	 * @param numeric The location Id to update.
	 * @param array The related events to associate to the location.
	 */
	public function relate_events($location_id, $new_relations)
	{
		$this->CI->Gordian_location_model->relate_events($location_id, $new_relations);
	}
	
	/**
	 * Returns a datastructure containing essential associated records.
	 * 
	 * @param numeric The ID of the location in question.
	 * 
	 * @return mixed Either the data in question, or FALSE.
	 */
	public function related_events($location_id)
	{
		return $this->CI->Gordian_location_model->related_events($location_id);
	}
	
	/**
	 * Removes a given location from a provided map.
	 */
	public function remove($id)
	{
		$this->CI->Gordian_location_model->remove($id);
	}	
}