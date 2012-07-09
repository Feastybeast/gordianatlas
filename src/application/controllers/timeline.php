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
	}
	
	/**
	 * Primary action to load JSON data from the database.
	 */
	function view()
	{
		$this->load->view("timeline/view");
	}
}