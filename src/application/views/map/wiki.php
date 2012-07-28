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

echo "<p><strong>{$wiki->Title}</strong></p>";
echo "<p>{$latlng_lbl} ({$location->Lng}, {$location->Lng})</p>";

if (count($loc_aka) > 0)
{
	echo "<p>{$aka_lbl}" . implode(',', $loc_aka) . "<p>";	
}

echo "<p>{$wiki->Content}</p>";