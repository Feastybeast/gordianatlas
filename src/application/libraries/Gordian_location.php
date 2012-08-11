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
}