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
}