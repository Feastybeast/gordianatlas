<?php 
/**
 * Main layout footer file.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 2
 * @license GPL 3
 */

if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

echo '		<div class="sub">&copy; <a href="http://code.google.com/p/gordianatlas">Gordian Atlas Project</a></div>';

foreach ($this->gordian_assets->getFooterScripts() as $k => $v)
{
	echo '				<script src="'.$v.'" type="text/javascript"></script>' . "\n";
}	

echo $this->gordian_assets->flashmessage_widget();
?>
	</body>
</html>