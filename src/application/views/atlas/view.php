<?php
/**
 * The main view screen for the application.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 2
 * @license GPL 3
 */

if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

/*
 * Header Information Follows
 */

$this->load->view('layouts/header');	
$this->load->view('layouts/superBar');

/*
 * Map Info Row Code Follows
 */

echo '<div id="mapInfoRow" class="structuralRow">' . "\n";

echo '  <div id="contentViewport"></div>' . "\n";	
echo '  <div id="mapViewport"></div>' . "\n";	

if ($this->gordian_auth->is_logged_in())
{
  echo '<div id="addLoc">';
  echo '<a href="/map/add" id="btnAddLoc"><img src="/assets/img/add.png" width="16" height="16" alt="' . $add_button_link . '"/>';
  echo $add_button_link; 
  echo '</a>';
  echo '</div>';	
}

echo '</div>' . "\n";

/*
 * Timeline Info Row follows.
 */

echo '<div id="timelineRow" class="structuralRow">' . "\n";	

if ($this->gordian_auth->is_logged_in())
{
  echo '	<div id="addEvent">';
  echo '<a href="/timeline/add" id="btnAddEvent">';
  echo '<img src="/assets/img/add.png" width="16" height="16" alt="' . $add_button_event . '" />';
  echo $add_button_event; 
  echo '</a>';
  echo '</div>';	
}

echo '  <div id="timelineViewport"></div>' . "\n";
	
echo '</div>' . "\n";	

/*
 * Logged in controls information follows.
 */
if ($this->gordian_auth->is_logged_in())
{
	$this->load->view('atlas/controls_logged_in');	
}

/*
 * Footer information Follows.
 */

$this->load->view('layouts/footer');
?>