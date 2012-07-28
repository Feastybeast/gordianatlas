<?php
/**
 * Main search and user management component of the Atlas App.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 3
 * @license GPL 3
 */

if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

echo '<div class="structuralRow" id="superBar">';

if (strpos(uri_string(), 'maintenance') > 0)
{
	echo '<span id="contextBar">The Gordian Atlas is currently undergoing maintenance</span>' . "\n";
}
else
{
	echo '<span id="siteTitle">The Gordian Atlas</span>' . "\n";
	
	echo $this->load->view('widgets/searchBar/view');
	
	echo '<span id="userWidget">' . "\n";
	echo $this->load->view('widgets/loginBadge/view');
	echo '</span>' . "\n";	
}

echo '</div>' . "\n";