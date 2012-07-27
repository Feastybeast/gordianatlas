<?php
/**
 * The success screen for registering a new account.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 3
 * @license GPL 3
 */

if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

$lang['gordian_timeline_heading'] = "Timeline Details";
$lang['gordian_timeline_title_label'] = "Timeline Title:";
$lang['gordian_timeline_description_label'] = "Timeline Description:";
$lang['gordian_timeline_button'] = "Go!";

// Errors emerging from the create attempt.
$lang['gordian_timeline_error_add_duplicate'] = "While attempting to add an event, a duplicate was located.";
$lang['gordian_timeline_error_add_occured_on'] = "While attempting to add an event, an invalid occurance date was provided.";
$lang['gordian_timeline_error_add_occured_range'] = "While attempting to add an event, an invalid occurance range was provided.";
$lang['gordian_timeline_error_add_occured_duration'] = "While attempting to add an event, an invalid duration was provided.";
$lang['gordian_timeline_error_add_alias_invalid'] = "While attempting to add an event, an invalid initial alias was provided.";
$lang['gordian_timeline_error_add_description_invalid'] = "While attempting to add an event, an invalid description was provided.";
$lang['gordian_timeline_error_add_occurance_unit'] = " isn't an understood unit of time, unable to add event.";