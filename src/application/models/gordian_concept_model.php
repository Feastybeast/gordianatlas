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
	}
	
	/**
	 * Adds a new concept to the database.
	 * 
	 * @param string The title of the concept.
	 * @param string The description of the concept.
	 */
	public function add($title, $content)
	{
		//TODO: NYI
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
	public function find()
	{
		//TODO: NYI	
	}
}