<?php

class Timeline extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	}
	
	function view()
	{
		$this->load->view("timeline/view");
	}
}
?>