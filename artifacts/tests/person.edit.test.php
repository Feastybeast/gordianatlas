<?php
if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

/**
 * Scratch test code for the personality editign screen.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 4
 * @license GPL 3
 */
 
echo form_open('/person/edit/4');

echo '<input type="hidden" name="person_name" value="Garry II" />';
echo '<input type="hidden" name="person_birth" value="03/10/1924" />';
echo '<input type="hidden" name="person_birth_loc" value="2" />';
echo '<input type="hidden" name="person_death" value="03/28/1988" />';
echo '<input type="hidden" name="person_death_loc" value="1" />';
echo '<input type="hidden" name="person_descript" value="Benny the Goldfish lived an uneventful life until he was suddenly on land. What a bummer." />';
echo '<input type="submit" />';

echo form_close();
exit(); 