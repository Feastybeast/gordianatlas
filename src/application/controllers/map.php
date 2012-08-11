<?php
if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

/**
 * This controller manages AJAX request data for the map widget of the Gordian Atlas.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 2
 * @license GPL 3
 */
class Map extends GA_Controller
{
	/**
	 * Default Constructor.
	 */
	function __construct()
	{
		parent::__construct();

		$this->load->library('Gordian_map');
	}
	
	/**
	 * Primary action to load JSON data regarding the map out of the database.
	 */
	public function view()
	{
		$data['jsonData'] = $this->gordian_map->load(1);
		
		$this->load->view("map/view", $data);
	}
}