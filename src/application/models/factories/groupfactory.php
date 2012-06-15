<?php
/*
 * Created on Jun 15, 2012
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
class GroupFactory extends AbstractPermissable 
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function create($title, $content)
	{		
		$this->db->get_where('Group', array('Title' => $this->title));
		
		$this->db->insert('Group', $this);
	}
}
?>
