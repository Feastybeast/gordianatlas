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
		$this->load->model('gordian_user_model');
		
		$this->load->library('Gordian_timeline');
				
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
			
		/*
		 * This is a reference to the current user model information.
		 */
		$data['user_data'] = $this->gordian_user_model->current();
		
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
		$this->load->library('gordian_state');
	 	$data['maintenanceMessage'] = $this->gordian_state->get_maintenance_notice();
		
		/*
		 * Display Contents
		 */
		$this->load->view('atlas/maintenance', $data);
	}	
}