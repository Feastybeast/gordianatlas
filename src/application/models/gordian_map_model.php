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
	
	/**
	 * Loads map data for the given timeline.
	 * 
	 * @param numeric The timeline to load map data for.
	 */
	public function load($timeline_id)
	{
		$query = "SELECT loc.IdLocation AS Id, loc.Lat, loc.Lng, la.Title ";
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
