<?php
/**
 * This controller manages the main "page" of the Gordian Atlas.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 2
 * @license GPL 3
 */

if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

class Atlas extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
		
		/*
		 * Anything under Atlas will always use this stylesheet...
		 */
		$this->gordian_assets->addStyleSheet('/css/gordian.css');		
	}
	
	function view()
	{	
		/*
		 * Assets that need to be loaded for this page.
		 */
		$this->gordian_assets->loadDefaultAssets();

		$this->gordian_assets->addHeaderScript('http://static.simile.mit.edu/timeline/api-2.3.0/timeline-api.js?bundle=true');
		$this->gordian_assets->addFooterScript('http://maps.google.com/maps/api/js?sensor=false');
		$this->gordian_assets->addFooterScript('/js/lib/gmap3.min.js');
		$this->gordian_assets->addFooterScript('/js/atlas/view.js');
		
		$data['superbar_link_string'] = ($this->gordian_auth->is_logged_in()) 
			? $this->lang->line('gordian_auth_logout_lnk_short')
			: $this->lang->line('gordian_auth_register_lnk_short');
		
		/*
		 * Display Contents
		 */
		$this->load->view('atlas/view', $data);
	}
	
	function index()
	{
		redirect('atlas/view');
	}
	
	function maintenance()
	{		
		$online = $this->db->get('GordianConfig', 1);
	 	$status = $online->row();
	 	$data['maintenanceMessage'] = $status->MaintenanceNotice;
		
		/*
		 * Display Contents
		 */
		$this->load->view('atlas/maintenance');
	}
		
	function test()
	{
		$this->load->model('gordian_group_model');
	}
}