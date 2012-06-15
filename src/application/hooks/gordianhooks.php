<?php
class GordianHooks extends CI_Controller
{
	const MAINTENANCE_PAGE = "atlas/maintenance";
	const HOME_PAGE = "atlas/view";
	const UNCONFIGURED_PAGE = "atlas/unconfigured";
	const MAINTENANCE_FILE_PATH = "../maintenancenotice.txt";
	
	function verifyOnline()
	{
		/*
		 * Check the config database to see if the system is on.
		 */
		 $online = $this->db->get('GordianConfig', 1);
		 
		if ($online->num_rows() == 0)
		{
			redirect(GordianHooks::UNCONFIGURED_PAGE);
		}
		else /* Determine System Maintenance Status */
		{
			$online = $online->result();
			
			if ( ($online->IsActive == 0) 
				&& (uri_string() != GordianHooks::MAINTENANCE_PAGE)
			)
			{
				redirect(GordianHooks::MAINTENANCE_PAGE);			
			} 
			else if ( ($online->IsActive == 1)
				&& uri_string() == GordianHooks::MAINTENANCE_PAGE
			)
			{
				redirect(GordianHooks::HOME_PAGE);	
			}				
			
		}  /* System Maintenance Status Determined*/	
	}
}
?>