<?php
	$this->load->view('layouts/header');
	$this->load->view('layouts/administration/sidebar'); 
	
	/*
	 * Print out widgets to manage the database state.
	 */
	echo form_open('administration/toggle_maintenance');
	echo "<p>";
	echo form_label("Is Site Available? ", "site_available");
	echo form_checkbox("site_available", "1", false);
	echo "</p>";
	
	echo form_submit('IsUpdated', "Update Maintenance State"); 
	echo form_close();
	
	$this->load->view('layouts/footer');
?>