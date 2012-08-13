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
class Gordian_person_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('gordian_wiki');
	}
	
	/**
	 * Adds a new person to the database.
	 * 
	 * @param array The criteria to add to the person.
	 * 
	 * @return mixed The Id of the newly added person, or false.
	 */
	public function add($criteria)
	{
		//	Preparing a new ID to use
		$pairs = array(
			'BirthLocation' => $criteria['lob'],
			'BirthEvent' => DateTime::createFromFormat('m/d/Y', $criteria['dob'])->format('Y-m-d'),
			'DeathLocation' => $criteria['lod'],
			'DeathEvent' => DateTime::createFromFormat('m/d/Y', $criteria['dod'])->format('Y-m-d')
		);
		
		$this->db->insert("Person", $pairs);
		$person_id = $this->db->insert_id();

		//	Going to add a new alias to Database
		$this->db->insert("PersonAlias",array("Person_IdPerson"=>$person_id,"Title"=>$criteria['name']));
		
		//	Going to add a new wiki page to Database
		$wiki_id = $this->gordian_wiki->add($criteria['name'], $criteria['biography']);
		$this->gordian_wiki->associate_person(1,$person_id,$wiki_id);
		
		return $person_id;		
	}
	
	/**
	 * Attaches an event to the given personality.
	 * 
	 * @param numeric The person to associate.
	 * @param numeric the event to attribute them to.
	 * 
	 * @return boolean Indicates success of operation.
	 */
	public function attach_event($person_id, $event_id)
	{
		return $this->db->insert('EventHasPerson', array(
			'Person_IdPerson' => $person_id,
			'Event_IdEvent' => $event_id
		));
	}
	
	public function edit($id, $criteria)
	{
		//	Preparing a new ID to use
		$person_values = array(
			$criteria['lob'],
			DateTime::createFromFormat('m/d/Y', $criteria['dob'])->format('Y-m-d'),
			$criteria['lod'],
			DateTime::createFromFormat('m/d/Y', $criteria['dod'])->format('Y-m-d'),
			$id			
		);

		$qry_update  = "UPDATE Person SET ";
		$qry_update .= "BirthLocation = ?, BirthEvent = ?, DeathLocation = ?, DeathEvent = ? ";
		$qry_update .= "WHERE IdPerson = ? ";

		$res = $this->db->query($qry_update, $person_values);

		//	Going to add a new alias to Database
		$check_alias = "SELECT IdPersonAlias FROM PersonAlias WHERE Title = ? AND Person_IdPerson = ? ";
		$res = $this->db->query($check_alias, array($criteria['name'], $id));

		if ($res->num_rows() == 0)
		{
			$this->db->insert("PersonAlias", array("Person_IdPerson"=>$id, "Title"=> $criteria['name']));
		}		
		
		//	Going to add a new wiki page to Database
		$wiki_reference = $this->gordian_wiki->referenced_by('person', $id);
		$this->gordian_wiki->revise($wiki_reference->IdWikiPage, $criteria['biography']);
		
		return TRUE;
	}
	
	/**
	 * Locates a person in the database.
	 * 
	 * @param mixed Either an ID or Alias
	 * 
	 * @return mixed The object containing the record, or false.
	 */
	public function find($criteria)
	{
		/*
		 * Default values for the find mechanism.
		 */
		 $ret = FALSE;
		
		/*
		 * Main Query details
		 */
		$qry_person = "SELECT IdPerson, BirthLocation, BirthEvent, DeathEvent, DeathLocation ";
		$qry_person .= "FROM Person "; 
		
		if (is_numeric($criteria))
		{
			$qry_person .= "WHERE IdPerson = ?";
		}
		else if (is_string($criteria))
		{
			$qry_person .= "INNER JOIN PersonAlias pa ON pa.Person_IdPerson = IdPerson ";
			$qry_person .= "WHERE pa.Title = ?";
		}

		/*
		 * Run the main query.
		 */
		$res = $this->db->query($qry_person, array($criteria));
		
		if (($res->num_rows() == 0))
		{
			return $ret;
		}

		$ret = $res->row();
		$ret->aliases = array();
		$ret->events = array();
		
		/*
		 * Aliases
		 */
		$qry_aliases = "SELECT Title FROM PersonAlias WHERE Person_IdPerson = ? ORDER BY Ordering";
		 
		$res = $this->db->query($qry_aliases, array($ret->IdPerson)); 
		
		if ($res->num_rows() > 0)
		{
			foreach($res->row() as $k => $v)
			{
				$ret->aliases[] = $v;
			}
		}

		/*
		 * Related Events
		 */
		$qry_events =  "SELECT ea.Event_IdEvent, ea.Title ";
		$qry_events .= "	FROM EventHasPerson ehp ";
		$qry_events .= "	INNER JOIN ( ";
		$qry_events .= "		SELECT Title, Event_IdEvent ";
		$qry_events .= "		FROM EventAlias ";
		$qry_events .= "		GROUP BY Event_IdEvent ";
		$qry_events .= "		ORDER BY Ordering" ;
		$qry_events .= "	) ea ON ea.Event_IdEvent = ehp.Event_IdEvent ";
		$qry_events .= "WHERE ehp.Person_IdPerson = ?";
		
		$res = $this->db->query($qry_events, array($ret->IdPerson));
		
		foreach($res->result() as $row)
		{
			$ret->events[] = array('Id' => $row->Event_IdEvent, 'Title' => $row->Title);
		}

		return $ret;	
	}
	
	public function related_locations()
	{
		$qry_locations  = "SELECT loc.IdLocation, la.Title ";
		$qry_locations .= "FROM Location loc ";
		$qry_locations .= "INNER JOIN ( ";
		$qry_locations .= "    SELECT Location_IdLocation, Title ";
		$qry_locations .= "    FROM LocationAlias ";
		$qry_locations .= "    GROUP BY Location_IdLocation ";
		$qry_locations .= "    ORDER BY Ordering ";
		$qry_locations .= ") la ON la.Location_IdLocation = loc.IdLocation ";
		
		$res = $this->db->query($qry_locations);
		
		return ($res->num_rows() > 0) ? $res->result() : FALSE;		
	}
}