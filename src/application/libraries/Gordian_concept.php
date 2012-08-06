<?php
if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

/**
 * The Gordian Concept management library.
 * 
 * @author Rob Mann <mannro@metrostate.edu>
 * @since Elaboration 5
 * @license GPL 3
 */
class Gordian_concept
{
	// CodeIgniter Reference
	private $CI;
	
	/**
	 * Default Constructor.
	 */
	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->model('Gordian_concept_model');
		$this->CI->load->library('Gordian_wiki');
	}
	
	/**
	 * Adds a new concept to the database.
	 * 
	 * @param string The title of the concept.
	 * @param string The description of the concept.
	 */
	public function add($title, $content)
	{
		return $this->CI->Gordian_concept_model->add($title,$content);
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
	 * 
	 * @param mixed either an ID or title
	 **/
	public function find()
	{
		if (func_num_args()!=1)
		{
			return FALSE;
		}
		
		$arg=func_get_arg(0);
		
		if (!is_string($arg) && !is_numeric($arg))
		{
			return FALSE;
		}

		return $this->CI->Gordian_concept_model->find($arg);
	}
}