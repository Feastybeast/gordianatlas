<?php
/**
 * A reusable user registration component displayed via popup and landing page registration.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 3
 * @license GPL 3
 */

if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

/*
 * Error conditions displayed here.
 */
$this->gordian_assets->error_widget();

/*
 * Prepare variables to be used within the view.
 */
$label_email = form_label($email_label, 'Email');
$field_email = form_input(array(
					'name' => 'Email', 'id' => 'Email', 'value' => set_value('Email', '')
				));

$label_password = form_label($password_label, 'Password');
$field_password = form_password(array(
						'name' => 'Password', 'id' => 'Password'
					));

$field_submit = form_submit(array(
					'name' => 'postBack', 'value' => set_value('submitValue', $buttonLabel)
				));
				
$form_header = heading($formHeader, 3);

$need_register_link = anchor('auth/register', $registerLinkText);
$forgotten_password_link = anchor('auth/forgotten', $forgottenLinkText);
				
/*
 * Output the main form UI in HEREDOC format.
 */
echo form_open(uri_string());
echo <<<EOF
{$form_header}

<table>
	<tr>
		<td align="right">{$label_email}</td>
		<td align="right">{$field_email}</td>
		<td></td>
	</tr>
	<tr>
		<td align="right">{$label_password}</td>
		<td align="right">{$field_password}</td>
		<td></td>
	</tr>
	<tr>
		<td></td>
		<td align="right">
			{$field_submit}
		</td>
		<td></td>
	</tr>
	<tr>
		<td></td>
		<td>{$need_register_link} or {$forgotten_password_link}</td>
	</tr>
</table>
EOF;
form_close();
?>
