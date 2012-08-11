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
	public function __construct()
	{
		parent::__construct();

	 	// Prepare the wiki template
		$this->load->library('gordian_wiki');
		$this->load->library('gordian_timeline');
	}
	
	public function wiki()
	{
	 	// Load the map stringpack.
	 	$this->lang->load('gordian_timeline');
	 	
	 	$kind = $this->record_type();
	 	$id = $this->record_id();
	 	
		$data = $this->gordian_wiki->data_template($kind);		

	 	// Pull the wiki information
	 	$wiki_data = $this->gordian_wiki->referenced_by($kind, $id);
	 		 	
	 	// Pull the event Information.
	 	$event_data = $this->gordian_timeline->find_event($id);
	 	
	 	/*
	 	 * Revoke the required tabs.
	 	 */
	 	if (is_object($event_data) && is_object($wiki_data))
	 	{
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
			
			/*
			 * Finally output information
			 */
			$data['record_type'] = $this->record_type(); 
			$data['record_id'] = $this->record_id(); 
			 
		 	$data['title'] = $wiki_data->Title;
		 	
		 	$data['block_content'] = $data['timestamp'];
		 	
		 	$data['block_content'] .= "<div>" . $wiki_data->Content . "</div>";
		 			 	
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