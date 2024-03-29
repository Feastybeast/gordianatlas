<?php
if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

/**
 * This controller manages the main "page" of the Gordian Atlas.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 2
 * @license GPL 3
 */
class Atlas extends CI_Controller 
{
	/**
	 * Default constructor.
	 */
	function __construct()
	{
		parent::__construct();

		/*
		 * Anything under Atlas will always use this stylesheet...
		 */
		$this->gordian_assets->loadDefaultAssets();	
	}
	
	/**
	 * The primary screen of the Gordian Atlas application.
	 */
	function view()
	{
		/*
		 * Load required libraries for this action. 
		 */
		 //TODO: We may want to remove the raw reference to this model and use a library instead.
		$this->load->model('Gordian_user_model');
		
		// Used to locate the name of the timeline being viewed.
		$this->load->library('Gordian_timeline');
			
		// Used as labels in the Jquery interface.
		$this->lang->load('gordian_atlas');	
				
		/*
		 * Assets that need to be loaded for this page.
		 */
		$this->gordian_assets->addHeaderScript('http://static.simile.mit.edu/timeline/api-2.3.0/timeline-api.js?bundle=true');
		$this->gordian_assets->addFooterScript('http://maps.google.com/maps/api/js?sensor=false');
		$this->gordian_assets->addFooterScript('/js/lib/gmap3.min.js');
		$this->gordian_assets->addFooterScript('/js/atlas/view.js');
		
		/* 
		 * Prep data for the view.
		 */
		$data['superbar_link_string'] = ($this->gordian_auth->is_logged_in()) 
			? $this->lang->line('gordian_auth_logout_lnk_short')
			: $this->lang->line('gordian_auth_register_lnk_short');

		// Prep location widget data for view.		
		$data['add_button_link'] = $this->lang->line('gordian_atlas_location_add_btn');

		$data['label_location_title'] = $this->lang->line('gordian_atlas_location_title_lbl');
		$data['label_location_name'] = $this->lang->line('gordian_atlas_location_name_lbl');
		$data['label_location_lat'] = $this->lang->line('gordian_atlas_location_lat_lbl');
		$data['label_location_lng'] = $this->lang->line('gordian_atlas_location_lng_lbl');
		$data['label_location_description'] = $this->lang->line('gordian_atlas_location_descript_lbl');		

		// Prep event widget data.
		$data['add_button_event'] = $this->lang->line('gordian_atlas_event_add_btn');

		$data['label_event_title'] = $this->lang->line('gordian_atlas_event_title_lbl');
		$data['label_event_name'] = $this->lang->line('gordian_atlas_event_name_lbl');
		$data['label_event_description'] = $this->lang->line('gordian_atlas_event_descrpt_lbl');
		$data['label_event_occurance'] = $this->lang->line('gordian_atlas_event_occurance_lbl');
		$data['label_event_duration'] = $this->lang->line('gordian_atlas_event_duration_lbl');
		$data['label_event_range'] = $this->lang->line('gordian_atlas_event_range_lbl');
		$data['label_event_notice'] = $this->lang->line('gordian_atlas_event_range_duration_notice');
		
		// Concept Management UI.
		$data['label_concept_title'] = $this->lang->line('gordian_atlas_concept_title_lbl');
		$data['label_concept_name'] = $this->lang->line('gordian_atlas_concept_name_lbl');
		$data['label_concept_description'] = $this->lang->line('gordian_atlas_concept_descript_lbl');
		
		// Person Content
		$data['label_person_title'] = $this->lang->line('gordian_atlas_person_title_lbl');
		$data['label_person_name'] = $this->lang->line('gordian_atlas_person_name_lbl');
		$data['label_person_birth'] = $this->lang->line('gordian_atlas_person_birth_lbl');
		$data['label_person_birth_loc'] = $this->lang->line('gordian_atlas_person_birth_loc_lbl');
		$data['label_person_death'] = $this->lang->line('gordian_atlas_person_death_lbl');
		$data['label_person_death_loc'] = $this->lang->line('gordian_atlas_person_death_loc_lbl');
		$data['label_person_description'] = $this->lang->line('gordian_atlas_person_bio_lbl');
		
		$data['label_person_loc_unknown'] = $this->lang->line('gordian_atlas_person_loc_unknown');
		
		// Relation Comment
		$data['label_relation_title'] = $this->lang->line('gordian_atlas_relation_title_lbl');
						
		// Deletion notices for logged in users.
		$data['delete_title'] = $this->lang->line('gordian_atlas_delete_dialog_title');
		$data['delete_content'] = $this->lang->line('gordian_atlas_delete_dialog_content');

		/*
		 * This is a reference to the current user model information.
		 */
		$data['user_data'] = $this->Gordian_user_model->current();
		
		// Identify the first timeline and output it's name.
		$data['timeline_name'] = $this->gordian_timeline->find(1)->Title;
				
		/*
		 * Display Contents
		 */
		$this->load->view('atlas/view', $data);
	}
	
	/**
	 * Routes the user to the prefered "view" action rather than index.
	 */
	function index()
	{
		redirect('atlas/view');
	}
	
	/**
	 * The maintenance display screen. 
	 * 
	 * Displayed when non-administrative users attempt to access the website
	 * during a maintenance period.
	 */
	function maintenance()
	{	
		$this->load->library('Gordian_state');
	 	$data['maintenanceMessage'] = $this->gordian_state->get_maintenance_notice();
		
		/*
		 * Display Contents
		 */
		$this->load->view('atlas/maintenance', $data);
	}
	
	function wiki()
	{
		$this->lang->load('gordian_atlas');
		
		$data['splash_header'] = $this->lang->line('gordian_atlas_splash_header');
		$data['splash_content'] = $this->lang->line('gordian_atlas_splash_body');
		
		$this->load->view('atlas/wiki', $data);
	}
}