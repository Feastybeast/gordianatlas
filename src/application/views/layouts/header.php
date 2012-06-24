<?php
/**
 * Main Layout header file for the application.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 2
 * @license GPL 3
 */

if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}
?>
<!DOCTYPE HTML SYSTEM>
<html>
	<head>
		<title>The Gordian Atlas:</title>
<?php 
	foreach ($this->gordian_assets->getMetaTags() as $k => $v)
	{
				
	}			
		
	foreach ($this->gordian_assets->getHeaderScripts() as $k => $v)
	{
		echo '<script src="'.$v.'" type="text/javascript"></script>' . "\n";
		
	}			
		
	foreach ($this->gordian_assets->getStyleSheets() as $k => $v)
	{
		echo '<link rel="stylesheet" href="'.$v.'">' . "\n";
	}
?>		
	</head>
	<body>