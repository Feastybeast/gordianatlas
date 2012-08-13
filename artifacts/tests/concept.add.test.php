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

// facsimile for testing code as it is developed. Clls method to be tested.
$this->db->query("DELETE FROM wikipagerevision WHERE 1 = 1 limit 10");
$this->db->query("DELETE FROM timelineconcepthaswikipage WHERE 1 = 1 limit 10");
$this->db->query("DELETE FROM wikipage WHERE 1 = 1 limit 10");
$this->db->query("DELETE FROM conceptalias WHERE 1 = 1 limit 10");
$this->db->query("DELETE FROM concept WHERE 1 = 1 limit 10");

echo form_open('concept/add');  //Start of dynamically generated html page

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