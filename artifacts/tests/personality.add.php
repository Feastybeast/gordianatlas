<?php
if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

/**
 * Test code for the add personality component.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 4
 * @license GPL 3
 */
 
echo form_open('/event/add_personality/1');
echo form_hidden('dob', '01/13/2012');
echo form_hidden('dod', '01/25/2012');
echo form_hidden('lob', '1');
echo form_hidden('lod', '2');
echo form_hidden('name', 'Benny the Goldfish');
echo form_hidden('biography', 'Benny the Goldfish lived an uneventful life until he was suddenly on land.');
echo form_submit("test", "Yobs");
echo form_close();
exit(); 