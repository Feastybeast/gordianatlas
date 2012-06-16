<?php
/*
 * Created on Jun 15, 2012
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
class Administration extends CI_Controller
{
	public function index()
	{
		$data['assets'] = array(
			'stylesheets' => array(
				'/css/gordian.css'
			)
		);
		
		$this->load->view('administration/index', $data);	
	}
	
	public function toggle_maintenance()
	{ 
		/*
		 * Load Libraries required for this page.
		 */
		 $this->load->helper('form');
		
		
		$data['assets'] = array(
			'stylesheets' => array(
				'/css/gordian.css'
			)
		);
		
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
