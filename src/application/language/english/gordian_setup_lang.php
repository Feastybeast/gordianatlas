<?php
/**
 * Strings regarding the setup of the Gordian Atlas.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 3
 * @license GPL 3
 */

if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

/*
 * Splash / Index Screen Related Strings
 */
$lang['gordian_setup_index_header'] = "Welcome to the Gordian Atlas";

$lang['gordian_setup_index_body'] = "<p>Greetings fellow historian!</p>"
	. "We're thrilled that you've decided to deepen your knowledge of the past using the Gordian Atlas."
	. " <br /> You will need to answer a few questions relating to the theme your Atlas and your "
	. "preferred default configuration to continue.";

/*
 * Administrator Account Related Strings
 */
$lang['gordian_setup_admin_link'] = "Setup Administrator Account";
$lang['gordian_setup_admin_body'] = "Your first step to configure the Gordian Atlas " .
	"is to setup the main site administrator account. Please enter the essential login information below." .
	"The system will verify that you've entered a valid email and password combination prior to continuing " .
	"to the next screen., where you will configure the Atlas' first timeline.";

/*
 * Finalization Related Strings
 */
 $lang['gordian_setup_finalize_header'] = "History awaits!";
 
$lang['gordian_setup_finalize_body'] = "<p>Your atlas is ready!</p>"
	. "The default administrator account has been setup, and a  default timeline has been prepared."
	. "The Atlas is now ready to use, if any configuration is now necessary, please look to the  "
	. " Administration Control Panel for assistance!";

/*
 * Timeline related strings
 */
$lang['gordian_setup_timeline_link'] = "Setup Timeline";
$lang['gordian_setup_timeline_body'] = "Setup Timeline";

$lang['gordian_setup_timeline_body'] = "Please provide basic information for the default timeline in your Atlas.";

$lang['gordian_setup_timeline_flash_title'] = 'Timeline Creation Error';
$lang['gordian_setup_timeline_flash_message'] = 'Unable to create timeline, please try again.';

/*
 * Support Strings
 */
$lang['gordian_setup_cannot_write'] = "The Gordian Atlas must be able to write to it's parent directory for maintenance status";
$lang['gordian_setup_version_absent'] = "The Gordian Atlas cannot determine it's current version. Is the version file present?";
$lang['gordian_setup_initial_message'] = "The Atlas is currently offline for configuration.";