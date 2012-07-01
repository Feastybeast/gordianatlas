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
 
 
$header_label = heading($headerLabel, 3); 
 
$label_email = form_label('Email Address:', 'Email');
$field_email = form_input(array(
					'name' => 'Email', 'id' => 'Email', 'value' => set_value('Email', '')
				));

$label_nickname = form_label('Nickname:', 'Nickname');
$field_nickname = form_input(array(
					'name' => 'Nickname', 'id' => 'Nickname', 'value' => set_value('Nickname', '')
				));

$label_password = form_label('Password:', 'Password');
$field_password = form_password(array(
						'name' => 'Password', 'id' => 'Password'
					));

$label_confirm = form_label('Confirm:', 'confirm');
$field_confirm = form_password(array(
					'name' => 'Confirm', 'id' => 'Confirm'
				));

$field_submit = form_submit(array(
					'name' => 'postBack', 'value' => set_value('submitValue', $buttonLabel)
				));
$field_hidden = form_hidden(array(
					'name' => 'userId', 'value' => set_value('userId', '')
				));
				

$need_login_link = anchor('auth/login', $loginLinkText);
$forgotten_password_link = anchor('auth/forgotten', $forgottenLinkText);

/*
 * Output the main form UI in HEREDOC format.
 */
echo form_open(uri_string());
echo <<<EOF
{$header_label}
<table>
	<tr>
		<td align="right">{$label_email}</td>
		<td align="right">{$field_email}</td>
		<td></td>
	</tr>
	<tr>
		<td align="right">{$label_nickname}</td>
		<td align="right">
			{$field_nickname} <br />
			<span class='sub'>(Leave blank to just show your email)</span>
		</td>
		<td></td>
	</tr>
	<tr>
		<td align="right">{$label_password}</td>
		<td align="right">{$field_password}</td>
		<td></td>
	</tr>
	<tr>
		<td align="right">{$label_confirm}</td>
		<td align="right">{$field_confirm}</td>
		<td></td>
	</tr>
	<tr>
		<td></td>
		<td align="right">
			{$field_submit}
			{$field_hidden}
		</td>
		<td></td>
	</tr>
	<tr>
		<td colspan="2" align="right">
			{$need_login_link} or {$forgotten_password_link}
		</td>
	</tr>
</table>
EOF;
form_close();
?>
