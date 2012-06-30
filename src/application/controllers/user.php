<?php
/**
 * This controller is responsible for user management for the Gordian Atlas.
 * 
 * Tasks include registration, login / logout behaviors and so forth.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 2
 * @license GPL 3
 */

if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

class User extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->gordian_assets->addStyleSheet('/css/gordian.css');
		$this->load->model('User_model');
	}
	
	public function view()
	{
		$data = array();		
		$this->load->view('user/view', $data);
	}
	
	public function index()
	{
		redirect('user/view');
	}
	
	/**
	 * 
	 */
	public function register()
	{
		/*
		 * User is registered. NO FUNNY BUSINESS!
		 */
		if (array_key_exists('userId', $this->session->all_userdata()))
		{
			redirect('user/edit');
		}
		
		/*
		 * We'll be making heavy use of form validation here ...
		 */
		$this->load->library('form_validation');
		$this->lang->load('labels');
		
		/*
		 * Preferred Labeling for this screen.
		 */
		$data['buttonLabel'] = $this->lang->line('label_btn_register');
		
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
		
		if ($this->form_validation->run() == FALSE)
		{	
			$this->load->view('user/register', $data);	
		}
		else
		{
			$this->load->view('user/register_success', $data);
		}
	}
}