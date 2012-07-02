<?php
/**
 * The setup controller. Run once to establish the baseline data required for the 
 * Gordian Atlas to function.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 3
 * @license GPL 3
 */

if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

class Setup extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		 $online = $this->db->get('GordianConfig', 1);

		
		if ($online->num_rows() > 0)
		{
			redirect('');
		}
		
		/*
		 * They should be here ... continue processing.
		 */		
		$this->lang->load('gordian_setup');

		$this->gordian_assets->addStyleSheet('css/gordian.css');
	}
	
	public function index()
	{
		$this->load->helper('html');
		
		$this->load->view('layouts/header');
		$this->load->view('setup/index');
		$this->load->view('layouts/footer');
	}
	
	public function admin_account()
	{		
		/*
		 * Output Results
		 */
		$this->load->helper('gordian_auth');

		$data['user_widget_config'] = array();

		$this->load->view('setup/admin_account', $data);
	}
}
