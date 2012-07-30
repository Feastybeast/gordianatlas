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
class Map extends CI_Controller
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
	 
	 /**
	  * 
	  */
	 public function wiki()
	 {
	 	// Load the map stringpack.
	 	$this->lang->load('gordian_map');
	 	
	 	// Pull Wiki information
	 	$this->load->library('Gordian_wiki');
	 	
	 	$data_array = explode('/', uri_string());
	 	$kind = $data_array[0];
	 	$id = $data_array[2];
	 	
	 	$wiki_data = $this->gordian_wiki->referenced_by($kind, $id);
	 	
	 	// Pull Location Information.
	 	$location_data = $this->gordian_map->find($id);
	 	
	 	if (is_object($location_data) && is_object($wiki_data))
	 	{
		 	$data['wiki'] = $wiki_data;
		 	$data['location'] = $location_data;
		 	
		 	// Don't list the primary city name on the WikiPage.
		 	$data['aka_lbl'] = $this->lang->line('gordian_map_ajax_aka_label');
		 	$data['loc_aka'] = array_diff($location_data->aliases, array($wiki_data->Title));
		 			 	
		 	$data['latlng_lbl'] = $this->lang->line('gordian_map_ajax_latlng_lbl');
		 	
		 	// Manipulation Labels
		 	$data['edit_lbl'] = $this->lang->line('gordian_map_ajax_edit_lbl');
		 	$data['remove_lbl'] = $this->lang->line('gordian_map_ajax_remove_lbl');
		 	$data['remove_confirm'] = $this->lang->line('gordian_map_ajax_remove_confirm');
		 	
		 	$this->load->view('map/wiki', $data);
	 	}
	 	else
	 	{	
	 		$data['title'] = $this->lang->line('gordian_map_ajax_title');
	 		$data['error'] = $this->lang->line('gordian_map_ajax_error');
		 	$this->load->view('map/wiki_error', $data);
	 	}
	 }
}