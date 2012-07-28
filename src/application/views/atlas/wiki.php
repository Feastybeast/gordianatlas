<?php
if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

/**
 * A splash scren for the biography window in the event no data is selected.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 4
 * @license GPL 3
 */

echo <<<EOF
	<h3>{$splash_header}</h3>
	
	<p>{$splash_content}</p>
EOF;
?>