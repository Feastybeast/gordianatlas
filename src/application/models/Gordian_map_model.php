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
class Gordian_map_model extends CI_Model
{
	/**
	 * Default Constructor.
	 */
	public function __construct()
	{
		parent::__construct();
	}
	
	public function add($lat, $lng, $name)
	{
		/*
		 * We'll need a boilerplate icon for the moment.
		 */
		$this->db->insert('Icon', array('Path' => '', 'Color' => ''));
		$icon_id = $this->db->insert_id();

		/*
		 * Initially, just insert the record.
		 */
		$data = array('Lat' => $lat, 'Lng' => $lng, 'Icon_IdIcon' => $icon_id);
		$this->db->insert('Location', $data);
		$location_id = $this->db->insert_id();
		
		/*
		 * Now we need to add the location Alias ...
		 */
		 $this->add_alias($location_id, $name);
		 
		 return $location_id;
	}
	
	/**
	 * Adds an alias to an existing location
	 * 
	 * @param numeric The Id of the location to add an alias.
	 * @param string The alias to add to the location Id.
	 * 
	 * @return boolean if the alias was added or not.
	 */
	public function add_alias($loc_id, $alias)
	{
		if (!is_numeric($loc_id))
		{
			return FALSE;
		}
		
		if (!is_string($alias) || strlen($alias) == 0)
		{
			return FALSE;
		}
		
		/*
		 * Ensure the alias isn't already present.
		 */
		$data = array('Title' => $alias, 'Location_IdLocation' => $loc_id);
		
		$res = $this->db->get_where('LocationAlias', $data, 1);
		
		if ($res->num_rows() != 0)
		{
			return FALSE;
		}
		
		$this->db->insert('LocationAlias', $data);
		return TRUE;
	}
	
	/**
	 * Attaches a given location to a timeline.
	 * 
	 * @param numeric The ID of the timeline to attach to.
	 * @param numeric The ID of the timeline to attach.
	 * 
	 * @return boolean If the event is attached to the timeline.
	 */
	public function attach_timeline($location_id, $timeline_id)
	{
		$data = array(
				'Location_IdLocation' => $location_id, 
				'Timeline_IdTimeline' => $timeline_id
		);

		// Is the data already present?
		$query = $this->db->get_where('TimelineHasLocation', $data);
		
		if ($query->num_rows() > 0)
		{
			return FALSE;
		}
		
		$this->db->insert('TimelineHasLocation', $data);
		
		return ($this->db->insert_id()) ? TRUE : FALSE;
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
		$query = "SELECT loc.IdLocation AS Id, Loc.Lat, Loc.Lng ";
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

		// And the aliases...
		$qry_alias =  "SELECT Title FROM LocationAlias ";
		$qry_alias .= "WHERE Location_IdLocation = {$ret->Id} ";
		$qry_alias .= "ORDER BY Ordering DESC";

		$res = $this->db->query($qry_alias);
		
		foreach($res->row() as $row)
		{
			$ret->aliases[] = $row;
		}
		
		return $ret;
	}
	
	public function load($timeline_id)
	{
		$query = "SELECT loc.IdLocation AS Id, Loc.Lat, Loc.Lng, la.Title ";
		$query .= "FROM Location loc ";
		$query .= "INNER JOIN LocationAlias la ON la.Location_IdLocation = loc.IdLocation ";
		$query .= "INNER JOIN TimelineHasLocation thl ON ";
		$query .= "thl.Location_IdLocation = loc.IdLocation ";
		$query .= "WHERE thl.Timeline_IdTimeline = ?";
		
		$res = $this->db->query($query, array($timeline_id));
		
		return $res;
	}
}
?>
