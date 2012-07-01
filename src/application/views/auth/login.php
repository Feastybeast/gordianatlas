<?php
/**
 * The landing screen for a non-Javascript enabled browser when registering an account.
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
$this->load->view('auth/login_form');
$this->load->view('layouts/footer');