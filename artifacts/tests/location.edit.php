<?php
if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

/**
 * The success screen for registering a new account.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 4
 * @license GPL 3
 */
 
public function test()
{

echo form_open('/location/edit/6');

echo '<input type="hidden" name="name" value="Somewhere in the Yucatan." />';
echo '<input type="hidden" name="lat" value="20.00" />';
echo '<input type="hidden" name="lng" value="-90.00" />';
echo '<input type="hidden" name="description" value="Theres a lot of corn to be had there." />';
echo '<input type="submit" />';

echo form_close(); 
exit();

}