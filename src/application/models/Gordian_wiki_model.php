<?php
if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

/**
 * The success screen for registering a new account.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 3
 * @license GPL 3
 */
class Gordian_wiki_model extends CI_Model
{
	/**
	 * Default Constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Adds a new WikiPage to the system
	 * 
	 * @param string The core name of the WikiPage.
	 * 
	 * @return numeric The Id of the newly created page.
	 */
	public function add($name)
	{
		$data = array(
			'Title' => $name
		);
		
		$this->db->insert('WikiPage', $data);
		return $this->db->insert_id();	
	}

	/**
	 * Associates the given Wiki to the identified location.
	 * 
	 * @param numeric The Id of the timeline to make the association.
	 * @param numeric The Id of the Wikipage to associate.
	 * @param numeric The Id of the location to associate to for said timeline.
	 * 
	 * @return boolean if the association was valid.
	 */
	function associate_event($timeline_id, $event_id, $wiki_id)
	{
		$data = array(
					'Timeline_IdTimeline' => $timeline_id,
					'Event_IdEvent' => $event_id,
					'WikiPage_IdWikiPage' => $wiki_id
				); 
				
		$this->db->insert('TimelineEventHasWikiPage', $data);
	}

	/**
	 * Associates the given Wiki to the identified location.
	 * 
	 * @param numeric The Id of the timeline to make the association.
	 * @param numeric The Id of the Wikipage to associate.
	 * @param numeric The Id of the location to associate to for said timeline.
	 * 
	 * @return boolean if the association was valid.
	 */
	function associate_location($timeline_id, $location_id, $wiki_id)
	{
		$data = array(
					'Timeline_IdTimeline' => $timeline_id,
					'Location_IdLocation' => $location_id,
					'WikiPage_IdWikiPage' => $wiki_id
				); 
				
		$this->db->insert('TimelineLocationHasWikiPage', $data);
	}
	
	/**
	 * Returns information about a WikiPage given a name.
	 * 
	 * @param string The core name of the wikipage sought.
	 * 
	 * @return mixed Either FALSE or an object describing the Page.
	 */
	public function find()
	{
		if (func_num_args() != 1)
		{
			return FALSE;
		}
		
		/*
		 * Data critereon to look for.
		 */
		$arg = func_get_arg(0);
		
		if (is_numeric($arg))
		{
			$data = array(
				'IdWikiPage' => $arg
			);			
		}
		else
		{
			$data = array(
				'Title' => $arg
			);			
		}
		
		$query = $this->db->get_where('WikiPage', $data);
		
		/*
		 * Act accordingly.
		 */
		if ($query->num_rows() == 0)
		{
			return FALSE;
		}
		else
		{
			return $query->row();
		}
	}
	
	/**
	 * Inserts a revised WikiPage into the database.
	 * 
	 * @param numeric The core wiki ID to revise.
	 * @param string The revisions to overwrite for existing data.
	 */
	public function revise($wiki_id, $revisions)
	{
		$data = array(
			'WikiPage_IdWikiPage' => $wiki_id,
			'Content' => $revisions
		);
		
		$this->db->insert('WikiPageRevision', $data);
		return $this->db->insert_id();
	}
}