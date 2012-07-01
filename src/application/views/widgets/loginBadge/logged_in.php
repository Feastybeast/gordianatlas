<?php
/**
 * The logged in state of the loginBadge site widget.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 3
 * @license GPL 3
 */

if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

echo "We're totally logged in.'";
echo anchor('/auth/logout', $superbar_link_string, array("class" => "sub"));