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
	 * Delete a concept from a Timeline.
	 */
	public function delete()
	{
		//TODO: NYI
	}
	
	/**
	 * Edit a concept for a timeline.
	 */
	public function edit()
	{
		//TODO: NYI
	}
	
	/**
	 * Find a concept via ID or Title.
	 */
	public function find($arg)
	{
		$query  ="SELECT IdWikiPage ";
		$query .= "FROM Concept con ";
		$query .= "INNER JOIN ConceptAlias cona ON cona.Concept_IdConcept = con.IdConcept ";
		$query .= "LEFT OUTER JOIN TimelineConceptHasWikiPage tchw ON con.IdConcept = tchw.Concept_IdConcept ";
		$query .= "LEFT OUTER JOIN WikiPage wp ON wp.IdWikiPage = tchw.WikiPage_IdWikiPage ";
			
		if (is_string($arg))
		{
			$query .= "WHERE cona.Content = ?";
		}
		else if (is_numeric($arg))
		{
			$query .= "WHERE con.IdConcept = ?";
			var_dump($query);
			var_dump($arg);
			exit();			
		}
	
		$res = $this->db->query($query,array($arg));
		
		if ($res->num_rows() == 0)
		{
				return FALSE;
		}
		
		return $this->gordian_wiki->find($res->row());
	}
}