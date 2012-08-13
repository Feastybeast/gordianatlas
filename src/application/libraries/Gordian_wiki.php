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
	 * @param numeric The Id of the concept to associate to.
	 * @param numeric The Id of the Wikipage to associate.
	 * 
	 * @return boolean if the association was valid.
	 */
	function associate_concept($timeline_id, $concept_id, $wiki_id)
	{
		$this->CI->load->library('gordian_concept');
		$this->CI->load->library('gordian_timeline');
		
		$concept = $this->CI->gordian_concept->find($concept_id);
		$timeline = $this->CI->gordian_timeline->find($timeline_id);
		
		if (!is_object($concept) || !is_object($timeline))
		{
			return FALSE;
		}
		
		return $this->CI->Gordian_wiki_model->associate_concept($timeline_id, $concept_id, $wiki_id);		
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
	function associate_event($timeline_id, $event_id, $wiki_id)
	{
		$this->CI->load->library('Gordian_event');
		$this->CI->load->library('Gordian_timeline');
		
		$event = $this->CI->gordian_event->find($event_id);
		$timeline = $this->CI->gordian_timeline->find($timeline_id);
		
		if (!is_object($event))
		{
			return FALSE;
		}
		
		if (!is_object($timeline))
		{
			return FALSE;
		}
		
		return $this->CI->Gordian_wiki_model->associate_event($timeline_id, $event_id, $wiki_id);		
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
		$this->CI->load->library('Gordian_location');
		
		$location = $this->CI->gordian_location->find($location_id);
		$timeline = $this->CI->gordian_timeline->find($timeline_id);
		
		return $this->CI->Gordian_wiki_model->associate_location($timeline_id, $location_id, $wiki_id);		
	}
	
	/**
	 * Associates the given Wiki to the identified person.
	 * 
	 * @param numeric The Id of the timeline to associate to.
	 * @param numeric The Id of the person to associate to.
	 * @param numeric The Id of the Wikipage to associate.
	 * 
	 * @return boolean if the association was valid.
	 */
	function associate_person($timeline_id, $person_id, $wiki_id)
	{
		return $this->CI->Gordian_wiki_model->associate_person($timeline_id, $person_id, $wiki_id);		
	}	
	
	/**
	 * Attempts to find a WikiPage either by name or Id.
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
	 * Support method to locate a given wiki resource provided a record type and ID.
	 * 
	 * @param enum The kind of record to locate.
	 * @param enum Its ID.
	 * 
	 * @return mixed either FALSE or a JSON object containing critical details.
	 */
	public function referenced_by($kind, $id)
	{	
		$sql_mappings = array(
			'concept' => array(
				'table' => 'TimelineConceptHasWikiPage',
				'clause' => 'Concept_IdConcept'
			),
			'location' => array(
				'table' => 'TimelineLocationHasWikiPage',
				'clause' => 'Location_IdLocation'
			), 
			'event' => array(
				'table' => 'TimelineEventHasWikiPage',
				'clause' => 'Event_IdEvent'
			),
			'person' => array(
				'table' => 'TimelinePersonHasWikiPage',
				'clause' => 'Person_IdPerson'
			)			
		);
		
		// If there is no mapping ...
		if (!array_key_exists($kind, $sql_mappings))
		{
			return FALSE;
		}
		
		// See if there's a record ...
		$qry  = "SELECT WikiPage_IdWikiPage AS Id ";
		$qry .= "FROM " . $sql_mappings[$kind]['table'] . " ";
		$qry .= "WHERE " . $sql_mappings[$kind]['clause'] . " = {$id} ";
		$qry .= "AND Timeline_IdTimeline = 1";

		$res = $this->CI->db->query($qry, array($id));

		if ($res->num_rows() != 1)
		{
			return FALSE;
		}
		
		// One has been located ...
		return $this->find($res->row()->Id);		
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
		$parent_wiki = $this->find($wiki_id);
		
		if (is_object($parent_wiki))
		{
			return $this->CI->Gordian_wiki_model->revise($wiki_id, $revisions);
		}	
		
		return FALSE;
	}
	
	/**
	 * Returns a data template to be filled in by WikiPages.
	 */
	public function data_template($kind)
	{
		$this->CI->lang->load('gordian_wiki');
		
		$data = array();

		/*
		 * Relation Labels 
		 */
		$data['concepts_add_lbl'] = $this->CI->lang->line('gordian_wiki_concept_add_lbl');
		$data['concepts_relate_lbl'] = $this->CI->lang->line('gordian_wiki_concept_relate_lbl');

		$data['events_relate_lbl'] = $this->CI->lang->line('gordian_wiki_event_relate_lbl');

		$data['locations_relate_lbl'] = $this->CI->lang->line('gordian_wiki_location_relate_lbl');

		$data['personalities_add_lbl'] = $this->CI->lang->line('gordian_wiki_personality_add_lbl');
		$data['personalities_relate_lbl'] = $this->CI->lang->line('gordian_wiki_personality_relate_lbl');

		/*
		 * Detail labels. 
		 */
		$data['concept_lbl'] = $this->CI->lang->line('gordian_wiki_concept_tab_lbl');
		$data['entry_lbl'] = $this->CI->lang->line('gordian_wiki_entry_tab_lbl');
		$data['event_lbl'] = $this->CI->lang->line('gordian_wiki_event_tab_lbl');
		$data['location_lbl'] = $this->CI->lang->line('gordian_wiki_location_tab_lbl');
		$data['manage_lbl'] = $this->CI->lang->line('gordian_wiki_manage_tab_lbl');
		$data['personality_lbl'] = $this->CI->lang->line('gordian_wiki_personality_tab_lbl');
		
		$data['add_lbl'] = $this->CI->lang->line('gordian_wiki_manage_add_lbl');
		$data['edit_lbl'] = $this->CI->lang->line('gordian_wiki_manage_edit_lbl');
		$data['remove_lbl'] = $this->CI->lang->line('gordian_wiki_manage_remove_lbl');
		
		$data['block_content'] = '';
		$data['block_concepts'] = '';
		$data['block_events'] = '';
		$data['block_locations'] = '';
		$data['block_personalities'] = '';

		$data['title'] = '';
		$data['title_id'] = '';
		
		$data['record_type'] = $kind;
		$data['record_id'] = 0;

		/*
		 * Manage Button Configurations
		 */
		$data['display_buttons'][] = 'edit_entry';
		
		if (!in_array($kind, array('concept', 'person')))
		{
			$data['display_buttons'][] = 'delete_entry';			
		}
		
		if (!in_array($kind, array('person')))
		{
			$data['display_buttons'][] = 'associate_events';			
		}
		
		/*
		 * Manage Displayed Tabs
		 */
		$data['display_tabs'][] = 'entry';
		$data['display_tabs'][] = 'event';
		$data['display_tabs'][] = 'location';
		$data['display_tabs'][] = 'person';
		$data['display_tabs'][] = 'concept';
		$data['display_tabs'][] = 'manage';

		$revoke_template = array($kind);

		$revoke_tabs['location'] = array('concept', 'person');
		$revoke_tabs['concept'] = array('location', 'person');
		$revoke_tabs['person'] = array('location', 'concept');
	 	 
	 	 
	 	 if (array_key_exists($kind, $revoke_tabs))
	 	 {
	 	 	$revoke_template = array_merge($revoke_template, $revoke_tabs[$kind]);
	 	 }
	 	 
	 	 foreach($data['display_tabs'] as $k => $v)
	 	 {
	 	 	// Remove 
 			if (in_array($data['display_tabs'][$k], $revoke_template))
 			{
 				unset($data['display_tabs'][$k]);
 			}
	 	 } 		
		
		return $data;
	}
}