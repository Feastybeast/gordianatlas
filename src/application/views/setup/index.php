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

echo heading($this->lang->line('gordian_setup_index_header'), 2);

echo $this->lang->line('gordian_setup_index_body');

echo "<p><strong>";
echo  anchor('setup/admin_account', $this->lang->line('gordian_setup_admin_link'));
echo "</strong></p>";