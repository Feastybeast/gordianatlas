<?php
if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

/**
 * Standard Timeline wiki management screen.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 4
 * @license GPL 3
 */

echo "<p><strong>{$wiki->Title}</strong></p>";

if (count($evt_aka) > 0)
{
	echo "<p>{$aka_lbl}" . implode(',', $loc_aka) . "<p>";	
}

echo $timestamp;


echo "<p>{$wiki->Content}</p>";

if ($this->gordian_auth->is_logged_in())
{
echo <<<EOF
	<div id="wiki_actions">
		<span class="wiki_button">
			<a href="/timeline/edit_event/{$event->IdEvent}" class="edit_button">
				<img src="/assets/img/edit.png" width="32" height="32" alt="{$edit_lbl}" title="{$edit_lbl}"  />
				{$edit_lbl}
			</a>
		</span>
		<span class="wiki_button">
			<a href="/timeline/remove_event/{$event->IdEvent}" class="remove_btn">
				<img src="/assets/img/remove.png" width="32" height="32" alt="{$remove_confirm}" title="{$remove_lbl}"  />
				{$remove_lbl}
			</a>
			<div class="dialog" title="{$remove_confirm}">{$remove_confirm}</div>
		</span>
	</div>
EOF;
}