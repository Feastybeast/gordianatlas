<?php
/**
 * Initial Timeline setup UI.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 3
 * @license GPL 3
 */

if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

echo "<blockquote>" . $this->lang->line('gordian_setup_timeline_body') . "</blockquote>";

echo $this->gordian_timeline->ui_create_edit($widget_config);