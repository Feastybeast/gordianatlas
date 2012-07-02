<?php
/**
 * The success screen for registering a new account.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 3
 * @license GPL 3
 */

if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

class Auth extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('gordian_auth');
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->helper('gordian_auth');
		
		$this->lang->load('gordian_auth');
		
		$this->gordian_assets->addStyleSheet('/css/gordian.css');
	}
	
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

	public function forgotten()
	{
		$this->load->view('auth/forgotten');
	}
	
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
			
			$this->gordian_auth->login($email, $password);
		}

		$this->load->view('auth/login');	
	}

	public function logout()
	{
		$this->gordian_auth->logout();
	}
	
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
				$this->load->view('auth/register_success');				
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