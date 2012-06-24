<?php
/**
 * The main view screen for the application administration controller.
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
$this->load->view('layouts/administration/sidebar');
echo "Here is the index file.";
$this->load->view('layouts/footer');
?>