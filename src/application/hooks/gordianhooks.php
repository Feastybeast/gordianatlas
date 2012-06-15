<?php
class GordianHooks extends CI_Controller
{
	const MAINTENANCE_PAGE = "atlas/maintenance";
	const HOME_PAGE = "atlas/view";
	const UNCONFIGURED_PAGE = "atlas/unconfigured";
	const CONFIGURATION_PAGE = "atlas/configuration";
	const CONFIGURED_PAGE = "atlas/configured";
	const MAINTENANCE_FILE_PATH = "../maintenancenotice.txt";
	
	static private $configurationPages =  array(
		GordianHooks::UNCONFIGURED_PAGE, 
		GordianHooks::CONFIGURATION_PAGE,
		GordianHooks::CONFIGURED_PAGE
	);
	
	function verifyOnline()
	{
		/*
		 * Check the config database to see if the system is on.
		 */
		 $online = $this->db->get('GordianConfig', 1);
		
		if ($online->num_rows() == 0) /* ... the system isn't configured. */
		{

			if (!in_array(uri_string(), GordianHooks::$configurationPages))
			{
				redirect(GordianHooks::UNCONFIGURED_PAGE);			
			}
		}
		else /* System is configured, check to see if its on. */
		{
			/*
			 * Disallow Users from going to the Config Pages -- Go Home No matter what.
			 */
			if (in_array(uri_string(), GordianHooks::$configurationPages))
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