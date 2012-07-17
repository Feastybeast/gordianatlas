<?php
if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

/**
 * The setup controller. Run once to establish the baseline data required for the 
 * Gordian Atlas to function.
 * 
 * Basic execution path:
 * 	index() -> admin_account() -> timeline() -> confirm().
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 3
 * @license GPL 3
 */
class Setup extends CI_Controller
{
	/**
	 * Default constructor.
	 * 
	 * Ensures that the GordianConfig system has not been setup prior to 
	 * user interaction, to guarantee previous installations aren't 
	 * trampled.
	 */
	public function __construct()
	{
		parent::__construct();
		
		/*
		 * Verify that a user should be here ...
		 */
		$this->load->library('Gordian_state');
		
		if ($this->gordian_state->is_setup())
		{
			redirect('');
		}
		
		/*
		 * They should be here ... continue processing.
		 */		
		$this->lang->load('gordian_setup');
		
		$this->load->helper('html');
		$this->load->library('form_validation');
		
		$this->gordian_assets->loadDefaultAssets();
	}
	
	/**
	 * Administrative account setup behaviors
	 * 
	 * Validates and creates the initial administrative user account 
	 * prior to further configuration tasks.
	 */
	public function admin_account()
	{		
		/*
		 * Prepare configuration details for the page
		 */
		$this->load->helper('gordian_auth');

		$data['user_widget_config'] = array(
			'header' => $this->lang->line('gordian_setup_admin_link'),
			'forgot' => false,
			'register' => false,
			'login' => false,
			'button' => $this->lang->line('gordian_setup_admin_link')
		);
		
		/*
		 * Form Validation Behaviors
		 */
		$config_rules = array(
 				array(
                     'field'   => 'Email', 
                     'label'   => 'Email', 
                     'rules'   => 'required|is_unique[User.Email]|valid_email'
                  ),
               array(
                     'field'   => 'Password', 
                     'label'   => 'Password', 
                     'rules'   => 'required'
                  ),
				array(
					'field' => 'Nickname',
					'label'  => 'Nickname',
					'rules' => ''),
               array(
                     'field'   => 'Confirm', 
                     'label'   => 'Password Confirmation', 
                     'rules'   => 'required|matches[Password]'
                  )			
		);
		 
		$this->form_validation->set_rules($config_rules); 
		 
		if ($this->form_validation->run() == TRUE)
		{
			$email = $this->input->post('Email');
			$nickname = $this->input->post('Nickname');
			$password = $this->input->post('Password');
				
			// Initially register the user, period.
			$register_res = $this->gordian_auth->register($email, $password, $nickname);			
			// Flag the newly registered account as an admin.
			$admin_res = $this->gordian_auth->set_admin_rights($register_res, true);
			/*
			 * Log them into the website for ease of use later on.
			 * (Note: This will silently fail if already logged in.)
			 */
			$login_res = $this->gordian_auth->login($email, $password);
			
			if ($register_res && $admin_res && $login_res)
			{				
				redirect('setup/timeline');				
			}
			else 
			{
				$op_message = "";
				
				if (!$register_res)
				{
					$op_message .= $this->lang->line('gordian_auth_register_failed') . "\n";
				}
				
				if (!$admin_res)
				{
					$op_message .= $this->lang->line('gordian_auth_admin_toggle_failed') . "\n";
				}
				
				if (!$login_res)
				{
					$op_message .= $login_failed = $this->lang->line('gordian_auth_login_failed');
				}
				
				$this->session->set_flashdata('message', $op_message);
				redirect('setup/admin_account');			
			}			
		}
		
		/*
		 * Output Details
		 */
		$this->load->view('layouts/header');
		$this->load->view('setup/admin_account', $data);
		$this->load->view('layouts/footer');
	}
	
	/**
	 * Setup splash / license agreement screen prior to installation.
	 */
	public function index()
	{
		$this->load->view('layouts/header');
		$this->load->view('setup/index');
		$this->load->view('layouts/footer');
	}
	
	/**
	 * Finalize the installation of the Atlas.
	 */	
	 public function finalize()
	 {	
	 	// Default to having a version of 1.
	 	$this->gordian_state->set_version(1);
	 	// Remove any maintenance indication files just to be completely certain.
	 	$this->gordian_state->remove_maintenance();
	 	
	 	$this->session->set_flashdata('title', $this->lang->line('gordian_setup_finalize_header'));
		$this->session->set_flashdata('message', $this->lang->line('gordian_setup_finalize_body'));
	
		redirect('');
	 }
	 
	 /**
	  * Initial Timeline and Group setup for the Atlas.
	  */
	 public function timeline()
	 {
	 	$this->load->library('Gordian_group');
	 	$this->load->library('Gordian_timeline');
	 	
	 	/*
	 	 * Basic configuration rules. 
	 	 */
 		$config_rules = array(
 				array(
                     'field'   => 'Title', 
                     'label'   => 'Timeline title', 
                     'rules'   => 'required|is_unique[Timeline.Title]'
                  ),
               array(
                     'field'   => 'Description', 
                     'label'   => 'Timeline description', 
                     'rules'   => 'required'
                  )			
		);
		 
		$this->form_validation->set_rules($config_rules); 
	 	
	 	if ($this->form_validation->run() == TRUE)
		{
			$title = $this->input->post('Title');
			$description = $this->input->post('Description');
			
			// TODO: Likely need to provide new config information later ...
			$new_group = $this->gordian_group->create('Default Group', 'This is the default group');

			$admin_added = $this->gordian_group->administrate($new_group, $this->session->userdata('gordian_id'));
			
			if (!$new_group || !$admin_added)
			{
				$create_message = $this->lang->line('gordian_setup_timeline_create_failed');
				$this->session->set_flashdata('message', $create_message);
				redirect('setup/timeline');				
			}
			else
			{
				$new_timeline = $this->gordian_timeline->create($new_group, $title, $description);
				
				if (is_numeric($new_timeline))
				{
					redirect('setup/finalize');
				}
				else
				{
					$title = $this->lang->line('gordian_setup_timeline_flash_title'); 
					$body = $this->lang->line('gordian_setup_timeline_flash_message'); 
			
					$this->session->set_flashdata('title', $title);
					$this->session->set_flashdata('message', $body);					
				}
			}		
		}
	 	
	 	/*
	 	 * Default values are fine for the widget.
	 	 */ 
		$data['widget_config'] = array(); 
	 		 	
	 	$this->load->view('layouts/header');
	 	$this->load->view('setup/timeline', $data);
	 	$this->load->view('layouts/footer');
	 }
}
