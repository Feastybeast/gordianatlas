<?php
/**
 * The main search UI for the application super bar.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 3
 * @license GPL 3
 */

if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

echo form_open();
echo "Search for Timelines here:";
echo form_input('searchFor', 'search for timelines here.');
echo form_close();
?>