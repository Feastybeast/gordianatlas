<?php
if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

/**
 * The common tabbed footer for Atlas WikiPages.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 4
 * @license GPL 3
 */

echo '<link rel="stylesheet" href="/css/gordian.css">';
echo '<div class="wiki_title" id="' . $title_id . '">' . $title . '</div>';

/*
 * Primary Pane
 */ 
echo '<div class="wiki_pane" id="wiki_entry">' . $block_content . '</div>';

/*
 * Related Content : Concepts
 */ 
echo '<div class="wiki_pane" id="wiki_concepts">';
echo $block_concepts;

if ($this->gordian_auth->is_logged_in() && in_array('manage', $display_tabs))
{
	echo '<p>';
	echo '	<span class="wiki_button">';
	echo '	<img src="/assets/img/associate.png" width="32" height="32" alt="' . $edit_lbl . '" title="' . $edit_lbl . '"  />';
	echo '&nbsp; <a href="/' . $record_type . '/related_concepts/' . $record_id . '" class="relate_btn">' . $concepts_relate_lbl . '</a>';
	echo '</span>';
	
	echo '	<span class="wiki_button">';
	echo '	<img src="/assets/img/add.png" width="32" height="32" alt="' . $add_lbl . '" title="' . $add_lbl . '"  />';
	echo '&nbsp; <a href="/' . $record_type . '/add_concept/' . $record_id . '" class="edit_btn">' . $concepts_add_lbl . '</a>';
	echo '</span>';

	echo '</p>';
}

echo '</div>';

/*
 * Related content: Events
 */ 
echo '<div class="wiki_pane" id="wiki_events">';
echo $block_events;

if ($this->gordian_auth->is_logged_in() && in_array('manage', $display_tabs))
{
	echo '<p>';
	echo '	<span class="wiki_button">';
	echo '	<img src="/assets/img/associate.png" width="32" height="32" alt="' . $edit_lbl . '" title="' . $edit_lbl . '"  />';
	echo '&nbsp; <a href="/' . $record_type . '/related_events/' . $record_id . '" class="relate_btn">' . $events_relate_lbl . '</a>';
	echo '</span>';	
	echo '</p>';
}

echo '</div>';

/*
 * Related content: Locations
 */ 
echo '<div class="wiki_pane" id="wiki_locations">';
echo $block_locations;

if ($this->gordian_auth->is_logged_in() && in_array('manage', $display_tabs))
{
	echo '<p>';
	echo '	<span class="wiki_button">';
	echo '	<img src="/assets/img/associate.png" width="32" height="32" alt="' . $edit_lbl . '" title="' . $edit_lbl . '"  />';
	echo '&nbsp; <a href="/' . $record_type . '/related_locations/' . $record_id . '" class="relate_btn">' . $locations_relate_lbl . '</a>';
	echo '</span>';
	echo '</p>';
}

echo '</div>';

/*
 * Related content: Personalities
 */ 
echo '<div class="wiki_pane" id="wiki_personalities">';
echo $block_personalities;

if ($this->gordian_auth->is_logged_in() && in_array('manage', $display_tabs))
{
	echo '<p>';
	echo '	<span class="wiki_button">';
	echo '		<img src="/assets/img/associate.png" width="32" height="32" alt="' . $edit_lbl . '" title="' . $edit_lbl . '"  />';
	echo '&nbsp; <a href="/' . $record_type . '/related_personalities/' . $record_id . '" class="relate_btn">' . $personalities_relate_lbl . '</a>';
	echo '</span>';

	echo '	<span class="wiki_button">';
	echo '		<img src="/assets/img/add.png" width="32" height="32" alt="' . $add_lbl . '" title="' . $add_lbl . '"  />';
	echo '&nbsp; <a href="/' . $record_type . '/add_personalities/' . $record_id . '" class="edit_btn">' . $personalities_add_lbl . '</a>';
	echo '</span>';
	
	echo '</p>';
}

echo '</div>';


/*
 * The Wiki Management tab follows.
 */
if ($this->gordian_auth->is_logged_in() && in_array('manage', $display_tabs))
{
// WikiPage Management
	echo '<div class="wiki_pane" id="wiki_manage">';
	echo '	<span class="wiki_button">';
	echo '				<img src="/assets/img/edit.png" width="32" height="32" alt="' . $edit_lbl . '" title="' . $edit_lbl . '"  />';
	echo '&nbsp; <a href="/' . $record_type . '/edit/' . $record_id . '" class="edit_btn">' . $edit_lbl . '</a>';
	echo '		</span>';
	echo '		<span class="wiki_button">';
	echo '				<img src="/assets/img/remove.png" width="32" height="32" alt="' . $remove_lbl . '" title="' . $remove_lbl . '"  />';
	echo '&nbsp; <a href="/' . $record_type . '/remove/' . $record_id . '" class="remove_btn">' . $remove_lbl . '</a>';
	echo '	</span>';
	echo '</div>';
}

/*
 * The Tab Tray Follows 
 */
echo '<div id="wiki_tab_tray">';
echo (in_array('entry', $display_tabs)) 
	? '<span class="wiki_tab"><a href="/'.$record_type.'/entry/'  . $record_id .'">' . $entry_lbl . '</a></span>' : '';
echo (in_array('concept', $display_tabs)) 
	? '<span class="wiki_tab"><a href="/'.$record_type.'/concepts/'.$record_id.'">' . $concept_lbl . '</a></span>' : '';
echo (in_array('event', $display_tabs)) 
	? '<span class="wiki_tab"><a href="/'.$record_type.'/events/'.$record_id.'">' . $event_lbl . '</a></span>' : '';
echo (in_array('location', $display_tabs)) 
	? '<span class="wiki_tab"><a href="/'.$record_type.'/locations/'.$record_id.'">' . $location_lbl . '</a></span>' : '';
echo (in_array('personality', $display_tabs)) 
	? '<span class="wiki_tab"><a href="/'.$record_type.'/personalities/'.$record_id.'">' . $personality_lbl . '</a></span>' : '';
echo ($this->gordian_auth->is_logged_in() && in_array('manage', $display_tabs)) 
	? '<span class="wiki_tab"><a href="/'.$record_type.'/manage/'.$record_id.'">' . $manage_lbl . '</a></span>' : '';
echo '</div>';
?>