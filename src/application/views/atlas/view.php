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
			<img id="content_picture" alt="Associated Picture to Current Data" src="uploads/images/personalities/Richard-Lionheart.jpg" width="200" height="200" />
			<p id="content_details">Feel free to explore this time period by clicking on Icons on the map, or using the timeline below!</p>
		</div>
	</div>
	<div id="mapViewport">
	</div>	
</div>
<div id="timelineRow" class="structuralRow">
	<div id="timelineViewport"></div>
</div> 
<?php
	$this->load->view('layouts/footer');
?>
