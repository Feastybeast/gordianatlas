<?php
if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

/**
 * Timeline managment library.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 3
 * @license GPL 3
 */
class Gordian_timeline
{
	// The reference to the CodeIgniter Library.
	private $CI;
	// Used to store error messages generated during operations.
	private $errors; 
	// Valid durations for the system to use.
	private static $durations = 
		array('MINUTE','HOUR','DAY','WEEK','MONTH',
				'YEAR','DECADE','CENTURY','MILLENIA');

	/**
	 * Default constructor. 
	 * 
	 * Prepares references to the core software models used throughout 
	 * timeline management for the Atlas.
	 */
	public function __construct()
	{
		$this->CI =& get_instance();
		
		// Directly load your models ...
		$this->CI->load->model('Gordian_timeline_model');
		//And your language pack
		$this->CI->lang->load('gordian_timeline');
		// But not others ...
		$this->CI->load->library('Gordian_group');
		$this->CI->load->library('Gordian_view');
	}
	

	
	/**
	 * Creates a new timeline entry in the Atlas database.
	 * 
	 * The timeline is associated to an owning group.
	 * 
	 * @param numeric The owning group ID of the timeline.
	 * @param string The human readable title of the timeline
	 * @param string The human readable long form description.
	 * 
	 * @return boolean if the timeline was successfully created.
	 */
	public function create($owning_group, $title, $description)
	{	
		// Verify that the timeline is NOT present.
		$timeline_exists = $this->exists($title);
		// But that the group requesting the timeline IS.
		$group_exists = $this->CI->gordian_group->exists($owning_group);

		if (!$timeline_exists && $group_exists)
		{
			// View is implicitly created in this timeline command.
			$created_timeline = $this->CI->Gordian_timeline_model->create($title, $description);

			// Assign the Timeline to the Group
			$is_assigned = $this->CI->Gordian_timeline_model->assign_to($created_timeline, $owning_group);

			if ($is_assigned)
			{
				// Create the new view ...
				$view_id = $this->CI->gordian_view->create($owning_group, $title, $description);

				if(is_numeric($view_id))
				{	
					// And associate it ...
					$is_assigned = $this->CI->gordian_view->assign_to($view_id, $created_timeline);
					
					if ($is_assigned) // We made it this far, we're done!
					{
						return $created_timeline; 
					}
				}
			}
		}
			
		return FALSE;
	}			
	
	/**
	 * TODO: Implement.
	 */
	public function delete($id)
	{
		// NYI
	}

	/**
	 * Checks to see if a Timeline exists given a title or Id.
	 * 
	 * @param mixed The title string or group Id for the expected Timeline.
	 * @return boolean If the Timeline in question exists.
	 */	
	public function exists()
	{
		if (func_num_args() != 1)
		{
			$this->load->lang('gordian_exceptions');
			$ex = $this->lang->line('gordian_exceptions_illegal_arg');
			throw new Exception($ex);			
		}
		else
		{
			$arg = func_get_arg(0);
			return $this->CI->Gordian_timeline_model->exists($arg);
		}
		
		return FALSE;		
	}

	/**
	 * Returns information about a Timeline identified by a title or Id.
	 * 
	 * @param mixed The title string or group Id for the expected Timeline.
	 * @return boolean If the Timeline in question exists.
	 */	
	public function find()
	{
		if (func_num_args() != 1)
		{
			$this->load->lang('gordian_exceptions');
			$ex = $this->lang->line('gordian_exceptions_illegal_arg');
			throw new Exception($ex);			
		}
		else
		{
			$arg = func_get_arg(0);
			return $this->CI->Gordian_timeline_model->find($arg);
		}
		
		return FALSE;		
	}
	
	/**
	 * Returns the JSON data for the given timeline.
	 * 
	 * @param numeric The Id of the timeline to view.
	 * 
	 * @return string JSON data describing the timeline.
	 */
	public function load($timeline_id)
	{
		$timeline_data = $this->CI->Gordian_timeline_model->load($timeline_id);
		
		$json = array('dateTimeFormat' => 'iso8601');
		
		foreach($timeline_data->result() as $row)
		{	
			/*
			 * Prepare a baseline setup.
			 */
			$datum = array(
				'start'=> $row->OccuredOn,
    			'title' => $row->Title,
				'isDuration' => 0,
    			'classname'=> 'event id'.$row->IdEvent
			);
			
			/*
			 * Deal with durations if necessary.
			 */
			if ($row->OccuredDuration > 0)
			{
				$duration_date = new DateTime($row->OccuredOn);
				$fd = $this->format_duration($row->OccuredDuration, $row->OccuredUnit);
				$duration_date->add(new DateInterval($fd));
				$datum['end'] = $duration_date->format('Y-m-d H:i:s');

				$datum['isDuration'] = 1;
			}
			
			/*
			 * Set the row.
			 */	
			$json['events'][] = $datum;				
		}
		
		return json_encode($json);		
	}
	
	private function format_duration($amount, $unit)
	{
		$unit_trans = array( 
			'DAY' => 'D',
			'WEEK' => 'W',
			'MONTH' => 'M',
			'YEAR' => 'Y',
			'DECADE' => '0Y',
			'CENTURY' => '00Y',
			'MILLENIA' => '000Y'
		);
		
		if (!array_key_exists($unit, $unit_trans))
		{
			return FALSE;
		}
		
		return "P" . $amount . $unit_trans[$unit];
	}
	
	/*
	 * Support Methods
	 */
	 /**
	  * Returns all errors that occured from the last operation.
	  * 
	  * @return array The errors that occcured in the last operation.
	  */
	 public function get_errors()
	 {
	 	return $this->errors;
	 }
	 
	 /**
	  * Empties all known errors, called previous to a new operation.
	  */
	 private function reset_errors()
	 {
	 	$this->errors = array();
	 }
	
	/**
	 * Logs an error message due to faulty operations.
	 * 
	 * @param string The error message to log.
	 */
	private function set_error($error)
	{
		$this->errors[] = $error;
	}
	
	/**
	 * Timeline creation and editing widget.
	 * @param $config See <http://code.google.com/p/gordianatlas/wiki/Gordian_timeline>
	 */
	public function ui_create_edit($config)
	{
		$this->CI->lang->load('gordian_timeline');
		
		/*
		 * Header Details
		 */
		$header_data = (array_key_exists('header', $config)) 
			? $config['header'] 
			: $this->CI->lang->line('gordian_timeline_heading'); 
		$header_label = heading($header_data, 3); 
		
		/*
		 * Title field details
		 */
		$title_data = (array_key_exists('title', $config))
			? $config['title'] 
			: $this->CI->lang->line('gordian_timeline_title_label');
		$title_label = form_label($title_data, 'Nickname');
		$title_field = form_input(array(
							'name' => 'Title', 
							'id' => 'Title', 
							'value' => set_value('Title', '')
						));

		/*
		 * Description field details
		 */
		$description_data = (array_key_exists('description', $config))
			? $config['description'] 
			: $this->CI->lang->line('gordian_timeline_description_label');
		$description_label = form_label($description_data, 'Description');
		$description_field = form_input(array(
							'name' => 'Description', 
							'id' => 'Description', 
							'value' => set_value('Description', '')
						));		
		
		/*
		 * Submit Field Details
		 */
		$submit_label = (array_key_exists('button', $config))
			? $config['button'] 
			: $this->CI->lang->line('gordian_timeline_button');
		$submit_field = form_submit(array(
							'name' => 'postBack', 'value' => set_value('submitValue', $submit_label)
						));
		
		/*
		 * Output the main UI. Note there is no way to supress the Email and Password fields.
		 */
		if (!(array_key_exists('header', $config) && $config['header'] === FALSE))
		{
			echo $header_label;	
		}

		/*
		 * Boilerplate Error Widget output. 
		 * TODO: Consider shifting into seperate component.
		 */
		
		if (strlen(validation_errors()) > 0)
		{	
			echo '<fieldset>';
			echo '<legend>' . $this->CI->lang->line('gordian_auth_widget_header') . '</legend>';
			echo validation_errors();
			echo '</fieldset>';
		}
		
		echo form_open();
		echo '<table>';

		echo '  <tr>';
		echo '		<td align="right">' . $title_label . '</td>';
		echo '		<td align="right" width="1">'. $title_field .'</td>';
		echo '</tr>';		

		echo '  <tr>';
		echo '		<td align="right">' . $description_label . '</td>';
		echo '		<td align="right" width="1">'. $description_field .'</td>';
		echo '</tr>';	
		
		echo '	<tr>';
		echo '		<td></td>';
		echo '		<td align="right">'. $submit_field . '</td>';
		echo '	</tr>';
		
		echo '</table>';
		echo form_close();	
	}
}