<?php
/**
 * Toggle Maintenance Mode Administrative view component.
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
	
/*
 * Print out widgets to manage the database state.
 */
echo form_open('administration/toggle_maintenance');
echo "<p>";
echo lang('label_admin_site_available', "site_available") . " ";
echo form_checkbox(array(
		"id" => "site_available",
		"name" => "site_available", 
		"value" => "1", 
		"checked" => false
	));
echo "</p>";

echo form_submit('IsUpdated', $this->lang->line('label_btn_maintenance_update')); 
echo form_close();

$this->load->view('layouts/footer');