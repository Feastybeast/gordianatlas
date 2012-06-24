<?php
/**
 * Configuration Management Interface: Conclusion View.
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
echo 'Your system has been configured.';
$this->load->view('layouts/footer'); 
?>