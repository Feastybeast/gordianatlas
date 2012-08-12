<?php
if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

/**
 * The Gordian Concept Model
 * 
 * @author Rob Mann <manroo@metrostate.edu>
 * @since Elaboration 5
 * @license GPL 3
 */
class Gordian_concept_model extends CI_Model
{
	/**
	 * Default Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->library("gordian_wiki");
	}
	
	/**
	 * Adds a new concept to the database.
	 * 
	 * @param string The title of the concept.
	 * @param string The description of the concept.
	 */
	public function add($title, $content)
	{
		//	Preparing a new ID to use
		$this->db->insert("Concept",array("CreatedOn"=>date('Y-m-d H:i:s')));
		$concept_id = $this->db->insert_id();

		//	Going to add a new alias to Database
		$this->db->insert("ConceptAlias",array("Concept_IdConcept"=>$concept_id,"Content"=>$title));
		
		//	Going to add a new wiki page to Database
		$wiki_id = $this->gordian_wiki->add($title, $content);
		$this->gordian_wiki->associate_concept(1,$concept_id,$wiki_id);
		
		return $concept_id;
	}
	
	/**
	 * Removes a concept from a given timeline.
	 * 
	 * @param string The type of resource to remove from {'location', 'event', 'personality' }
	 * @param numeric The Id of the resource attaching to.
	 * @param numeric The Id of the concept to attach the resource to.
	 */
	public function attach($resource_type, $resource_id, $concept_id)
	{
		/*
		 * SQL Mappings
		 */
		$sql_mappings = $this->mappings($resource_type);
		
		/*
		 * If there is no mapping ...
		 */
		if (!is_array($sql_mappings))
		{
			return FALSE;
		}		
		
		$fields = array(
			$sql_mappings['clause'] => $resource_id, 
			'Concept_IdConcept' => $concept_id
		);

		$this->db->insert($sql_mappings['table'], $fields);
		
		return $this->db->insert_id();
	}	
	
	/**
	 * Find a concept via ID or Title.
	 */
	public function find($arg)
	{
		/*
		 * Baseline query results that have to return to be effective.
		 */ 
		$query  = "SELECT con.IdConcept, con.CreatedOn, con.ModifiedOn ";
		$query .= "FROM Concept con ";

		if (is_numeric($arg))
		{
			$query .= "WHERE con.IdConcept = ?";
		}
		else if (is_string($arg))
		{
			$query .= "INNER JOIN ConceptAlias cona ON cona.Concept_IdConcept = con.IdConcept ";
			$query .= "WHERE cona.Content = ?";
		}
	
		$res = $this->db->query($query,array($arg));

		if ($res->num_rows() == 0)
		{
				return FALSE;
		}
		
		/*
		 * We have a successful core return.
		 */
		$ret = $res->row();
		
		$ret->aliases = array();
		
		/*
		 * Load Aliases for the Concept
		 */
		$query_alias  = 'SELECT Content FROM ConceptAlias ';
		$query_alias .= 'WHERE Concept_IdConcept = ? ';
		$query_alias .=	'ORDER BY Ordering';
		
		$res = $this->db->query($query_alias, array($ret->IdConcept));
		
		if ($res->num_rows() == 0)
		{
			foreach($res->result() as $row)
			{
				$ret->aliases[] = $row->Content;
			}
		}
		
		/*
		 * Return WikiPage information, if available.
		 */
		 $ret->wikidata = $this->gordian_wiki->referenced_by('concept', $ret->IdConcept);
		
		/*
		 * Find related events.
		 */
		 $ret->related_events = array();
		 $events = $this->related_events();
		 
		 foreach($events as $k => $v)
		 {
			if (in_array($ret->IdConcept, explode(',', $v->Mapped)))
			{
				$ret->related_events[] = $v;
			}
		 }
		
		return $ret;
	}
	
	/**
	 * Removes a concept from a given timeline.
	 * 
	 * @param string The type of resource to remove from {'location', 'event', 'personality' }
	 * @param numeric The Id of the concept to remove from the given timeline.
	 */
	public function detach($resource_type, $resource_id, $concept_id)
	{
		/*
		 * SQL Mappings
		 */
		$sql_mappings = $this->mappings($resource_type);
		
		/*
		 * If there is no mapping ...
		 */
		if (!is_array($sql_mappings))
		{
			return FALSE;
		}		
		
		$fields = array(
			$sql_mappings['clause'] => $resource_id, 
			'Concept_IdConcept' => $concept_id,
			'Timeline_IdTimeline' => 1
		);

		$this->db->delete($sql_mappings['table'], $fields);
		
		return TRUE;
	}
	
	/**
	 * Common mappings mechanism for the model.
	 * 
	 * @param string The kind of mapping to return.
	 * 
	 * @return mixed Either the mappings data or FALSE.
	 */
	private function mappings($type)
	{
		$mappings =  array(
			'event' => array(
				'table' => 'EventHasConcept',
				'clause' => 'Event_IdEvent'
			),
			'location' => array(
				'table' => 'LocationHasConcept',
				'clause' => 'Location_IdLocation'
			), 
			'person' => array(
				'table' => 'PersonHasConcept',
				'clause' => 'Person_IdPerson'
			)
		);
		
		return (array_key_exists($type, $mappings)) ? $mappings[$type] : FALSE;
	}
	
	/**
	 * Updates the events associated to the provided location.
	 * 
	 * @param numeric The location Id to update.
	 * @param array The related events to associate to the location.
	 */
	public function relate_events($concept_id, $new_relations)
	{
		/*
		 * Remove ALL existing location records.
		 */
		$qry_delete = "DELETE FROM EventHasConcept WHERE Concept_IdConcept = {$concept_id}";
		$this->db->query($qry_delete);
		
		/*
		 * Add records back in.
		 */
		 foreach ($new_relations as $k => $v)
		 {
		 	$this->db->insert('EventHasConcept', array(
		 		'Concept_IdConcept' => $concept_id,
		 		'Event_IdEvent' => $v
		 	)); 
		 }
	}
	
	/**
	 * Returns a list of all events, with further indication of current relations.
	 */
	public function related_events()
	{
		$qry_events  = "SELECT evt.IdEvent, ea.Title, IFNULL(ehc.Mapped, 0) AS Mapped ";
		$qry_events .= "FROM Event evt ";
		$qry_events .= "INNER JOIN ( ";
		$qry_events .= "    SELECT Event_IdEvent, Title ";
		$qry_events .= "    FROM EventAlias ";
		$qry_events .= "    GROUP BY Event_IdEvent ";
		$qry_events .= "    ORDER BY Ordering ";
		$qry_events .= ") ea ON ea.Event_IdEvent = evt.IdEvent ";
		$qry_events .= "LEFT OUTER JOIN ( ";
		$qry_events .= "    SELECT Event_IdEvent, GROUP_CONCAT(Concept_IdConcept) AS Mapped ";
		$qry_events .= "    FROM EventHasConcept ehc "; 
		$qry_events .= "    GROUP BY Event_IdEvent ";
		$qry_events .= ") ehc ON ehc.Event_IdEvent = evt.IdEvent ";
		
		$res = $this->db->query($qry_events);
		
		return ($res->num_rows() > 0) ? $res->result() : FALSE;
	}	
}