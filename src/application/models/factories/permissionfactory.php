<?php
/*
 * Created on Jun 15, 2012
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class PermissionFactory extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('date');
	}
	
	public function create()
	{
		$data = array();
		$data['UserUpdatedBy'] = 0;
		$data['OccuredOn'] = mdate("%Y-%m-%d %h:%i:%a", time());
		$data['IsArchived'] = false;
		$data['IsLocked'] = false;
		$data['IsGalleryLocked'] = false;
		$data['Journal'] = 'Record Initialized by System';
		
		
	}
}
?>
