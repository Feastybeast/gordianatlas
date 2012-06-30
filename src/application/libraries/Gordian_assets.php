<?php
/**
 * This library provides a way to handle unified data off to the view component.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 3
 * @license GPL 3
 */

if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

class Gordian_assets 
{
	private $headerscripts;
	private $footerscripts;
	private $stylesheets;
	private $metas;
	
	public function __construct()
	{
		$this->headerscripts = array();
		$this->footerscripts = array();
		$this->stylesheets = array();
		$this->metas = array();
	}
	
	
	public function getHeaderScripts()
	{
		return $this->headerscripts;
	}
	
	public function getFooterScripts()
	{
		return $this->footerscripts;
	}
	
	public function getStyleSheets()
	{
		return $this->stylesheets;	
	}
	
	public function getMetaTags()
	{
		return $this->metas;
	}
	
	public function addHeaderScript($scriptPath)
	{
		$this->headerscripts[] = $scriptPath;
	}

	public function addFooterScript($scriptPath)
	{
		$this->footerscripts[] = $scriptPath;
	}
	
	public function addStyleSheet($sheetPath)
	{
		$this->stylesheets[] = $sheetPath;
	}
	
	public function addMetaTag($metaContent)
	{
		$this->metas[] = $metaContent;
	}
	
	/**
	 * Boilerplate Error Widget output.
	 */
	public function error_widget()
	{
		$CI =& get_instance();
		$CI->lang->load('form_label');
		
		if (strlen(validation_errors()) > 0)
		{
			$op = validation_errors();
			
			echo '<fieldset>';
			echo '<legend>' . $CI->lang->line('label_widget_header') . '</legend>';
			echo validation_errors();
			echo '</fieldset>';
		}		
	}
}
?>