<?php
/**
 * The success screen for registering a new account.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 3
 * @license GPL 3
 */

if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}
?>

<?php echo form_open('/map/add'); ?>
	<input type="hidden" name="name" value="Tunguska Crater" />
	<input type="hidden" name="lat" value="60.916667" />
	<input type="hidden" name="lng" value="101.95" />
	<input type="hidden" name="description" value="This is the first bit of information about the Tunguska crater, where the epynomous event happened." />
	<input type="submit" />
<?php echo form_close(); ?>