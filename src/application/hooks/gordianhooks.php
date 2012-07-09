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
	const MAINTENANCE_PAGE = "atlas/maintenance";
	const HOME_PAGE = "atlas/view";
	
	/**
	 * This method handles two major functions: a pre-setup routine, and maintenance screening.
	 * 
	 */
	function verifyOnline()
	{
		/*
		 * Check the config database to see if the system is on.
		 */
		 $online = $this->db->get('GordianConfig', 1);
		
		if ($online->num_rows() == 0) /* ... the system isn't configured. */
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
				redirect(GordianHooks::HOME_PAGE);
			}
			
			/*
			 * Is the system online? Act accordingly.
			 */
			$status = $online->row();

			if ( ($status->IsActive == 0) 
				&& (uri_string() != GordianHooks::MAINTENANCE_PAGE)
			)
			{
				redirect(GordianHooks::MAINTENANCE_PAGE);			
			} 
			else if ( ($status->IsActive == 1)
				&& uri_string() == GordianHooks::MAINTENANCE_PAGE
			)
			{
				redirect(GordianHooks::HOME_PAGE);	
			}							
		}
	}
}
?>