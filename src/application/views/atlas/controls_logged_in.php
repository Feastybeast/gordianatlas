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

<!-- Begin the Add / Edit Event forms -->
<div id="event-form" title="Manage Event">
<?php echo form_open('/timeline/add'); ?>
	<fieldset>
		<p>
			<label for="evt_name"><?php echo $label_event_name; ?></label><br />
			&nbsp;&nbsp;<input type="text" name="evt_name" id="evt_name" class="text ui-widget-content ui-corner-all" size="30" />
		</p>
		<p><label for="evt_occurance"><?php echo $label_event_occurance; ?></label>
			&nbsp;&nbsp;<input type="text" name="evt_occurance" id="evt_occurance" class="text ui-widget-content ui-corner-all" size="10" />
		</p>
		<p>
			<label for="evt_range"><?php echo $label_event_range; ?></label>
			<input type="text" name="evt_range" id="evt_range" class="text ui-widget-content ui-corner-all" size="4" />
			<label for="evt_duration"><?php echo $label_event_duration; ?></label>
			<input type="text" name="evt_duration" id="evt_duration" class="text ui-widget-content ui-corner-all" size="4" />
			&nbsp;
			<select name="evt_units" id="evt_units">
				<?php
				// <option value="MINUTE">minutes</option>
				// <option value="HOUR">hours</option>
				?>
				<option value="DAY" selected="selected">days</option>
				<option value="WEEK">weeks</option>
				<option value="MONTH">months</option>
				<option value="YEAR">years</option>
				<option value="DECADE">decades</option>
				<?php
				// <option value="CENTURY">centuries</option>
				// <option value="MILLENIA">millenia</option>
				?>
			</select>
		</p>
		<p style="font-size: 60%;"><?php echo $label_event_notice; ?></p>
 		<p>
			<label for="evt_descript"><?php echo $label_event_description; ?></label><br />
			&nbsp;&nbsp; <textarea rows="4" cols="30" name="evt_descript" id="evt_descript" value="" class="text ui-widget-content ui-corner-all"></textarea>
		</p>
	</fieldset>
<?php 
	echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash());
	echo form_close(); 
?>
</div>
<!-- //End the Add / Edit Location forms -->
