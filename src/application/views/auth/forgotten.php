<?php
/**
 * Forgotten Password landing screen for glitches in JS behavior.
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
echo gordian_auth_user_widget(array(
	'header' => "Forgotten Password Recovery", 
	'forgotten' => FALSE,
	'password' => FALSE,
	'confirm' => FALSE,
	'nickname' => FALSE
));
$this->load->view('layouts/footer');