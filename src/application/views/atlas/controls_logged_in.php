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

<!-- Begin Concept forms -->
<div id="concept-form" title="<?php echo $label_concept_title; ?>">
<?php echo form_open('/concept/add'); ?>
	<p>
		<label for="concept_name"><?php echo $label_concept_name; ?></label><br />
		&nbsp;&nbsp;<input type="text" name="concept_name" id="concept_name" class="text ui-widget-content ui-corner-all" size="30" />
	</p>
	<p><label for="concept_descript"><?php echo $label_concept_description; ?></label><br />
	&nbsp;&nbsp; <textarea rows="4" cols="30" name="concept_descript" id="concept_descript" class="text ui-widget-content ui-corner-all"></textarea>
	</p>
<?php 
	echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash());
	echo form_close(); 
?>
</div>
<!-- //End Concept form -->

<!-- Begin the Add / Edit Event forms -->
<div id="event-form" title="<?php echo $label_event_title; ?>">
<?php echo form_open(''); ?>
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
		&nbsp;&nbsp; <textarea rows="4" cols="30" name="evt_descript" id="evt_descript" class="text ui-widget-content ui-corner-all"></textarea>
	</p>
<?php 
	echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash());
	echo form_close(); 
?>
</div>
<!-- //End the Add / Edit Event forms -->

<!-- Begin the Add / Edit Location forms -->
<div id="location-form" title="<?php echo $label_location_title; ?>">
<?php echo form_open(''); ?>
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
		&nbsp;&nbsp; <textarea rows="4" cols="30" name="loc_descript" id="loc_descript" class="text ui-widget-content ui-corner-all"></textarea>
	</p>
<?php 
	echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash());
	echo form_close(); 
?>
</div>
<!-- //End the Add / Edit Location forms -->

<!-- Begin Personality forms -->
<div id="person-form" title="<?php echo $label_person_title; ?>">
<?php echo form_open(''); ?>
		<p>
			<label for="person_name"><?php echo $label_person_name; ?></label><br /> 
			&nbsp;&nbsp;<input type="text" name="person_name" id="person_name" class="text ui-widget-content ui-corner-all" size="30" />
		</p>
		<p>
			<label for="person_birth"><?php echo $label_person_birth; ?></label>
			&nbsp;<input type="text" name="person_birth" id="person_birth" class="text ui-widget-content ui-corner-all" size="15" /> 
			<?php echo $label_person_birth_loc; ?>
			<select name="person_birth_loc" id="person_birth_loc">
			</select>
		</p>
		<p>
			<label for="person_death"><?php echo $label_person_death; ?></label>
			&nbsp;<input type="text" name="person_death" id="person_death" class="text ui-widget-content ui-corner-all" size="15" /> 
			<?php echo $label_person_death_loc; ?>
			<select name="person_death_loc"  id="person_death_loc">
			</select>
		</p>		
		<p>
			<label for="person_descript"><?php echo $label_person_description; ?></label><br />
			&nbsp;&nbsp; <textarea rows="6" cols="50" name="person_descript" id="person_descript" class="text ui-widget-content ui-corner-all"></textarea>
		</p>
<?php 
	echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash());
	echo form_close(); 
?>
</div>
<!-- //End Concept form -->

<!-- Begin Relation forms -->
<div id="relate-form" title="<?php echo $label_relation_title; ?>">
<?php echo form_open(''); ?>
	<div id="relation-div">
	</div>
<?php 
	echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash());
	echo form_close(); 
?>
</div>
<!-- //End Concept form -->
<?php echo '<div class="dialog" id="deletion_notice" title="' . $delete_title . '">' . $delete_content . '</div>'; ?>