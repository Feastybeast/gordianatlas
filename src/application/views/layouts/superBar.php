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
?>

<div class="structuralRow" id="superBar">
	<span id="siteTitle">
		The Gordian Atlas
	</span>
	<span id="contextBar">
		<?php echo $this->load->view('widgets/searchBar/view'); ?>
	</span>
	<span id="userWidget">
		<?php echo $this->load->view('widgets/loginBadge/view'); ?>
	</span>
</div>

