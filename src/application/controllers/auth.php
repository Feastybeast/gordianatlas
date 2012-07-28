<?php
if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

/**
 * Primary authorization component for the Gordian Atlas.
 * 
 * Handles login / logout / registration / password recovery functions.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 3
 * @license GPL 3
 */
class Auth extends CI_Controller 
{
	/**
	 * Default constructor.
	 * 
	 * Gathers a substantial amount of helpers and libraries required 
	 * for the Auth controller to function.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->library('Gordian_auth');
		$this->load->library('Session');
		$this->load->library('Form_validation');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->helper('gordian_auth');
		
		$this->lang->load('gordian_auth');
		
		$this->gordian_assets->loadDefaultAssets();
	}
	
	/**
	 * Authorization splash / routing screen.
	 */
	public function index()
	{
		if (!$this->gordian_auth->is_logged_in())
		{
			redirect('auth/login');
		}
		else 
		{
			redirect('atlas/view');
		}
	}

	/**
	 * Forgotten password management action.
	 */
	public function forgotten()
	{
		$this->load->view('auth/forgotten');
	}
	
	/**
	 * Atlas user login action.
	 * 
	 * Handles basic validation routines to ensure users are legitimate,
	 * prior to allowing users to edit system data.
	 */
	public function login()
	{
		/*
		 * Business Logic
		 */
		 $config_rules = array(
				array(
                     'field'   => 'Email', 
                     'label'   => 'Email Address', 
                     'rules'   => 'required|valid_email'
                  ),
               array(
                     'field'   => 'Password', 
                     'label'   => 'Password', 
                     'rules'   => 'required'
                  )
		 );
		 
		 $this->form_validation->set_rules($config_rules);
		
		/*
		 * Page Output
		 */
		if ($this->form_validation->run() == TRUE)
		{
			$email = $this->input->post('Email');
			$password = $this->input->post('Password');
			
			$login_successful = $this->gordian_auth->login($email, $password);
			if ($login_successful)
			{
				redirect('');
			}
			else 
			{
				$login_failed = $this->lang->line('gordian_auth_login_failed');
				$this->session->set_flashdata('message', $login_failed);
			}
		}

		$this->load->view('auth/login');	
	}

	/**
	 * Logs a user out, then redirects them to the home page with a confirmation.
	 */
	public function logout()
	{
		$this->gordian_auth->logout();
		$this->session->set_flashdata('message', $this->lang->line('gordian_auth_logout_flash'));
		redirect('');
	}
	
	/**
	 * Performs basic validation and registration behaviors for a new user account.
	 */
	public function register()
	{
		/*
		 * User is registered. NO FUNNY BUSINESS!
		 */
		if ($this->gordian_auth->is_logged_in())
		{
			redirect('user/index');
		}

		/*
		 * Setup form validation rules.
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
			
			$register_res = $this->gordian_auth->register($email, $password, $nickname);
			$login_res = $this->gordian_auth->login($email, $password);
			
			if ($register_res && $login_res)
			{				
				redirect('/');				
			}
			else 
			{
				if (!$register_res)
				{
					$register_failed = $this->lang->line('gordian_auth_register_failed');
					$this->session->set_flashdata('message', $register_failed);
					$this->load->view('auth/register');
				}
				else if (!$login_res)
				{
					$login_failed = $this->lang->line('gordian_auth_login_failed');
					$this->setssion->set_flashdata('message', $login_failed);
					redirect("/auth/login");
				}
			}
		}
		else
		{
			$this->load->view('auth/register');
		}
	}
}