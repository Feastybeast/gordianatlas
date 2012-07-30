<?php 
/**
 * Main layout footer file.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 2
 * @license GPL 3
 */

if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

echo '		<div class="sub">&copy; <a href="http://code.google.com/p/gordianatlas">Gordian Atlas Project</a></div>';


foreach ($this->gordian_assets->getFooterScripts() as $k => $v)
{
	echo '				<script src="'.$v.'" type="text/javascript"></script>' . "\n";
}	

echo $this->gordian_assets->flashmessage_widget();
?>

<!-- This is the paypal code -->

<script type="text/javascript">
var amt = null;
function updateOther(txtField) 
{

  document.getElementById("rbAmountOther").checked = true;
  txtField.value = "";
}

function updateAmount() 
{
  document.getElementById("txtAmountOther").value = "";
  document.getElementsByName("amount")[0].value="";
}

function donationCheck()
{
	gatherAmount(document.donation);
	document.donation.submit();
}

function gatherAmount(form)
{
	for ( var i = 0; i < form.rbAmount.length; i++ ) 
	{
		if ( form.rbAmount[i].checked ) 
		{
		  amt = form.rbAmount[i].value;
		}
	}

  if ( form.txtAmountOther.value != "" ) 
  {
    var otherAmount = form.txtAmountOther.value;
    form.amount.value = otherAmount;
    amt = otherAmount;
  }
  
  form.amount.value = amt;
}
</script>


<form method="post" name="donation" action="https://www.paypal.com/cgi-bin/webscr" target="_blank" style="float: right;">
	<div id="rdDiv">
	  <table id="tblRadioButons">
		<tr>
		  <td>
		  	<strong>Care to donate?</strong>
		  </td>
		  <td>&nbsp;
		  </td>
		  <td >
			<input type="radio" name="rbAmount" id="rbAmount1" onclick="updateAmount();" value="5" />
			<label for="lbAmount1">5</label>
		  </td>
		  <td >
			<input type="radio" name="rbAmount" id="rbAmount2" onclick="updateAmount();" value="10" />
			<label for="lbAmount2">10</label>
		  </td>
		  <td >
			<input type="radio" name="rbAmount" id="rbAmount3" onclick="updateAmount();" value="20" />
			<label for="lbAmount3">20</label>
		  </td>
		  <td>
			<input type="radio" name="rbAmount" id="rbAmount4" onclick="updateAmount();" value="25" />
			<label for="input_amount_3">25</label>
		  </td>
		  <td>
			  <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" onclick="donationCheck();" name="submit" alt="PayPal - The safer, easier way to pay online!">		  
		  </td>
		</tr>
	  </table>
	</div>
	<div>
		<input type="hidden" name="cmd" value="_donations">
		<input type="hidden" name="business" value="coobs.her@gmail.com">
		<input type="hidden" name="lc" value="US">
		<input type="hidden" name="item_name" value="donation">
		<input type="hidden" name="amount" value="">
		<input type="hidden" name="currency_code" value="USD">
		<input type="hidden" name="no_note" value="0">
		<input type="hidden" name="currency_code" value="USD">
		<input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHostedGuest">
		<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
	</div>
</form>
<!-- This is the end of paypal code -->
	</body>
</html>