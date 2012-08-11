<?php
if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

/**
 * The location controller - used to pull data for map subobjects.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 4
 * @license GPL 3
 */
class Location extends GA_Controller
{
	/**
	 * Default Constructor
	 */
	public function __construct()
	{
		parent::__construct();

	 	// Prepare the wiki template
		$this->load->library('gordian_wiki');
		$this->load->library('gordian_location');

	 	// Load the map stringpack.
	 	$this->lang->load('gordian_location');
	}
	
	/**
	 * Returns the list of possibly related events to this location.
	 */
	public function related_events()
	{
		$post = $this->post_vars();

		if (array_key_exists('related_updated', $post))
		{
			$related = (array_key_exists('related', $post)) ? $post['related'] : array();

			$this->gordian_location->relate_events($this->record_id(), $related);

			echo "Updated";
			exit();
		}
		else
		{
			$records = $this->gordian_location->related_events($this->record_id());
	
			if ($records == FALSE)
			{
				$this->lang->load('gordian_wiki');
				
				$data['title'] = $this->lang->line('gordian_wiki_error_generic_title');
				$data['error'] = $this->lang->line('gordian_wiki_error_generic_body');
				
				$this->load->view('wiki/error', $data);
			}
			else
			{
				$data['output'] = '';
				
				foreach($records as $k => $v)
				{
					$data['output'] .= form_checkbox('related', $v->IdEvent, ($v->Mapped == $this->record_id()) ? TRUE : FALSE);
					$data['output'] .= ' ' . $v->Title . "<br />\n";
				}

					$data['output'] .= '<input type="hidden" id="related_updated" name="updated" value="1" />';

				
				exit($data['output']);
			}			
		}
	}
	
	/**
	 * Render wiki data for the location item.
	 */
	public function wiki()
	{
		$kind = $this->record_type();
	 	$id = $this->record_id();

		$data = $this->gordian_wiki->data_template($kind);
	 		 	
	 	$wiki_data = $this->gordian_wiki->referenced_by($kind, $id);
	 	
	 	// Pull Location Information.
	 	$location_data = $this->gordian_location->find($id);
	 	
	 	if (is_object($location_data) && is_object($wiki_data))
	 	{
	 		/*
	 		 * Prep the fields as required.
	 		 */
	 		$data['record_id'] = $id;
	 		$data['record_type'] = $this->record_type(); 
	 		 
	 		$data['title_id'] = 'loc_name_val';
	 		$data['title'] = $wiki_data->Title;

			if (count($location_data->aliases) > 1)
			{
				$data['block_content']  = '<div>';
				$data['block_content'] .= $this->lang->line('gordian_location_ajax_aka_label');
				$data['block_content'] .= implode(array_diff($location_data->aliases, array($wiki_data->Title)), ', ');
				$data['block_content'] .= '</div>';
			}
			
			$data['block_content'] .= "<div>";
			$data['block_content'] .= $this->lang->line('gordian_location_ajax_latlng_lbl');
			$data['block_content'] .= "(<span id='lat_val'>{$location_data->Lat}</span>,<span id='lng_val'>{$location_data->Lng}</span>)";
			$data['block_content'] .= "</div>";
			
	 		$data['block_content'] .= '<div class="wiki_inset" id="loc_descript_val">' . $wiki_data->Content . '</div>';
		 	
		 	/*
		 	 * Related Concepts 
		 	 */
		 	 foreach($data['display_tabs'] as $k => $v)
		 	 {
				if ($v == 'concept' || $v == 'personality')
				{
					unset($data['display_tabs'][$k]);
				}		 	 	
		 	 }
		 	
		 	/*
		 	 * Link the event tabs as necessary.
		 	 */
			if (count($location_data->events) > 0)
			{
				$data['block_events'] = '<div><strong>' . $this->lang->line('gordian_location_ajax_events_label') . '</strong></div>';
				
				$data['block_events'] .= '<ul>';
				
				foreach($location_data->events as $k => $v)
				{
					$data['block_events'] .= '<li><a href="/event/wiki/' . $v["Id"] . '" class="wiki_btn">' . $v["Title"] . '</a></li>'; 
				}

				$data['block_events'] .= '</ul>';				
			}
		 	
			/*
			 * Finally load the template.
			 */		 			 			 			 	
		 	$this->load->view('wiki/template', $data);
	 	}
	 	else
	 	{	
	 		$data['title'] = $this->lang->line('gordian_location_ajax_title');
	 		$data['error'] = $this->lang->line('gordian_location_ajax_error');
		 	$this->load->view('wiki/error', $data);
	 	}
	}
}