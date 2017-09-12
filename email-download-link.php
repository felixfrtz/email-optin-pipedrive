<?php
/*
Plugin Name: E-Mail Opt-In Pipedrive
Plugin URI: https://teop-media.com/
Description: This is a fork of gopiplus' email download link plugin. Additionally, it will create a contact and organization in Pipedrive from the submitted data. This plugin will send a download link to user after they have submitted a form. i.e. Send email with download link to users after signing up. There are lots of reasons you might want to send to a download link to your user after they have submitted a form.
Version: 1.0
Author: Felix Fritz
Donate link: https://teop-media.com/
Author URI: https://teop-media.com/
Text Domain: email-optin-pipedrive
Domain Path: /languages
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

/*  
Copyright 2017 Email download link (http://www.gopiplus.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'base'.DIRECTORY_SEPARATOR.'ed-defined.php');
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'ed-stater.php');

add_action('admin_menu', array( 'ed_cls_registerhook', 'ed_adminmenu' ));
register_activation_hook(ED_FILE, array( 'ed_cls_registerhook', 'ed_activation' ));
register_deactivation_hook(ED_FILE, array( 'ed_cls_registerhook', 'ed_deactivation' ));
add_action( 'widgets_init', array( 'ed_cls_registerhook', 'ed_widget_loading' ));

add_shortcode( 'email-download-link', 'emaildownload_shortcode' );

function ed_textdomain() 
{
	  load_plugin_textdomain( 'email-download-link' , false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action('plugins_loaded', 'ed_textdomain');
?>