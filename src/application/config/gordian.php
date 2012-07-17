<?php
/**
 * Main gordian atlas configuration settings file. 
 * 
 * !! DO NOT TOUCH UNLESS YOU KNOW WHAT YOU ARE DOING. !!
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 3
 * @license GPL 3
 */

if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

// Javascript config locations - served via CDN.
$config['gordian_JQ'] = 'https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js';
$config['gordian_JQUI'] = 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js';
$config['gordian_JQUI_CSS'] = 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css';

// Primary URLs used throughout the application.
$config['gordian_uri_primary'] = 'atlas/view';
$config['gordian_uri_maint'] = 'atlas/maintenance';