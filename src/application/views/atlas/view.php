<?php
/**
 * The main view screen for the application.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 2
 * @license GPL 3
 */

if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

$this->load->view('layouts/header');	
$this->load->view('layouts/superBar');
?>
<div id="mapInfoRow" class="structuralRow">
	<div id="biographyViewport">
		<div id="content">
		</div>
	</div>
	<div id="mapViewport">		
	</div>
<?php
	if ($this->gordian_auth->is_logged_in())
	{
		echo '	<div id="addLoc"><a href="/map/add" id="btnAddLoc"><img src="/assets/img/add.png" width="16" height="16" border="0" />';
		echo $add_button_link; 
		echo '</a></div>';	
	}
?>
</div>
<div id="timelineRow" class="structuralRow">
<?php
	if ($this->gordian_auth->is_logged_in())
	{
		echo '	<div id="addEvent"><a href="/timeline/add" id="btnAddEvent"><img src="/assets/img/add.png" width="16" height="16" border="0" />';
		echo $add_button_event; 
		echo '</a></div>';	
	}
?>
	<div id="timelineViewport"></div>
</div>

<?php 
if ($this->gordian_auth->is_logged_in())
{
	$this->load->view('atlas/controls_logged_in');	
}

$this->load->view('layouts/footer');
?>