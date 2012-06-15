<?php 
if(isset($assets) && isset($assets['footerscripts']))
{
	foreach ($assets['footerscripts'] as $k => $v)
	{
		echo '		<script src="'.$v.'" type="text/javascript"></script>' . "\n";
		
	}		
}
?>
	</body>
</html>