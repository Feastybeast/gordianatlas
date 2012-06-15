<?php
/*
 * Created on Jun 14, 2012
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class Map extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function view()
	{
		$this->load->view("map/view");
	}
}
?>
