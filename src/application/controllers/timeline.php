<?php
if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

/**
 * This controller manages AJAX request data for the timeline widget of the Gordian Atlas.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 2
 * @license GPL 3
 */
 class Timeline extends GA_Controller
{
	/**
	 * Default constructor.
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->library('Gordian_timeline');
	}
	
	/**
	 * Called via AJAX on /atlas/view, this routine adds a new event onto the timeline.
	 */
	function add()
	{
		if ($this->input->is_ajax_request())
		{
			$do = $this->input->post('evt_occurance');
			$date_obj = DateTime::createFromFormat('m/d/Y', $do);
			$occured_on = $date_obj->format('Y-m-d'); 
						
			$occured_range = $this->input->post('evt_range');
			$occured_duration = $this->input->post('evt_duration');
			$occured_unit = $this->input->post('evt_units');
			$initial_alias = $this->input->post('evt_name');
			$description = $this->input->post('evt_descript');
			
			/*
			 * Attempt to add the new location to timeline 1.
			 */
			if (strlen($initial_alias) > 0 
				&& strlen($occured_on) > 6 
				&& is_numeric($occured_range) && $occured_range >= 0 
				&& is_numeric($occured_duration) && $occured_duration >= 0
				)
			{
				$result = $this->gordian_timeline->add($occured_on, $occured_range, $occured_duration, 
					$occured_unit, $initial_alias, $description);
				
				$data['occured_on']	= $occured_on;
				$data['occured_range'] = $occured_range;
				$data['occured_duration'] = $occured_duration;
				$data['occured_unit'] = $occured_unit;
				$data['initial_alias'] = $initial_alias;
				$data['description'] = $description;
						
				$this->load->view('timeline/add_succeeded', $data);
			}
			else
			{
				$this->load->view('timeline/add_failed');	
			}
		}
		else
		{
			$this->load->view('timeline/add_failed');				
		}
	}
	
	public function edit_event()
	{
		// $this->input->is_ajax_request() && 
		
		if ($this->gordian_auth->is_logged_in())
		{
			$do = $this->input->post('evt_occurance');
			$date_obj = DateTime::createFromFormat('m/d/Y', $do);
			$occured_on = $date_obj->format('Y-m-d'); 
						
			$occured_range = $this->input->post('evt_range');
			$occured_duration = $this->input->post('evt_duration');
			$occured_unit = $this->input->post('evt_units');
			$initial_alias = $this->input->post('evt_name');
			$description = $this->input->post('evt_descript');
			
			/*
			 * Attempt to add the new location to timeline 1.
			 */
			if (strlen($initial_alias) > 0 
				&& strlen($occured_on) > 6 
				&& is_numeric($occured_range) && $occured_range >= 0 
				&& is_numeric($occured_duration) && $occured_duration >= 0
				)
			{
				$data_array = explode('/', uri_string());
			 	$id = $data_array[2];

				$result = $this->gordian_timeline->edit_event($occured_on, $occured_range, $occured_duration, 
					$occured_unit, $initial_alias, $description, $id);
		 	} 	
		}
	}
	
	/**
	 * Removes a location pin from a given Timeline's map.
	 * 
	 * @param numeric The Id of the element to remove from the given timeline.
	 */
	public function remove_event($id)
	{
		if ($this->input->is_ajax_request() && $this->gordian_auth->is_logged_in())
		{
			
			$data_array = explode('/', uri_string());
		 	$id = $data_array[2];
	
			$this->gordian_timeline->remove_event($id);
		}
	}
	
	/**
	 * Primary action to load JSON data from the database.
	 */
	function view()
	{
		$data['jsonData'] = $this->gordian_timeline->load(1);
		
		$this->load->view("timeline/view", $data);
	}
}