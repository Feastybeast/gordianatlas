<?php
/**
 * This controller manages AJAX request data for the map widget of the Gordian Atlas.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 2
 * @license GPL 3
 */

if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

class Map extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function view()
	{
		$this->load->view("map/view");
	}
}