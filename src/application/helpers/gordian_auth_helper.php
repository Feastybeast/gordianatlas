<?php
/**
 * Helper functions for the Gordian Authorization Library.
 * 
 * @author Jay Ripley <riplja@metrostate.edu>
 * @since Elaboration 3
 * @license GPL 3
 */

if (!defined('BASEPATH')) 
{
	exit('No direct script access allowed');
}

if (!function_exists('gordian_auth_user_widget'))
{
	function gordian_auth_user_widget($config = array())
	{
		/*
		 * Need to load up a substantial amount of behavior.
		 */
		$CI =& get_instance();
		$CI->load->helper('html');
		$CI->lang->load('gordian_auth');
		$CI->lang->load('gordian');
		
		/*
		 * Boilerplate Error Widget output.
		 */
		if (strlen(validation_errors()) > 0)
		{	
			echo '<fieldset>';
			echo '<legend>' . $CI->lang->line('gordian_auth_widget_header') . '</legend>';
			echo validation_errors();
			echo '</fieldset>';
		}
		
		/*
		 * Header Details
		 */
		$header_data = (array_key_exists('header', $config)) 
			? $config['header'] 
			: $CI->lang->line('gordian_auth_default_hdr'); 
		$header_label = heading($header_data, 3); 
		
		/*
		 * Email Field Details
		 */
		$email_data = (array_key_exists('email', $config) && $config['email'] != FALSE)
			? $config['email'] 
			: $CI->lang->line('gordian_auth_email_label');
		$email_label = form_label($email_data, 'Email');
		$email_field = form_input(array(
							'name' => 'Email', 
							'id' => 'Email', 
							'value' => set_value('Email', '')
						));
		
		/*
		 * Nickname field details
		 */
		$nickname_data = (array_key_exists('nickname', $config))
			? $config['nickname'] 
			: $CI->lang->line('gordian_auth_nickname_label');
		$nickname_label = form_label($nickname_data, 'Nickname');
		$nickname_field = form_input(array(
							'name' => 'Nickname', 
							'id' => 'Nickname', 
							'value' => set_value('Nickname', '')
						));
		
		/*
		 * Password field details
		 */
		$password_data = (array_key_exists('password', $config) && $config['password'] != FALSE)
			? $config['password'] 
			: $CI->lang->line('gordian_auth_password_label');
		$password_label = form_label($password_data, 'Password');
		$password_field = form_password(array(
								'name' => 'Password', 
								'id' => 'Password'
							));
		
		/*
		 * Confirmation field details
		 */
		$confirm_data = (array_key_exists('confirm', $config) && $config['confirm'] != FALSE)
			? $config['confirm'] 
			: $CI->lang->line('gordian_auth_confirm_label');
		$confirm_label = form_label($confirm_data, 'confirm');
		$confirm_field = form_password(array(
							'name' => 'Confirm', 'id' => 'Confirm'
						));
		
		/*
		 * Submit Field Details
		 */
		$submit_label = (array_key_exists('button', $config))
			? $config['button'] 
			: $CI->lang->line('gordian_auth_default_btn');
		$submit_field = form_submit(array(
							'name' => 'postBack', 'value' => set_value('submitValue', $submit_label)
						));
						
		/*
		 * Register new account link
		 */
		$register_label = (array_key_exists('register', $config) && $config['register'] != FALSE)
			? $config['register'] 
			: $CI->lang->line('gordian_auth_register_lnk');
		$register_link = anchor('auth/register', $register_label);

		/*
		 * Register new account link
		 */
		$login_label = (array_key_exists('login', $config) && $config['login'] != FALSE)
			? $config['login'] 
			: $CI->lang->line('gordian_auth_login_lnk');
		$login_link = anchor('auth/login', $login_label);

		
		/*
		 * Forgotten password link
		 */
		$forgotten_label = (array_key_exists('forgot', $config) && $config['forgot'] != FALSE) 
			? $config['forgot'] 
			: $CI->lang->line('gordian_auth_recovery_lnk');
		$forgotten_link = anchor('auth/forgotten', $forgotten_label);
		
		/*
		 * Miscellaneous strings required ...
		 */
		$LABEL_SUFFIX = $CI->lang->line('gordian_label_suffix');
		
		/*
		 * Output the main UI. Note there is no way to supress the Email and Password fields.
		 */
		if (!(array_key_exists('header', $config) && $config['header'] === FALSE))
		{
			echo $header_label;	
		}
		
		echo form_open(uri_string());
		echo '<table>';

		echo '  <tr>';
		echo '		<td align="right">' . $email_label . $LABEL_SUFFIX . '</td>';
		echo '		<td align="right" width="1">'. $email_field .'</td>';
		echo '</tr>';


		if (!(array_key_exists('nickname', $config) && $config['nickname'] == FALSE))
		{
			echo '	<tr>';
			echo '		<td align="right">' . $nickname_label . $LABEL_SUFFIX . '</td>';
			echo '		<td align="right" width="1">' . $nickname_field . "<br />";
			echo '<span class="sub">' . $CI->lang->line('gordian_auth_nickname_blank_notice') . '</span>';
			echo '		</td>';
			echo '	</tr>';
		}
		
		echo '	<tr>';
		echo '		<td align="right">' . $password_label . $LABEL_SUFFIX . '</td>';
		echo '		<td align="right" width="1">' . $password_field. '</td>';
		echo '	</tr>';
		
		if (!(array_key_exists('confirm', $config) && $config['confirm'] == FALSE))
		{
			echo '	<tr>';
			echo '		<td align="right">' . $confirm_label . $LABEL_SUFFIX . '</td>';
			echo '		<td align="right" width="1">' . $confirm_field . '</td>';
			echo '	</tr>';
		}
			
		echo '	<tr>';
		echo '		<td></td>';
		echo '		<td align="right">'. $submit_field . '</td>';
		echo '	</tr>';
		echo '	<tr>';
		echo '		<td colspan="2" align="right">';

		if (!(array_key_exists('register', $config) && !$config['register']))
		{
			echo $register_link;		
		}
		
		echo '&nbsp;';
		
		if (!(array_key_exists('login', $config) && !$config['login']))
		{
			echo $login_link;		
		}
		
		echo '&nbsp;';
		
		if (!(array_key_exists('forgot', $config) && !$config['forgot']))
		{
			echo $forgotten_link;
		}
		
		echo '		</td>';
		echo '	</tr>';
		echo '</table>';
		echo form_close();	
	}
}