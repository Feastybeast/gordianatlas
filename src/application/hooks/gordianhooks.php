<?php
if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

/**
 * This component acts vaugely as a Pre-Processing / Application Wide Controller
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 2
 * @license GPL 3
 */
class GordianHooks extends CI_Controller
{
	/**
	 * This method handles two major functions: a pre-setup routine, and maintenance screening.
	 * 
	 */
	function verifyOnline()
	{
		/*
		 * Check the config database to see if the system is on.
		 */
		$this->load->config('gordian');
		$this->load->library('Gordian_state');

		$site_state = $this->gordian_state->is_online();
		
		$the_home_page = $this->config->item('gordian_uri_primary');
		$the_maint_page = $this->config->item('gordian_uri_maint');
		
		if (!$this->gordian_state->is_setup())
		{
			if (FALSE === strpos(uri_string(), 'setup'))
			{
				redirect('setup');			
			}
		}		
		else /* System is configured, check to see if its on. */
		{
			/*
			 * Disallow Users from going to the Config Pages -- Go Home No matter what.
			 */
			if (TRUE === strpos(uri_string(), 'setup'))
			{
				redirect($the_home_page);
			}
			
			$is_online = $this->gordian_state->is_online();
			
			/*
			 * Is the system online? Act accordingly.
			 */
			if ( !$is_online && (uri_string() != $the_maint_page) )
			{
				redirect($the_maint_page);			
			} 
			else if ( $is_online && uri_string() == $the_maint_page )
			{
				redirect($the_home_page);	
			}							
		}
	}
}
?>