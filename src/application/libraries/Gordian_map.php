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
		$this->CI->load->library('gordian_location');
		$this->CI->load->model("Gordian_map_model");
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
}