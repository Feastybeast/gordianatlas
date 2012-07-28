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

if ($this->gordian_auth->is_logged_in())
{
echo <<<EOF
	<div id="wiki_actions">
		<span class="wiki_button">
			<a href="/map/edit_location/{$location->Id}" class="edit_button">
				<img src="/assets/img/edit.png" width="32" height="32" alt="{$edit_lbl}" title="{$edit_lbl}"  />
				{$edit_lbl}
			</a>
		</span>
		<span class="wiki_button">
			<a href="/map/remove_location/{$location->Id}" class="remove_btn">
				<img src="/assets/img/remove.png" width="32" height="32" alt="{$remove_confirm}" title="{$remove_lbl}"  />
				{$remove_lbl}
			</a>
			<div class="dialog" title="{$remove_confirm}">{$remove_confirm}</div>			
		</span>
	</div>
EOF;
}