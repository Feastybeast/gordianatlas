<?php
if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

/**
 * The success screen for registering a new account.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 5
 * @license GPL 3
 */
class Gordian_location_model extends CI_Model
{
	/**
	 * Default Constructor.
	 */
	public function __construct()
	{
		parent::__construct('location');
	}
	
	/**
	 * Accepts two argument varieties (lat,lng) or (id). See params for details.
	 * 
	 * @param numeric A floating point form latitude for a location.
	 * @param numeric A floating point form longitude for a location.
	 * @param numeric An integer of which timeline the location is expected to be in.
	 * 
	 * @return mixed Either the object describing the location, or FALSE
	 */
	public function find()
	{
		if (func_num_args() < 1 || func_num_args() > 2)
		{
			return FALSE;
		}

		// Prep the Query
		$query = "SELECT loc.IdLocation AS Id, loc.Lat, loc.Lng ";
		$query .= "FROM Location loc ";
		$query .= "INNER JOIN TimelineHasLocation thl ON thl.Location_IdLocation = loc.IdLocation ";

		if (func_num_args() == 2)
		{
			$lat = func_get_arg(0);
			$lng = func_get_arg(1);
			
			$query .= "WHERE loc.Lat = {$lat} AND loc.Lng = {$lng} ";
		} 
		else // Only looking for Id.
		{
			$id = func_get_arg(0);
			
			$query .= "WHERE loc.IdLocation = {$id} ";			
		}

		$query .= "AND thl.Timeline_IdTimeline = 1";
		
		// Run the query.
		$res = $this->db->query($query);
				 
		if ($res->num_rows() != 1)
		{
			return FALSE;
		}
		
		// We have our data, now return the aliases as well...
		$ret = $res->row();
		$ret->aliases = array();
		$ret->events = array();

		// And the aliases...
		$qry_alias =  "SELECT Title FROM LocationAlias ";
		$qry_alias .= "WHERE Location_IdLocation = {$ret->Id} ";
		$qry_alias .= "ORDER BY Ordering DESC";

		$res = $this->db->query($qry_alias);
		
		foreach($res->result() as $row)
		{
			$ret->aliases[] = $row->Title;
		}
		
		/*
		 * Related Events
		 */
		$qry_events =  "SELECT ea.Event_IdEvent, ea.Title ";
		$qry_events .= "	FROM EventHasLocation ehl ";
		$qry_events .= "	INNER JOIN ( ";
		$qry_events .= "		SELECT Title, Event_IdEvent ";
		$qry_events .= "		FROM EventAlias ";
		$qry_events .= "		GROUP BY Event_IdEvent ";
		$qry_events .= "		ORDER BY Ordering" ;
		$qry_events .= "	) ea ON ea.Event_IdEvent = ehl.Event_IdEvent ";
		$qry_events .= "WHERE ehl.Location_IdLocation = {$ret->Id}";
		
		$res = $this->db->query($qry_events);
		
		foreach($res->result() as $row)
		{
			$ret->events[] = array('Id' => $row->Event_IdEvent, 'Title' => $row->Title);
		}
		
		/*
		 * Return the values
		 */
		 
		return $ret;
	}
	
	/**
	 * Updates the events associated to the provided location.
	 * 
	 * @param numeric The location Id to update.
	 * @param array The related events to associate to the location.
	 */
	public function relate_events($location_id, $new_relations)
	{
		/*
		 * Remove ALL existing location records.
		 */
		$qry_delete = "DELETE FROM EventHasLocation WHERE Location_IdLocation = {$location_id}";
		$this->db->query($qry_delete);
		
		/*
		 * Add records back in.
		 */
		 foreach ($new_relations as $k => $v)
		 {
		 	$this->db->insert('EventHasLocation', array(
		 		'Location_IdLocation' => $location_id,
		 		'Event_IdEvent' => $v
		 	)); 
		 }
	}
	
	/**
	 * Returns a list of all events, with further indication of current relations.
	 */
	public function related_events($location_id)
	{
		$qry_events  = "SELECT evt.IdEvent, ea.Title, IFNULL(ehl.Location_IdLocation, 0) AS Mapped ";
		$qry_events .= "FROM `Event` evt ";
		$qry_events .= "INNER JOIN ( ";
		$qry_events .= "    SELECT Event_IdEvent, Title ";
		$qry_events .= "    FROM EventAlias ";
		$qry_events .= "    GROUP BY Event_IdEvent ";
		$qry_events .= "    ORDER BY Ordering ";
		$qry_events .= ") ea ON ea.Event_IdEvent = evt.IdEvent ";
		$qry_events .= "INNER JOIN TimelineHasEvent the ON the.Event_IdEvent = evt.IdEvent ";
		$qry_events .= "LEFT OUTER JOIN `EventHasLocation` ehl ON evt.IdEvent  = ehl.Event_IdEvent  ";
		$qry_events .= "WHERE the.Timeline_Idtimeline = 1 ";
		$qry_events .= "GROUP BY evt.IdEvent, ea.Title";
		
		$res = $this->db->query($qry_events);
		
		return ($res->num_rows() > 0) ? $res->result() : FALSE;
	}
}