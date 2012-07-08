<?php
/**
 * The landing screen for a non-Javascript enabled browser when logging into an account.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 3
 * @license GPL 3
 */

if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

$this->load->view('layouts/header');
echo gordian_auth_user_widget(array('header' => "Register a new account", 'register' => FALSE));
$this->load->view('layouts/footer');