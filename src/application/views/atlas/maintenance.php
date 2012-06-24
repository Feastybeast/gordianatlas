<?php
/**
 * Maintenance Notice View for the Application.
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
echo $maintenanceMessage;
$this->load->view('layouts/footer');
?>