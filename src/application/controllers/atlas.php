<?php
/*
 * Created on Jun 14, 2012
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
class Atlas extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
	}
	
	function view()
	{
		/*
		 * Assets that need to be loaded for this page.
		 */
		$data['assets'] = array(
			'headerscripts' => array(
				'http://static.simile.mit.edu/timeline/api-2.3.0/timeline-api.js?bundle=true'
			),
			'footerscripts' => array(
				'https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js',
				'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js',	
				'http://maps.google.com/maps/api/js?sensor=false',
				'/js/lib/gmap3.min.js',
				'/js/atlas/view.js'
			),
			'stylesheets' => array(
				'/css/gordian.css'
			)
		);
		
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
		/*
		 * Assets that need to be loaded for this page.
		 */
		$data['assets'] = array(
			'stylesheets' => array(
				'/css/gordian.css'
			)
		);
		
		$data['maintenance_message'] = "Stuff";
		
		/*
		 * Display Contents
		 */
		$this->load->view('atlas/maintenance', $data);
	}
} 
?>
