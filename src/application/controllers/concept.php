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
		
			$updated_concept = $this->input->post('');
				
			if (strlen($updated_concept) > 0)
			{
				$this->gordian_concept->edit($this->record_id(), $updated_concept);
			}
		}
	}

	/**
	 * Returns information associated to a given record type.
	 */
	public function entries()
	{
		exit(json_encode($this->gordian_concept->entries()));
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
				
		$data['block_content'] = $concept_data->wikidata->Content;
		
		$this->load->view('wiki/template', $data);
	}
}