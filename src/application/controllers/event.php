<?php
if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

/**
 * The event controller - used to pull data for timeline subobjects.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 4
 * @license GPL 3
 */
class Event extends GA_Controller
{
	/**
	 * Default constructor.
	 */
	public function __construct()
	{
		parent::__construct();

	 	// Prepare the wiki template
		$this->load->library('gordian_wiki');
		$this->load->library('gordian_event');
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
				$result = $this->gordian_event->add($occured_on, $occured_range, $occured_duration, 
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
	 * Manually attributes a new concept to a record.
	 */
	public function add_concept()
	{
		if ($this->gordian_auth->is_logged_in())
		{
			 $post = $this->post_vars();
			 
			 $this->gordian_event->add_concept($this->record_id(), $post['title'], $post['content']);
			 
			 echo "Updated";
			exit();
		}
	}
	
	/**
	 * Edits information regarding the current event.
	 */
	public function edit()
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
				$id = $this->record_id();

				$result = $this->gordian_event->edit($occured_on, $occured_range, $occured_duration, 
					$occured_unit, $initial_alias, $description, $id);
		 	} 	
		}
	}

	/**
	 * Returns and manages the list of related locations to this event.
	 */
	public function related_concepts()
	{
		$post = $this->post_vars();

		if (array_key_exists('related_updated', $post))
		{
			if ($this->gordian_auth->is_logged_in())
			{
				$related = (array_key_exists('related', $post)) ? $post['related'] : array();
	
				$this->gordian_event->relate_concepts($this->record_id(), $related);
	
				echo "Updated";
				exit();
			}
		}
		else
		{
			$records = $this->gordian_event->related_concepts($this->record_id());
	
			if ($records == FALSE)
			{
				$this->lang->load('gordian_wiki');
				
				$data['title'] = $this->lang->line('gordian_wiki_error_generic_title');
				$data['error'] = $this->lang->line('gordian_wiki_error_generic_body');
				
				$this->load->view('wiki/error', $data);
			}
			else
			{
				$data['output'] = '';
				
				foreach($records as $k => $v)
				{
					$recs = explode(',', $v->Mapped);
					$data['output'] .= form_checkbox('related', $v->IdConcept, (in_array($this->record_id(), $recs)) ? TRUE : FALSE);
					$data['output'] .= ' ' . $v->Content . "<br />\n";
				}

					$data['output'] .= '<input type="hidden" id="related_updated" name="updated" value="1" />';

				
				exit($data['output']);
			}			
		}			
	}
	
	/**
	 * Returns and manages the list of related locations to this event.
	 */
	public function related_locations()
	{		
		$post = $this->post_vars();

		if (array_key_exists('related_updated', $post))
		{
			if ($this->gordian_auth->is_logged_in())
			{
				$related = (array_key_exists('related', $post)) ? $post['related'] : array();
	
				$this->gordian_event->relate_locations($this->record_id(), $related);
	
				echo "Updated";
				exit();	
			}			
		}
		else
		{
			$records = $this->gordian_event->related_locations($this->record_id());
	
			if ($records == FALSE)
			{
				$this->lang->load('gordian_wiki');
				
				$data['title'] = $this->lang->line('gordian_wiki_error_generic_title');
				$data['error'] = $this->lang->line('gordian_wiki_error_generic_body');
				
				$this->load->view('wiki/error', $data);
			}
			else
			{
				$data['output'] = '';
				
				foreach($records as $k => $v)
				{
					$recs = explode(',', $v->Mapped);
					$data['output'] .= form_checkbox('related', $v->IdLocation, (in_array($this->record_id(), $recs)) ? TRUE : FALSE);
					$data['output'] .= ' ' . $v->Title . "<br />\n";
				}

					$data['output'] .= '<input type="hidden" id="related_updated" name="updated" value="1" />';

				
				exit($data['output']);
			}			
		}
	}

	/**
	 * Removes a location pin from a given Timeline's map.
	 * 
	 * @param numeric The Id of the element to remove from the given timeline.
	 */
	public function remove($id)
	{
		if ($this->input->is_ajax_request() && $this->gordian_auth->is_logged_in())
		{	
			$this->gordian_event->remove($this->record_id());
		}
	}
	
	/**
	 * Loads WikiData for the current Event.
	 */
	public function wiki()
	{
	 	// Load the map stringpack.
	 	$this->lang->load('gordian_events');
	 	
	 	$kind = $this->record_type();
	 	$id = $this->record_id();
	 	
		$data = $this->gordian_wiki->data_template($kind);		

	 	// Pull the wiki information
	 	$wiki_data = $this->gordian_wiki->referenced_by($kind, $id);
	 		 	
	 	// Pull the event Information.
	 	$event_data = $this->gordian_event->find($id);
	 	
	 	/*
	 	 * Revoke the required tabs.
	 	 */
	 	if (is_object($event_data) && is_object($wiki_data))
	 	{
		 	// Don't list the primary city name on the WikiPage.
		 	$data['evt_aka'] = array_diff($event_data->aliases, array($wiki_data->Title));
		 	
		 	$data['aka_lbl'] = $this->lang->line('gordian_timeline_ajax_aka_lbl');

			// Time label
			$date = DateTime::createFromFormat('Y-m-d', $event_data->OccuredOn);
			$input_date_val = $date->format('m/d/Y');

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

			/*
			 * Load related locations as necessary.
			 */
			if (count($event_data->locations) > 0)
			{
				$data['block_locations'] = '<div><strong>' . $this->lang->line('gordian_events_ajax_locations_label') . '</strong></div>';
				
				$data['block_locations'] .= '<ul>';
				
				foreach($event_data->locations as $k => $v)
				{
					$data['block_locations'] .= '<li><a href="/location/wiki/' . $v["Id"] . '" class="wiki_btn">' . $v["Title"] . '</a></li>'; 
				}

				$data['block_locations'] .= '</ul>';				
			}

			/*
			 * Load related concepts as necessary.
			 */
			if (count($event_data->concepts) > 0)
			{
				$data['block_concepts'] = '<div><strong>' . $this->lang->line('gordian_events_ajax_concepts_label') . '</strong></div>';
				
				$data['block_concepts'] .= '<ul>';
				
				foreach($event_data->concepts as $k => $v)
				{
					$data['block_concepts'] .= '<li><a href="/concept/wiki/' . $v["Id"] . '" class="wiki_btn">' . $v["Title"] . '</a></li>'; 
				}

				$data['block_concepts'] .= '</ul>';				
			}

			/*
			 * Finally output information
			 */
			$data['record_type'] = $this->record_type(); 
			$data['record_id'] = $this->record_id(); 
			 
		 	$data['title'] = $wiki_data->Title;
		 	$data['title_id'] = 'evt_name_val';
		 	
		 	$data['block_content'] = $data['timestamp'];
		 	
		 	$data['block_content'] .= '<div id="evt_descript_val">' . $wiki_data->Content . '</div>';

			$data['block_content'] .= '<input type="hidden" id="evt_occurance_val" value="' . trim($input_date_val) . '" />';
			$data['block_content'] .= '<input type="hidden" id="evt_units_val" value="' . trim($event_data->OccuredUnit) . '" />';
			$data['block_content'] .= '<input type="hidden" id="evt_range_val" value="' . trim($event_data->OccuredRange) . '" />';
			$data['block_content'] .= '<input type="hidden" id="evt_duration_val" value="' . trim($event_data->OccuredDuration) . '" />';

		 	$this->load->view('wiki/template', $data);
	 	}
	 	else
	 	{	
	 		$data['title'] = $this->lang->line('gordian_timeline_ajax_title'); 		
	 		$data['error'] = $this->lang->line('gordian_timeline_ajax_error');
		 	$this->load->view('wiki/error', $data);
	 	}
	}
}