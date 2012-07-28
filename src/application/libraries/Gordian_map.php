<?php
if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

/**
 * The Gordian Atlas Map Interaction Library.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 3
 * @license GPL 3
 */
class Gordian_map 
{
	// CodeIgniter Reference.
	private $CI;
	
	/**
	 * Default Constructor.
	 */
	public function __construct()	
	{
		$this->CI =& get_instance();
		$this->CI->load->model("Gordian_map_model");
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
		$location_id = $this->CI->Gordian_map_model->add($lat, $lng, $name);
		
		// Then associate it to at least one timeline.
		$this->CI->Gordian_map_model->attach_timeline($location_id, $timeline_id);
				
		// Then associate it to it's wikipage.
		$this->CI->load->library("Gordian_wiki"); 
		
		$wiki_id = $this->CI->gordian_wiki->add($name, $description);
		$this->CI->gordian_wiki->associate_location($timeline_id, $location_id, $wiki_id);

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

			return $this->CI->Gordian_map_model->find($lat, $lng);
		}
		else if (func_num_args() == 1)
		{
			$id = func_get_arg(0);
			
			if (!is_numeric($id))
			{
				return FALSE;
			}
			
			return $this->CI->Gordian_map_model->find($id);
		}	
	}
	
	/**
	 * Loads all mapping data as JSON associated to the given timeline
	 * 
	 * @param numeric The ID of the timeline data to load.
	 * @return string JSON data of the timeline.
	 */
	public function load($timeline_id)
	{
		$timeline_data = $this->CI->Gordian_map_model->load($timeline_id);
		
		$json = array();
		
		foreach($timeline_data->result() as $row)
		{
			$json['locations']["id{$row->Id}"] = 
				array('Lat' => $row->Lat, 
					'Lng' => $row->Lng,
					'Title' => $row->Title
			);
		}
		
		return json_encode($json);
	}
	
	public function remove_location($id)
	{
		$this->CI->Gordian_map_model->remove_location($id);
	}
}