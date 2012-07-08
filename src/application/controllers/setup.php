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
		$this->gordian_assets->loadDefaultAssets();
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
		 * Prepare configuration details for the page
		 */
		$this->load->helper('gordian_auth');
		$this->load->helper('html');
		$this->load->library('form_validation');

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
			
			$register_res = $this->gordian_auth->register($email, $password, $nickname);
			$login_res = $this->gordian_auth->login($email, $password);
			
			if ($register_res && $login_res)
			{				
				redirect('/setup/timeline', 'refresh');				
			}
			else 
			{
				if (!$register_res)
				{
					$register_failed = $this->lang->line('gordian_auth_register_failed');
					$this->session->set_flashdata('message', $register_failed);
				}
				else if (!$login_res)
				{
					$login_failed = $this->lang->line('gordian_auth_login_failed');
					$this->setssion->set_flashdata('message', $login_failed);
				}
			}			
		}
		
		/*
		 * Output Details
		 */
		$this->load->view('layouts/header');
		$this->load->view('setup/admin_account', $data);
		$this->load->view('layouts/footer');
	}
}
