<?php
if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

/**
 * This controller manages AJAX request data for the map widget of the Gordian Atlas.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 2
 * @license GPL 3
 */
class Map extends GA_Controller
{
	/**
	 * Default Constructor.
	 */
	function __construct()
	{
		parent::__construct();

		$this->load->library('Gordian_map');
	}
	
	/**
	 * Primary action to load JSON data regarding the map out of the database.
	 */
	public function view()
	{
		$data['jsonData'] = $this->gordian_map->load(1);
		
		$this->load->view("map/view", $data);
	}
	
	/**
	 * Adds information to the map for the timeline
	 */
	public function add()
	{
		if ($this->input->is_ajax_request())
		{
			$name = $this->input->post('name');
			$lat = $this->input->post('lat');
			$lng = $this->input->post('lng');
			$description = $this->input->post('description');
			
			/*
			 * Attempt to add the new location to timeline 1.
			 */
			if (strlen($name) > 0 && is_numeric($lat) && is_numeric($lng) && strlen($description))
			{
				$this->gordian_map->add($lat, $lng, $name, $description, 1);		
				$this->load->view('map/add_succeeded');
				exit();
			}
		}

		$this->load->view('map/add_failed');
	}
	
	/**
	 * Edits a location for the map for the given timeline.
	 */
	public function edit()
	{
		
	}
	
	/**
	 * Edits the location information 
	 */
	public function edit_location()
	{
		if ($this->input->is_ajax_request() && $this->gordian_auth->is_logged_in())
		{
			$name = $this->input->post('name');
			$lat = $this->input->post('lat');
			$lng = $this->input->post('lng');
			$description = $this->input->post('description');
						
			/*
			 * Attempt to add the new location to timeline 1.
			 */
			if (strlen($name) > 0 && is_numeric($lat) && is_numeric($lng) && strlen($description))
			{
				$data_array = explode('/', uri_string());
			 	$id = $data_array[2];

				$this->gordian_map->edit_location($lat, $lng, $name, $description, $id);
		 	} 	
		}		
	}
	
	/**
	 * Action to remove a map item for a given timeline.
	 */
	 public function remove_location()
	 {
		if ($this->input->is_ajax_request() && $this->gordian_auth->is_logged_in())
		{
			$data_array = explode('/', uri_string());
		 	$id = $data_array[2];
	 	
			$this->gordian_map->remove_location($id);
		}			
	 }
}