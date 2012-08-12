<?php
if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

/**
 * The Gordian Events Database Model
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 4
 * @license GPL 3
 */
class Gordian_event_model extends CI_Model
{
	/**
	 * Default Constructor.
	 */
	public function __construct()
	{
		parent::__construct();
	}



	/**
	 * Edits an existing event within the database.
	 * @param numeric The Id of the event in question.
	 * @param string The date the event occured on.
	 * @param numeric The range of the event in unsigned counting numbers.
	 * @param numeric The duration of the event in unsigned counting numbers.
	 * @param enum The unit of reckoning for the event { days, months, years, etc. }.
	 */
	public function edit(
			$id, $occured_on, $occured_range, 
			$occured_duration, $occured_unit)
	{
		$data = array(
			'OccuredOn' => $occured_on,
			'OccuredRange' => $occured_range,
			'OccuredDuration' => $occured_duration,
			'OccuredUnit' => $occured_unit
		);
		
		
		$this->db->where('IdEvent', $id);
		$this->db->update('Event', $data); 

		return $this->db->insert_id();	
	}

	/**
	 * Finds an event given a date and title.
	 * 
	 * Accepts either a given Id, or a combo of {Occurance, Title}
	 * 
	 * @param numeric The Id of a given event.
	 * 
	 * @param string A date of a given event, as {YYYY-MM-DD}
	 * @param string An alias of the given event.
	 */
	public function find()
	{
		// We need a baseline query to pull info from.
		$qry_main = "SELECT IdEvent, OccuredOn, OccuredRange, ";
		$qry_main .= "OccuredDuration, OccuredUnit ";
		$qry_main .= "FROM `Event` evt "; 

		switch(func_num_args())
		{
			case 2: // Date & Title
				$date = func_get_arg(0);
				$title = func_get_arg(1);
				
				if (!is_string($date) || !is_string($title))
				{
					return FALSE;
				}
							
				$qry_main .= " INNER JOIN `EventAlias` ea ON ea.Event_IdEvent = evt.IdEvent ";
				$qry_main .= " WHERE ea.Title = ? ";
				$qry_main .= " AND evt.OccuredOn = DATE_FORMAT(?, '%Y-%m-%d')";
				
				$res = $this->db->query($qry_main, array($title, $date));
				break;
			
			case 1: // By Id.
				$id = func_get_arg(0);
				
				if (!is_numeric($id))
				{
					return FALSE;
				}
			
				$qry_main .= "WHERE IdEvent = ?";
				$res = $this->db->query($qry_main, array($id));
				break;
			
			default: // Invalid.
				return FALSE;
		}
		
		if ($res->num_rows() != 1)
		{
			return FALSE;
		}
		else 
		{
			/*
			 * Finally locate all the aliases and return them.
			 */
			$ret = $res->row();
			$ret->aliases = array();
			$ret->locations = array();
			$ret->concepts = array();

			$qry_alias = "SELECT Title FROM `EventAlias` ";
			$qry_alias .= "WHERE Event_IdEvent = ? ";
			$qry_alias .= "ORDER BY Ordering ";

			$res = $this->db->query($qry_alias, $ret->IdEvent);
			
			if ($res->num_rows() > 0)
			{
				foreach ($res->row() as $row)
				{
					$ret->aliases[] = $row;
				}
			}
			
			/*
			 * Related Locations
			 */
			$qry_locations =  "SELECT la.Location_IdLocation, la.Title ";
			$qry_locations .= "	FROM EventHasLocation ehl ";
			$qry_locations .= "	INNER JOIN ( ";
			$qry_locations .= "		SELECT Title, Location_IdLocation ";
			$qry_locations .= "		FROM LocationAlias ";
			$qry_locations .= "		GROUP BY Location_IdLocation ";
			$qry_locations .= "		ORDER BY Ordering" ;
			$qry_locations .= "	) la ON la.Location_IdLocation = ehl.Location_IdLocation ";
			$qry_locations .= "WHERE ehl.Event_IdEvent = {$ret->IdEvent}";
			
			$res = $this->db->query($qry_locations);
			
			foreach($res->result() as $row)
			{
				$ret->locations[] = array('Id' => $row->Location_IdLocation, 'Title' => $row->Title);
			}

			/*
			 * Related Concepts
			 */
			$qry_concepts  = "SELECT ehc.Concept_IdConcept, ca.Content ";
			$qry_concepts .= "FROM EventHasConcept ehc ";
			$qry_concepts .= "INNER JOIN ( ";
			$qry_concepts .= "    SELECT Concept_IdConcept, Content ";
			$qry_concepts .= "    FROM ConceptAlias ";
			$qry_concepts .= "    GROUP BY Concept_IdConcept ";
			$qry_concepts .= "    ORDER BY Ordering ";
			$qry_concepts .= ") ca ON ca.Concept_IdConcept = ehc.Concept_IdConcept ";
			$qry_concepts .= "WHERE ehc.Event_IdEvent = {$ret->IdEvent}";
			
			$res = $this->db->query($qry_concepts);
			
			foreach($res->result() as $row)
			{
				$ret->concepts[] = array('Id' => $row->Concept_IdConcept, 'Title' => $row->Content);
			}			
		}
		
		return $ret;
	}
	
	/**
	 * Returns a list of all events, with further indication of current relations.
	 */
	public function related_concepts($event_id)
	{

		$qry_concepts  = "SELECT con.IdConcept, ca.Content, IFNULL(loj.Mapped,0) AS Mapped ";
		$qry_concepts .= "FROM Concept con ";
		$qry_concepts .= "INNER JOIN ( ";
		$qry_concepts .= "    SELECT Content, Concept_IdConcept ";
		$qry_concepts .= "    FROM ConceptAlias ";
		$qry_concepts .= "    GROUP BY Concept_IdConcept ";
		$qry_concepts .= "    ORDER BY Ordering ";
		$qry_concepts .= ") ca ON ca.Concept_Idconcept = con.IdConcept ";
		$qry_concepts .= "LEFT OUTER JOIN ( ";
		$qry_concepts .= "    SELECT Concept_IdConcept, GROUP_CONCAT(ehc.Event_IdEvent) AS Mapped ";
		$qry_concepts .= "    FROM EventHasConcept ehc ";
		$qry_concepts .= "    GROUP BY ehc.Concept_IdConcept ";
		$qry_concepts .= ") loj ON con.IdConcept = loj.Concept_IdConcept ";

		$res = $this->db->query($qry_concepts);
		
		return ($res->num_rows() > 0) ? $res->result() : FALSE;
	}
	
	/**
	 * Updates the events associated to the provided location.
	 * 
	 * @param numeric The location Id to update.
	 * @param array The related events to associate to the location.
	 */
	public function relate_concepts($event_id, $new_relations)
	{
		/*
		 * Remove ALL existing location records.
		 */
		$qry_delete = "DELETE FROM EventHasConcept WHERE Event_IdEvent = {$event_id}";
		$this->db->query($qry_delete);
		
		/*
		 * Add records back in.
		 */
		 foreach ($new_relations as $k => $v)
		 {
		 	$this->db->insert('EventHasConcept', array(
		 		'Concept_IdConcept' => $v,
		 		'Event_IdEvent' => $event_id
		 	)); 
		 }
	}


	/**
	 * Updates the events associated to the provided location.
	 * 
	 * @param numeric The location Id to update.
	 * @param array The related events to associate to the location.
	 */
	public function relate_locations($event_id, $new_relations)
	{
		/*
		 * Remove ALL existing location records.
		 */
		$qry_delete = "DELETE FROM EventHasLocation WHERE Event_IdEvent = {$event_id}";
		$this->db->query($qry_delete);
		
		/*
		 * Add records back in.
		 */
		 foreach ($new_relations as $k => $v)
		 {
		 	$this->db->insert('EventHasLocation', array(
		 		'Location_IdLocation' => $v,
		 		'Event_IdEvent' => $event_id
		 	)); 
		 }
	}
	
	/**
	 * Returns a list of all events, with further indication of current relations.
	 */
	public function related_locations($event_id)
	{

		$qry_locations  = "SELECT loc.IdLocation, la.Title, IFNULL(ehl.Mapped, 0) AS Mapped ";
		$qry_locations .= "FROM Location loc ";
		$qry_locations .= "INNER JOIN ( ";
		$qry_locations .= "    SELECT Location_IdLocation, Title ";
		$qry_locations .= "    FROM LocationAlias ";
		$qry_locations .= "    GROUP BY Location_IdLocation ";
		$qry_locations .= "    ORDER BY Ordering ";
		$qry_locations .= ") la ON la.Location_IdLocation = loc.IdLocation ";
		$qry_locations .= "LEFT OUTER JOIN ( ";
		$qry_locations .= "    SELECT Location_IdLocation, GROUP_CONCAT(Event_IdEvent) AS Mapped ";
		$qry_locations .= "    FROM EventHasLocation the ";
		$qry_locations .= "    GROUP BY Location_IdLocation ";
		$qry_locations .= ") ehl ON ehl.Location_IdLocation = loc.IdLocation";

		$res = $this->db->query($qry_locations);
		
		return ($res->num_rows() > 0) ? $res->result() : FALSE;
	}

	/**
	 * Removes a location pin from a given Timeline's map.
	 * 
	 * @param numeric The Id of the element to remove from the given timeline.
	 */
	public function remove($id)
	{
		$this->db->delete('TimelineHasEvent', 
			array('Event_IdEvent' => $id, 'Timeline_IdTimeline' => 1)
		); 	
	}
}