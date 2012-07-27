<?php
/**
 * Success notice for adding new event.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 3
 * @license GPL 3
 */

if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

echo "TRUE";

echo " > ";
echo $occured_on;
echo " | ";
echo $occured_range;
echo " | ";
echo $occured_duration;
echo " | ";
echo $occured_unit;
echo " | ";
echo $initial_alias;
echo " | ";
echo $description;
echo " < ";