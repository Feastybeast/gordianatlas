<?php
/**
 * This controller manages administrative aspects of the Gordian Atlas.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 2
 * @license GPL 3
 */

if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}
 
class Administration extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->gordian_assets->addStyleSheet('/css/gordian.css');
	}

	public function index()
	{		
		$this->load->view('administration/index');	
	}
	
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
