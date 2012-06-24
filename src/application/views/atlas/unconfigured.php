<?php
/**
 * The unconfigured state splash screen used to setup the system.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 2
 * @license GPL 3
 */

if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

$this->load->view('layouts/header');
echo 'Your system has not been configured.';
$this->load->view('layouts/footer');
?>