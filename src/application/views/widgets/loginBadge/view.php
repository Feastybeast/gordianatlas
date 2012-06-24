<?php
/**
 * The centralized login/logout widget UI for the super bar for the application.
 * 
 * Remember, no processing, only decision making!
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 3
 * @license GPL 3
 */

if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}


if ($this->session->userdata('userId') == false)
{
	$this->gordian_assets->addFooterScript('/js/widgets/loginBadge/logged_out.js');	
	$this->load->view('widgets/loginBadge/logged_out');
}
else
{
	$this->gordian_assets->addFooterScript('/js/widgets/loginBadge/logged_in.js');	
	$this->load->view('widgets/loginBadge/logged_in');	
}
?>