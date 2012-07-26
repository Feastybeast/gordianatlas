<?php
/**
 * A chunk of HTML controls displayed when a user is logged in.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 4
 * @license GPL 3
 */

if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}
?>

<!-- Begin the Add / Edit Location forms -->
<div id="location-form" title="Manage Location">
<?php echo form_open('/map/add'); ?>
	<fieldset>
		<p>
			<label for="loc_name"><?php echo $label_location_name; ?></label><br />
			&nbsp;&nbsp;<input type="text" name="loc_name" id="loc_name" class="text ui-widget-content ui-corner-all" />
		</p>
		<p>
			<label for="lat"><?php echo $label_location_lat; ?></label>
			<input type="text" name="lat" id="lat" value="" size="6" class="text ui-widget-content ui-corner-all" /> &nbsp;&nbsp;
			<label for="lng"><?php echo $label_location_lng; ?></label>
			<input type="text" name="lng" id="lng" value="" size="6" class="text ui-widget-content ui-corner-all" />
		</p>
 		<p>
			<label for="loc_descript"><?php echo $label_location_description; ?></label><br />
			&nbsp;&nbsp; <textarea rows="4" cols="30" name="loc_descript" id="loc_descript" value="" class="text ui-widget-content ui-corner-all"></textarea>
		</p>
	</fieldset>
<?php 
	echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash());
	echo form_close(); 
?>
</div>
<!-- //End the Add / Edit Location forms -->