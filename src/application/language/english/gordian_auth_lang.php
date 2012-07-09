<?php
if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

/**
 * This file contains localization strings supporting the behavior of the Gordian Authorization component.
 * 
 * The Gordian Authorization class is heavily patterned after 
 * the IonAuth framework, developed by Ben Edmunds. 
 * Documentation and contact information for both Ben and 
 * IonAuth can be located at <http://benedmunds.com/ion_auth/>.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 3
 * @license GPL 3
 */

/*
 * Default Values
 */
$lang['gordian_auth_default_btn'] = "Go!";
$lang['gordian_auth_default_hdr'] = "Please update header value!";

$lang['gordian_auth_widget_header'] = "Please correct the following errors:";

$lang['gordian_auth_nickname_notice'] = "(Defaults to your email address, if blank.)";

$lang['gordian_auth_email_label'] = "Email";
$lang['gordian_auth_nickname_label'] = "Nickname";
$lang['gordian_auth_password_label'] = "Password";
$lang['gordian_auth_confirm_label'] = "Confirm";


/*
 * 
 */
$lang['gordian_auth_label_endcap'] = ":";
$lang['gordian_auth_label_email'] = "Email Address";
$lang['gordian_auth_label_password'] = "Password";


/*
 * Login Related Strings
 */
$lang['gordian_auth_login_btn'] = "Log in to Account";
$lang['gordian_auth_login_hdr'] = "Log in to Account";
$lang['gordian_auth_login_lnk'] = "Need to login to your account?";

$lang['gordian_auth_register_failed'] = "Unable to register your account, please verify credentials are unique.";
$lang['gordian_auth_register_defaults_failed'] = "Unable to set default group memberships for registering, the administrators have been contacted for you.";

/*
 * Logout Related Strings
 */
$lang['gordian_auth_logout_hrd'] = "Are you sure you want to log out?";
$lang['gordian_auth_logout_btn'] = "Log out of account";
$lang['gordian_auth_logout_lnk'] = "Log out of account";
$lang['gordian_auth_logout_lnk_short'] = "Log out";
$lang['gordian_auth_logout_flash'] = "You have been logged out.";

/*
 * Password Recovery Related Strings
 */
$lang['gordian_auth_recovery_btn'] = "Recover Account Information";
$lang['gordian_auth_recovery_lnk'] = "Forgotten your password?";
$lang['gordian_auth_recovery_hdr'] = "Recover Account Password";

/*
 * Registration Related Strings
 */
$lang['gordian_auth_register_btn'] = "Register Account";
$lang['gordian_auth_register_lnk'] = "Need to register an account?";
$lang['gordian_auth_register_lnk_short'] = "Register account";
$lang['gordian_auth_register_hdr'] = "Register a new account";

$lang['gordian_auth_register_failed'] = "Registration failed, please try again.";

 /*
  * Update Related Strings
  */
$lang['gordian_auth_update_hdr'] = "Update Account Details";
$lang['gordian_auth_update_btn'] = "Update Account";