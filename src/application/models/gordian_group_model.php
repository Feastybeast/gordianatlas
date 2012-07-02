<?php
/**
 * Group management component for Gordian Atlas
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 3
 * @license GPL 3
 */

if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

class Gordian_group_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		
		$this->config->load('gordian');

		$query =  "SELECT COLUMN_TYPE ";
		$query .= " FROM INFORMATION_SCHEMA.COLUMNS ";
		$query .= " WHERE TABLE_SCHEMA = ";
		$query .= "'" . $this->config->item('gordian_db_schema') . "'"; 
		$query .= " AND TABLE_NAME = 'Group'"; 
		$query .= " AND COLUMN_NAME = 'Status'";

		$res = $this->db->query($query);		
		exit();
		
		$this->group_states = new stdClass;
	}
	
	function join_group()
	{
		
	}
	
	function create_group($title, $description, $state)
	{
		
	}
	
	function leave_group()
	{
		
	}
}