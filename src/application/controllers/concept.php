<?php
if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

/**
 * The success screen for registering a new account.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 4
 * @license GPL 3
 */
class Concept extends GA_Controller
{
	/**
	 * Default Constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->library("Gordian_concept");
	}
	
	/**
	 * Add a new concept to the database.
	 */
	public function add()
	{
		$title = $this->input->post("concept_name");
		$content = $this->input->post("concept_descript");

		if ($this->gordian_auth->is_logged_in())
		{
			if (strlen($title)>0 && strlen($content)>0)
			{		
	
				$concept = $this->gordian_concept->find($title);
	
				if(!is_object($concept))
				{
					$this->gordian_concept->add($title,$content);	
				}
				
			}
		}
	}
	
	/**
	 * NYI
	 */
	public function delete()
	{
		if ($this->input->is_ajax_request() && $this->gordian_auth->is_logged_in())
		{
			$remove_id = $this->record_id();

			if ($remove_id != FALSE)
			{
				// $this->gordian_concept->remove_from($remove_id);				
			}
		}	
	}
	
	/**
	 * Updates a concept's wiki page.
	 */
	public function edit()
	{
		if ($this->input->is_ajax_request() && $this->gordian_auth->is_logged_in())
		{
			if ($this->record_id() == FALSE)
			{
				return FALSE;
			}
		
			$updated_concept = $this->input->post('content');
				
			if (strlen($updated_concept) > 0)
			{
				$this->gordian_concept->edit($this->record_id(), $updated_concept);
			}
		}
	}

	/**
	 * Returns information associated to a given record type.
	 */
	public function related_to()
	{
		$values = explode('/', uri_string());

		$id = array_pop($values);
		$kind = array_pop($values);
		
		$data = '';

		if (is_numeric($id) && is_string($kind))
		{
			$data = $this->gordian_concept->entries($kind, $id);
		}
		
		exit(json_encode($data));
	}
	
/**
	 * Returns and manages the list of related locations to this event.
	 */
	public function related_events()
	{		
		$post = $this->post_vars();

		if (array_key_exists('related_updated', $post))
		{
			if ($this->gordian_auth->is_logged_in())
			{
				$related = (array_key_exists('related', $post)) ? $post['related'] : array();
	
				$this->gordian_concept->relate_events($this->record_id(), $related);
	
				echo "Updated";
				exit();	
			}			
		}
		else
		{
			$records = $this->gordian_concept->related_events($this->record_id());
	
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
					$recs = explode(',', $v->Mapped);
					$data['output'] .= form_checkbox('related', $v->IdEvent, (in_array($this->record_id(), $recs)) ? TRUE : FALSE);
					$data['output'] .= ' ' . $v->Title . "<br />\n";
				}

					$data['output'] .= '<input type="hidden" id="related_updated" name="updated" value="1" />';

				
				exit($data['output']);
			}			
		}
	}	
	
	public function wiki()
	{
		$id = $this->record_id();
		$data = $this->gordian_wiki->data_template($this->record_type());
		
		if ($id == FALSE)
		{
			return FALSE;
		}
		
		$concept_data = $this->gordian_concept->find($id);
		
		/*
		 * Fill out the standard component. 
		 */
		$data['title'] = $concept_data->wikidata->Title;
		$data['title_id'] = 'concept_name_val';
		$data['record_id'] = $this->record_id();
				
		$data['block_content'] = '<span id="concept_descript_val">' . $concept_data->wikidata->Content . '</span>';
		
		/*
		 * Deal with related events
		 */
		 if (count($concept_data->related_events) > 0)
		 {
		 	$data['block_events'] = "<ul>";
		 
		 	foreach($concept_data->related_events as $k => $v)
		 	{
		 		$data['block_events'] .= '<li><a href="/event/wiki/' . $v->IdEvent . '" class="wiki_btn">' . $v->Title . '</a></li>';
		 	}
		 	
		 	$data['block_events'] .= "</ul>";
		 }
		
		$this->load->view('wiki/template', $data);
	}
}