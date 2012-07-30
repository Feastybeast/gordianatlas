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

echo "<p><strong id='evt_name_val'>{$wiki->Title}</strong></p>";

if (count($evt_aka) > 0)
{
	echo "<p>{$aka_lbl}" . implode(',', $loc_aka) . "<p>";	
}

echo $timestamp;


echo "<p id='evt_descript_val'>{$wiki->Content}</p>";

if ($this->gordian_auth->is_logged_in())
{
echo <<<EOF
	<div id="wiki_actions">
		<span class="wiki_button">
			<a href="/timeline/edit_event/{$event->IdEvent}" class="edit_btn">
				<img src="/assets/img/edit.png" width="32" height="32" alt="{$edit_lbl}" title="{$edit_lbl}"  />
				{$edit_lbl}
			</a>
		</span>
		<span class="wiki_button">
			<a href="/timeline/remove_event/{$event->IdEvent}" class="remove_btn">
				<img src="/assets/img/remove.png" width="32" height="32" alt="{$remove_confirm}" title="{$remove_lbl}"  />
				{$remove_lbl}
			</a>
		</span>
	</div>
	<span class="hidden" id="evt_range_val">{$event->OccuredRange}</span>
	<span class="hidden" id="evt_duration_val">{$event->OccuredDuration}</span>
	<span class="hidden" id="evt_units_val">{$event->OccuredUnit}</span>
EOF;

$formatted_date = DateTime::createFromFormat('Y-m-d', $event->OccuredOn);
echo '	<span class="hidden" id="evt_occurance_val">' . $formatted_date->format('m/d/Y') . '</span>';
}