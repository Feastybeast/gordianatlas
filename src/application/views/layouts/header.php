<!DOCTYPE HTML SYSTEM>
<html>
	<head>
		<title>The Gordian Atlas:</title>
<?php 
	if(isset($assets))
	{
		if (isset($assets['meta']))
		{
			foreach ($assets['meta'] as $k => $v)
			{
				
			}			
		}
		
		if (isset($assets['headerscripts']))
		{
			foreach ($assets['headerscripts'] as $k => $v)
			{
				echo '<script src="'.$v.'" type="text/javascript"></script>' . "\n";
				
			}			
		}
		
		if (isset($assets['stylesheets']))
		{
			foreach ($assets['stylesheets'] as $k => $v)
			{
				echo '<link rel="stylesheet" href="'.$v.'">' . "\n";
			}
		}
	}
?>		
	</head>
	<body>