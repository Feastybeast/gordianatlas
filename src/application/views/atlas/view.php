<?php
	$this->load->view('layouts/header');
	
	
?>
<div id="badgingRow" class="structuralRow">
	<div id="headerPane">
		The Crusades Interactive Atlas<br />
		<span class="sub">&copy; Jay Ripley 2012</span>
	</div>
</div>
<div id="mapInfoRow" class="structuralRow">
	<div id="biographyViewport">
		<div id="content">
			<h4 id="content_title">Welcome to the Interactive Crusades Atlas</h4>
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
