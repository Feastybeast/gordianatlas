<?php
/**
 * The success screen for registering a new account.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 3
 * @license GPL 3
 */

if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

echo "<blockquote>" . $this->lang->line('gordian_setup_admin_body') . "</blockquote>";

echo gordian_auth_user_widget($user_widget_config);