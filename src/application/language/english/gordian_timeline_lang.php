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

// Errors emerging from edit attempt
$lang['gordian_timeline_error_edit_existence'] = "Unable to locate the existing event.";
$lang['gordian_timeline_error_edit_occured_on'] = "While attempting to edit an event, an invalid occurance date was provided.";
$lang['gordian_timeline_error_edit_occured_range'] = "While attempting to edit an event, an invalid occurance range was provided.";
$lang['gordian_timeline_error_edit_occured_duration'] = "While attempting to edit an event, an invalid duration was provided.";
$lang['gordian_timeline_error_edit_alias_invalid'] = "While attempting to edit an event, an invalid initial alias was provided.";
$lang['gordian_timeline_error_edit_description_invalid'] = "While attempting to edit an event, an invalid description was provided.";
$lang['gordian_timeline_error_edit_occurance_unit'] = " isn't an understood unit of time, unable to edit event.";

// AJAX messages.
$lang['gordian_timeline_ajax_title'] = "Oops!";
$lang['gordian_timeline_ajax_error'] = "We're sorry, but we're unable to load location data at this time. Please try again momentarily, or if this error continues to arise, please contact an administrator for assistance.";
$lang['gordian_timeline_ajax_aka_lbl'] = ".. also known as ";

// Time 
$lang['gordian_timeline_ajax_occured_lbl'] = "Occured ";
$lang['gordian_timeline_ajax_duration_lbl'] = "Began ";
$lang['gordian_timeline_ajax_range_lbl'] = "Circa ";

$lang['gordian_timeline_ajax_centuries'] = "Centuries";

// Labels
$lang['gordian_timeline_ajax_edit_lbl'] = "Edit this Event";
$lang['gordian_timeline_ajax_remove_lbl'] = "Remove this Event";
$lang['gordian_timeline_ajax_remove_confirm'] = "Are you sure you wish to remove this Event?";