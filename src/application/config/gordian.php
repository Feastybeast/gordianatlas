<?php
/**
 * Configured Gordian Atlas database settings, $db is inaccessable to us.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 3
 * @license GPL 3
 */

if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

$config['gordian_JQ'] = 'https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js';
$config['gordian_JQUI'] = 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js';
$config['gordian_JQUI_CSS'] = 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css';

$config['gordian_db_schema'] = 'gordianatlas';