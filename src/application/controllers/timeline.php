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
 class Timeline extends CI_Controller
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
	
	function wiki()
	{
	 	// Load the map stringpack.
	 	$this->lang->load('gordian_timeline');
	 	
	 	// Pull Wiki information
	 	$this->load->library('Gordian_wiki');
	 	
	 	$data_array = explode('/', uri_string());
	 	$kind = $data_array[0];
	 	$id = $data_array[2];
	 	
	 	$wiki_data = $this->gordian_wiki->referenced_by($kind, $id);
	 		 	
	 	// Pull Location Information.
	 	$event_data = $this->gordian_timeline->find_event($id);
	 	
	 	if (is_object($event_data) && is_object($wiki_data))
	 	{
		 	$data['wiki'] = $wiki_data;
		 	$data['event'] = $event_data;
		 			 	
		 	// Don't list the primary city name on the WikiPage.
		 	$data['evt_aka'] = array_diff($event_data->aliases, array($wiki_data->Title));
		 	
		 	$data['aka_lbl'] = $this->lang->line('gordian_timeline_ajax_aka_lbl');

			// Button Strings
		 	$data['edit_lbl'] = $this->lang->line('gordian_timeline_ajax_edit_lbl');
		 	$data['remove_lbl'] = $this->lang->line('gordian_timeline_ajax_remove_lbl');
		 	$data['remove_confirm'] = $this->lang->line('gordian_timeline_ajax_remove_confirm');

			// Time label
			$date = DateTime::createFromFormat('Y-m-d', $event_data->OccuredOn);

			if($event_data->OccuredRange == 0 && $event_data->OccuredDuration == 0)
			{
				$data['timestamp'] = $this->lang->line('gordian_timeline_ajax_occured_lbl');
				$data['timestamp'] .= $date->format('M d, Y');
			} 
			else if ($event_data->OccuredDuration > 0)
			{
			 	$data['timestamp'] = $this->lang->line('gordian_timeline_ajax_duration_lbl');
				$data['timestamp'] .= $date->format('M d, Y');

				// Need to do date adding behaviors.
				switch ($event_data->OccuredUnit) 
				{
					case 'MINUTE':
						$interval = 'PT'. $event_data->OccuredDuration . 'M';
						break;
					case 'HOUR':
						$interval = 'PT'. $event_data->OccuredDuration . 'H';
						break;
					case 'DAY':
						$interval = 'P'. $event_data->OccuredDuration . 'D';
						break;
					case 'WEEK':
						$interval = 'P'. $event_data->OccuredDuration . 'W';
						break;
					case 'MONTH':
						$interval = 'P'. $event_data->OccuredDuration . 'M';
						break;
					case 'YEAR':
						$interval = 'P'. $event_data->OccuredDuration . 'Y';
						break;
					case 'DECADE':
						$interval = 'P'. $event_data->OccuredDuration . '0Y';
						break;
					case 'CENTURY':
						$interval = 'P'. $event_data->OccuredDuration . '00Y';
						break;
					case 'MILLENIA':
						$interval = 'P'. $event_data->OccuredDuration . '000Y';
						break;
				}

				$date->add(new DateInterval($interval));

				$data['timestamp'] .= ' - ';
				$data['timestamp'] .= $date->format('M d, Y');
			}
			else if ($event_data->OccuredRange > 0)
			{
			 	$data['timestamp'] = $this->lang->line('gordian_timeline_ajax_range_lbl');
				$data['timestamp'] .= $date->format('M d, Y');
				$data['timestamp'] .= '&#177; ';
				$data['timestamp'] .= $event_data->OccuredRange . " ";
				
				switch ($event_data->OccuredUnit)
				{
					case 'CENTURY':
						$data['timestamp'] .= $this->lang->line('gordian_timeline_ajax_centuries');
						break;
						
					case 'MILLENIA':
						$data['timestamp'] .= ucfirst(strtolower($event_data->OccuredUnit));
						break;
											
					default:
						 $data['timestamp'] .= ucfirst(strtolower($event_data->OccuredUnit)) . "s";
						break;
					
						
				}
			}
		 	
		 	$this->load->view('timeline/wiki', $data);
	 	}
	 	else
	 	{	 		
	 		$data['error'] = $this->lang->line('gordian_timeline_ajax_error');
		 	$this->load->view('timeline/wiki_error', $data);
	 	}
	}
}