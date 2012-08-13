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
class Person extends GA_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('gordian_person');
		
		$this->lang->load('gordian_atlas');
		$this->lang->load('gordian_location');
	}
	
	public function edit()
	{
		if ($this->input->is_ajax_request() && $this->gordian_auth->is_logged_in())
		{
			if ($this->record_id() == FALSE)
			{
				return FALSE;
			}
		
			$update_data = $this->post_vars();
				
			if (count($update_data) > 0)
			{
				$this->gordian_person->edit($this->record_id(), $update_data);
			}
		}		
	}
	
	public function related_locations()
	{
		$records = $this->gordian_person->related_locations();

		/*
		 * Ensure the first row is present, properly translated.
		 */
		$first_row = new stdClass();
		$first_row->IdLocation = '0';
		$first_row->Title = $this->lang->line('gordian_atlas_person_loc_unknown');

		$records = array_merge(array($first_row), $records);
	
		if ($records == FALSE)
		{
			$this->lang->load('gordian_wiki');
				
			$data['title'] = $this->lang->line('gordian_wiki_error_generic_title');
			$data['error'] = $this->lang->line('gordian_wiki_error_generic_body');
			
			$this->load->view('wiki/error', $data);
		}
		else
		{
			exit(json_encode($records));		
		}
	}
	
	/**
	 * Wiki Screen
	 */
	public function wiki()
	{
		$data = $this->gordian_wiki->data_template('person');
		
		$person = $this->gordian_person->find($this->record_id());
		$wiki_data = $this->gordian_wiki->referenced_by('person', $person->IdPerson);
		
		$birth_loc = $this->gordian_location->find($person->BirthLocation);
		$death_loc = $this->gordian_location->find($person->DeathLocation);
		
		/*
		 * Main data.
		 */
		$formatted_birth = DateTime::createFromFormat('Y-m-d', $person->BirthEvent)->format('m/d/Y'); 
		$formatted_death = DateTime::createFromFormat('Y-m-d', $person->DeathEvent)->format('m/d/Y'); 
		 
		$data['title'] = $wiki_data->Title;
		$data['title_id'] = 'person_name_val';

		$data['record_id'] = $person->IdPerson;

		$data['block_content'] .= "<p>";
		
		if (is_object($birth_loc))
		{
			$data['block_content'] .= "<div>Born ". $formatted_birth . " at ";			
			$data['block_content'] .= '<a href="/location/wiki/' . $birth_loc->Id . '" class="wiki_btn">' . $birth_loc->aliases[0] . '</a></div>';			
		}
		
		if (is_object($death_loc))
		{
			$data['block_content'] .= "<div>Died ". $formatted_death . " at ";			
			$data['block_content'] .= '<a href="/location/wiki/' . $death_loc->Id . '" class="wiki_btn">' . $death_loc->aliases[0] . '</a></div>';			
			
		}		

		$data['block_content'] .="</p>";

	 	$data['block_content'] .= '<div id="person_descript_val">' . $wiki_data->Content . '</div>';

		$data['block_content'] .= '<input type="hidden" id="person_birth_loc_val" value="' . trim($person->BirthLocation) . '" />';
		$data['block_content'] .= '<input type="hidden" id="person_birth_val" value="' . trim($formatted_birth) . '" />';
		$data['block_content'] .= '<input type="hidden" id="person_death_loc_val" value="' . trim($person->DeathLocation) . '" />';
		$data['block_content'] .= '<input type="hidden" id="person_death_val" value="' . trim($formatted_death) . '" />';

	 	/*
	 	 * Link the event tabs as necessary.
	 	 */
		if (count($person->events) > 0)
		{
			$data['block_events'] = '<div><strong>' . $this->lang->line('gordian_location_ajax_events_label') . '</strong></div>';
			
			$data['block_events'] .= '<ul>';
			
			foreach($person->events as $k => $v)
			{
				$data['block_events'] .= '<li><a href="/event/wiki/' . $v["Id"] . '" class="wiki_btn">' . $v["Title"] . '</a></li>'; 
			}

			$data['block_events'] .= '</ul>';				
		}
		
		$this->load->view('wiki/template', $data);
	}
}