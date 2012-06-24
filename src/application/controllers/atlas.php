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
		$this->gordian_assets->addHeaderScript('http://static.simile.mit.edu/timeline/api-2.3.0/timeline-api.js?bundle=true');

		$this->gordian_assets->addFooterScript('https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js');
		$this->gordian_assets->addFooterScript('https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js');
		$this->gordian_assets->addFooterScript('http://maps.google.com/maps/api/js?sensor=false');
		$this->gordian_assets->addFooterScript('/js/lib/gmap3.min.js');
		$this->gordian_assets->addFooterScript('/js/atlas/view.js');
		
		/*
		 * Display Contents
		 */
		$this->load->view('atlas/view');
	}
	
	function index()
	{
		redirect('atlas/view');
	}
	
	function maintenance()
	{		
		 $online = $this->db->get('GordianConfig', 1);
		 
		 if ($online->num_rows() == 0)
		 {
		 	$data['maintenanceMessage'] = "The System is currently unavailable.";
		 }
		 else 
		 {
		 	$status = $online->row();
		 	$data['maintenanceMessage'] = $status->MaintenanceNotice;
		 }
		
		/*
		 * Display Contents
		 */
		$this->load->view('atlas/maintenance');
	}
	
	function unconfigured()
	{
		/*
		 * Display Contents
		 */
		$this->load->view('atlas/unconfigured');
	}
	
	function configuration()
	{		
		/*
		 * Display Contents
		 */		
		$this->load->view('atlas/configuration');		
	}
	
	function configured()
	{
		/*
		 * Display Contents
		 */
		$this->load->view('atlas/configured');
	}
} 
?>
