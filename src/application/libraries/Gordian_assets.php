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
	// Session Flash message data.
	private $flash_message;
	
	
	public function __construct()
	{
		/*
		 * Loading Required Libraries
		 */
		$this->CI =& get_instance();
		$this->CI->config->load('gordian');

		/*
		 * Variable Prep
		 */
		$this->headerscripts = array();
		$this->footerscripts = array();
		$this->stylesheets = array();
		$this->metas = array();
		
		$this->flash_message = $this->CI->session->flashdata('message');
		
		if (strlen($this->flash_message) > 0)
		{
			$this->loadDefaultAssets();
		}
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
		if (!in_array($scriptPath, $this->headerscripts))
		{
			$this->headerscripts[] = $scriptPath;		
		}
	}

	public function addFooterScript($scriptPath)
	{
		if (!in_array($scriptPath, $this->footerscripts))
		{
			$this->footerscripts[] = $scriptPath;
		}
	}
	
	public function addStyleSheet($sheetPath)
	{
		if (!in_array($sheetPath, $this->footerscripts))
		{
			$this->stylesheets[] = $sheetPath;
		}
	}
	
	public function addMetaTag($metaContent)
	{
		if (!in_array($metaContent, $this->footerscripts))
		{	
			$this->metas[] = $metaContent;
		}
	}
	
	public function flashmessage_widget()
	{
		if (strlen($this->flash_message) > 0)
		{
			$this->CI->lang->load('gordian');
			$this->message_header = $this->CI->lang->line('gordian_message_header');
			
echo <<<EOF
<span id="GA_flashmessage">{$this->flash_message}</span>
<script type="text/javascript">
	$(document).ready(
		function() 
		{
			var GA_flashwidth = $("#GA_flashmessage").dialog("option", "width");
			var GA_browserwidth = $(document).width();
			var GA_midscreen = (GA_browserwidth / 2) - (GA_flashwidth / 2);
			
			$('#GA_flashmessage').dialog({ 
				position: [ GA_midscreen, 100 ],
				title: "{$this->message_header}"
			});
		}
	);
</script>
EOF;
		}
	}
	
	public function loadDefaultAssets()
	{
		$this->addFooterScript($this->CI->config->item('gordian_JQ'));
		$this->addFooterScript($this->CI->config->item('gordian_JQUI'));
		$this->addStyleSheet($this->CI->config->item('gordian_JQUI_CSS'));
		$this->addStyleSheet('/css/gordian.css');
		$this->addFooterScript('/js/lib/flashdata.js');		
	}
}
?>