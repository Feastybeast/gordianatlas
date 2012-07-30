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

echo "<p><strong id='loc_name_val'>{$wiki->Title}</strong>";
if (count($loc_aka) > 0)
{
	echo "<br />&nbsp;&nbsp;{$aka_lbl}" . implode(', ', $loc_aka);	
}
echo "</p>";

echo "<p>{$latlng_lbl} (<span id='lat_val'>{$location->Lat}</span>, <span id='lng_val'>{$location->Lng}</span>)</p>";





echo "<p id='loc_descript_val'>{$wiki->Content}</p>";

if ($this->gordian_auth->is_logged_in())
{
echo <<<EOF
	<div id="wiki_actions">
		<span class="wiki_button">
			<a href="/map/edit_location/{$location->Id}" class="edit_btn">
				<img src="/assets/img/edit.png" width="32" height="32" alt="{$edit_lbl}" title="{$edit_lbl}"  />
				{$edit_lbl}
			</a>
		</span>
		<span class="wiki_button">
			<a href="/map/remove_location/{$location->Id}" class="remove_btn">
				<img src="/assets/img/remove.png" width="32" height="32" alt="{$remove_confirm}" title="{$remove_lbl}"  />
				{$remove_lbl}
			</a>
		</span>
	</div>
EOF;
}