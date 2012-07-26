<?php
if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

/**
 * WikiPage and WikiPageRevision management library.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 4
 * @license GPL 3
 */
class Gordian_wiki 
{
	// CodeIgniter Reference
	private $CI;
	
	/**
	 * Default Constructor.
	 */
	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->model('Gordian_wiki_model');
	}
	
	/**
	 * Adds a new WikiPage to be referenced by other objects.
	 * 
	 * @param string The universally unique title for a wiki page.
	 * @param string The initial wiki page revision for the document.
	 */
	public function add($name, $description)
	{
		if (is_object($this->find($name)))
		{
			return FALSE;
		}
		
		/*
		 * Insert data
		 */
		 
		 $wiki_id = $this->CI->Gordian_wiki_model->add($name);		 
		 $revision_id = $this->revise($wiki_id, $description);
		 
		 if (!is_numeric($wiki_id) || !is_numeric($revision_id))
		 {
		 	return FALSE;
		 }
		
		return $wiki_id;
	}
		
	/**
	 * Associates the given Wiki to the identified location.
	 * 
	 * @param numeric The Id of the timeline to associate to.
	 * @param numeric The Id of the location to associate to.
	 * @param numeric The Id of the Wikipage to associate.
	 * 
	 * @return boolean if the association was valid.
	 */
	function associate_location($timeline_id, $location_id, $wiki_id)
	{
		$this->CI->load->library('Gordian_timeline');
		$this->CI->load->library('Gordian_map');
		
		$location = $this->CI->gordian_map->find($location_id);
		$timeline = $this->CI->gordian_timeline->find($timeline_id);
		
		return $this->CI->Gordian_wiki_model->associate_location($timeline_id, $location_id, $wiki_id);		
	}
	
	/**
	 * Attempts to find a WikiPage by name
	 * 
	 * @param mixed The ID or name of the WikiPage to locate.
	 * 
	 * @return object The wiki page object
	 */
	public function find()
	{
		if (func_num_args() != 1)
		{
			return FALSE;
		}
		
		return $this->CI->Gordian_wiki_model->find(func_get_arg(0));
	}
	
	/**
	 * Revises the data of a given wikipage.
	 * 
	 * @param numeric The ID of the core wiki page to revise.
	 * @param string The string replacement for the Wiki data.
	 * 
	 * @return boolean If succesfully revised or not.
	 */
	public function revise($wiki_id, $revisions)
	{
		if (is_object($this->find($wiki_id)))
		{
			return $this->CI->Gordian_wiki_model->revise($wiki_id, $revisions);
		}	
		
		return FALSE;
	}
}