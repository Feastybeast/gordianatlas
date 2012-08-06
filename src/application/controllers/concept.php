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
class Concept extends GA_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library("Gordian_concept");
	}
	
	/**
	 * 
	 */
	public function add()
	{
		$this->db->query("DELETE FROM wikipagerevision WHERE 1 = 1 limit 10");
		$this->db->query("DELETE FROM timelineconcepthaswikipage WHERE 1 = 1 limit 10");
		$this->db->query("DELETE FROM wikipage WHERE 1 = 1 limit 10");
		$this->db->query("DELETE FROM conceptalias WHERE 1 = 1 limit 10");
		$this->db->query("DELETE FROM concept WHERE 1 = 1 limit 10");
		//$this->db->query("DELETE FROM WHERE 1 = 1 limit 10");
		$title = $this->input->post("title");
		$content = $this->input->post("content");

		if (strlen($title)>0 && strlen($content)>0)
		{		
			$concept = $this->gordian_concept->find($title);

			if(!is_object($concept))
			{
				$this->gordian_concept->add($title,$content);	
			}
		}

		if ($this->input->is_ajax_request() && $this->gordian_auth->is_logged_in())
		{
			exit("This is the add screen.");
		}
	}
	
	public function delete()
	{
		if ($this->current_record() == FALSE)
		{
			return FALSE;
		}		
	}
	
	public function edit()
	{
		if ($this->current_record() == FALSE)
		{
			return FALSE;
		}
	}
	
	public function test()
	{
		// facsimile for testing code as it is developed. Clls method to be tested.
		
		//exit("In concept/test");
		
		echo form_open('concept/add');  //Start of dynamically generated html page
		//echo <input type="submit" name="mysubmit" value="Submit Post!" /> //standard submit button

		$data = array(
              'name'	=> 'title',
              'value'	=> 'username'
            );

		echo "Concept: ";
		echo form_input($data);
		echo "<br />Description: ";
		echo form_textarea(array('name'	=> 'content'));
		echo "<br />";
		echo form_submit('','Submit');
		echo form_close();  //End of dynamically generated html page
		exit(); // needed because Code Igniter needs it 
	}
	
	public function wiki()
	{
		if ($this->current_record() == FALSE)
		{
			return FALSE;
		}		
	}
}