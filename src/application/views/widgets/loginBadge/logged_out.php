<?php
/**
 * This is the logged out view of the loginBadge widget.
 * 
 * By indicating either login credentials or an interest in registering a new account, 
 * a user takes the first step to participating on the site.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 3
 * @license GPL 3
 */

if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

echo form_open('auth/login');
echo form_input('Email', 'Enter your username...');
echo ' '; 
echo form_input('Password', 'and password...');
echo ' '; 
echo form_submit('login', 'Login');
echo ' '; 
echo anchor('/auth/register', $superbar_link_string, array("class" => "sub"));
echo form_close();