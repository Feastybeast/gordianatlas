<?php
if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

/**
 * The maintenance state management library for the Gordian Atlas.
 * 
 * This controls, configures and validates the maintenance state data for 
 * the Atlas.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 3
 * @license GPL 3
 */

class Gordian_state
{
	// Used to store the parent path location for later use.
	private $parent_path;
	
	/**
	 * Default Constructor
	 */
	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->lang->load('gordian_setup');
	}

	/**
	 * Returns the current Atlas version.
	 * 
	 * @return mixed Either false if nonexistant, or the version number.
	 */
	public function get_atlas_version()
	{
		// Online. there is no notice. Cease processing.
		if (!file_exists($this->get_version_file()))
		{
			throw new Exception($this->CI->lang->line('gordian_setup_version_absent'));
		}
		
		return file_get_contents($this->get_version_file());
	}
	
	/**
	 * Returns the existing maintenance notice.
	 * 
	 * @return mixed Either false if nonexistant, or the file contents.
	 */
	public function get_maintenance_notice()
	{
		// Online. there is no notice. Cease processing.
		if ($this->is_online())
		{
			return FALSE;
		}
		
		return file_get_contents($this->get_status_file());
	}	
	
	/**
	 * Tests to see if the Atlas is online via the presence of the maintenance notice.
	 * 
	 * @return boolean Is online?
	 */
	public function is_online()
	{
		if (!$this->is_writable())
		{
			throw new Exception($this->CI->lang->line('gordian_setup_cannot_write'));	
		}
		else
		{
			return  !file_exists($this->get_status_file());
		}
	}
	
	/**
	 * Verifies that the system is properly setup.
	 * 
	 * 
	 */
	public function is_setup()
	{
		try 
		{
			$this->get_atlas_version();
		} 
		catch (Exception $e) 
		{
			return FALSE;
		}
		
		return TRUE;
	}
	
	/**
	 * Disables maintenance mdoe of the Atlas by removing the notice file.
	 */
	public function remove_maintenance()
	{
		if (!$this->is_writable())
		{
			throw new Exception($this->CI->lang->line('gordian_setup_cannot_write'));
		}
		
		// Find the state file we need 
		$maintenance_file = $this->get_status_file();
		
		return (!file_exists($maintenance_file)) ? TRUE : unlink($maintenance_file);		
	}
	
	/**
	 * Enables the maintenance mode of the site by placing a notice file on the system.
	 * 
	 * @param $maintenance_message The message to display to end users.
	 */
	public function set_maintenance($maintenance_message)
	{
		if (!$this->is_writable())
		{
			throw new Exception($this->CI->lang->line('gordian_setup_cannot_write'));
		}

		// Writes and notifies the system.
		$file_handler = fopen($this->get_status_file(), 'w+');
		$res = fwrite($file_handler, $maintenance_message);
		
		return (is_numeric($res)) ? TRUE : FALSE;
	}
	
	public function set_version($version)
	{
		/*
		 * Is the version appropriate, and can the file be updated?
		 */
		if (!is_numeric($version))
		{
			return FALSE;
		}
		else if (!$this->is_writable())
		{
			return FALSE;
		}
		
		$file_handler = fopen($this->get_version_file(), 'w+');
		$res = fwrite($file_handler, $version);
		
		return (is_numeric($res)) ? TRUE : FALSE;
	}
	
	/**
	 * @return String The Canonical location of the gordian status file.
	 */
	private function get_status_file()
	{
		return $this->get_parent_path() . DIRECTORY_SEPARATOR . "gordian_status.txt";
	}

	/**
	 * Returns the parent (hopefully web-inaccessable) directory of 
	 * the Gordian Atlas installation.
	 * 
	 * @return String The parent directory of the current installation.
	 */
	private function get_parent_path()
	{
		if (!$this->parent_path)
		{
			$this->parent_path = pathinfo(getcwd());
			$this->parent_path = $this->parent_path['dirname'];		
		}
		
		return $this->parent_path;
	}
	
	/**
	 * Returns the system version of the Gordian Atlas.
	 * 
	 * @return String The system version of the Gordian Atlas.
	 */
	private function get_version_file()
	{
		return $this->get_parent_path() . DIRECTORY_SEPARATOR . "gordian_version.txt";
	}
	
	/**
	 * Verifies that the system is capable of writing the gordian_status file.
	 * 
	 * @return boolean If the system can write the config file.
	 */
	private function is_writable()
	{			
		$config = $this->get_status_file();
		$version = $this->get_version_file();
		
		if	(file_exists($config) && file_exists($version))
		{
			return (is_writable($config) && is_writable($version));
		}
		else
		{
			return is_writable($this->get_parent_path());
		}
	}	
}