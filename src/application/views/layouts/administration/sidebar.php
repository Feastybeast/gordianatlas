<?php
/**
 * Sidebar navigation bar component for the administrative section of the site.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 2
 * @license GPL 3
 */

if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}
?>
<div style="width: 300px; float: left;">
	<h3>Welcome to the Administrator</h3>
	<ul>
		<li><?php echo anchor('administration/toggle_maintenance', $this->lang->line('label_link_site_maintenance')); ?></li>
	</ul>
</div>