<?php
if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

/**
 * This controller manages administrative aspects of the Gordian Atlas.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 2
 * @license GPL 3
 */
class Administration extends CI_Controller
{
	/**
	 * Default constructor.
	 * 
	 * Loads standard assets before handling other actions.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->gordian_assets->loadDefaultAssets();
	}

	/**
	 * Loads the administrative splash screen.
	 */
	public function index()
	{		
		$this->load->view('administration/index');	
	}
	
	/**
	 * This action manages the maintenance state of the Atlas.
	 */
	public function toggle_maintenance()
	{ 
		/*
		 * Load Libraries required for this page.
		 */
		 $this->load->helper('form');
		 $this->load->helper('language');
		 $this->lang->load('labels');
		
		/*
		 * Query the database and store the information in an array 
		 * accessable to the view.
		 */
		$query = $this->db->get('GordianConfig', 1);
		$data['dbState'] = $query->row();

		$this->load->view('administration/toggle_maintenance', $data);			
	}
} 
?>
