<?php
if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

/**
 * This controller is responsible for user management for the Gordian Atlas.
 * 
 * Tasks include registration, login / logout behaviors and so forth.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 2
 * @license GPL 3
 */
class User extends CI_Controller 
{
	/**
	 * Default constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->gordian_assets->loadDefaultAssets();
		
		$this->load->library('Gordian_auth');
	}

	/**
	 * This is the action end users access to edit their nickname and password for the system.
	 */
	public function edit()
	{
		/*
		 * Load the gordian auth helper to ensure access. 
		 */
		 if (!$this->gordian_auth->is_logged_in())
		 {
		 	redirect('');
		 }
		 
		$this->load->helper('gordian_auth');
		$this->load->library('form_validation');
		
		/*
		 * Form Validation code goes here.
		 */
		$config_rules = array(
 				array(
                     'field'   => 'Email', 
                     'label'   => 'Email', 
                     'rules'   => 'valid_email'
                  ),
				array(
					'field' => 'Nickname',
					'label'  => 'Nickname',
					'rules' => 'required')
		); 
		 
		$this->form_validation->set_rules($config_rules);
		
		if ($this->form_validation->run() == TRUE)
		{
			$nickname = $this->input->post('Nickname');
			
			$this->gordian_auth->edit_user($nickname);
		}
		else 
		{
			$this->session->set_flashdata('message', "You need to put stuff in here.");
		}
		
		/*
		 * Display code
		 */
		$data['widget_config'] = array(
			'header' => "Edit user account",
			'forgot' => false,
			'register' => false,
			'login'=> false,
			'button' => "Update User Information"
		);
		
		$this->load->view('layouts/header');
		$this->load->view('user/edit', $data);
		$this->load->view('layouts/footer');
	}	
	
	/**
	 * 
	 */
	public function view()
	{
		$data = array();
		$this->load->view('layouts/header');
		$this->load->view('user/view', $data);
		$this->load->view('layouts/footer');
	}
	
	/**
	 * The index method, which routes to the preferred view method.
	 */
	public function index()
	{
		redirect('user/view');
	}	
}