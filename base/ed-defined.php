<?php
$ed_plugin_name='email-download-link';
//$ed_plugin_folder_name = dirname(dirname(plugin_basename(__FILE__)));

$ed_current_folder = dirname(dirname(__FILE__));
if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
if(!defined('ED_PLUGIN_DISPLAY')) define('ED_PLUGIN_DISPLAY', "Email download link");
if(!defined('ED_DIR')) define('ED_DIR', $ed_current_folder.DS);
if(!defined('ED_URL')) define('ED_URL',plugins_url().'/email-download-link/');
if(!defined('ED_FILE')) define('ED_FILE',ED_DIR.'email-download-link.php');
if(!defined('ED_ADMINURL')) define('ED_ADMINURL', get_option('siteurl') . '/wp-admin/admin.php');
if(!defined('ED_FAV')) define('ED_FAV', 'http://www.gopiplus.com/work/2016/03/01/email-download-link-wordpress-plugin/');
if(!defined('ED_OFFICIAL')) define('ED_OFFICIAL', 'This is a fork. Check official website for more information <a target="_blank" href="'.ED_FAV.'">click here</a>');
if(!defined('ED_PLUGIN_NAME')) define('ED_PLUGIN_NAME', "email-download-link");

if(!defined('ED_MSG_01')) define('ED_MSG_01', __('Please fill in the required field.',  'email-download-link'));
if(!defined('ED_MSG_02')) define('ED_MSG_02', __('Email address seems invalid.',  'email-download-link'));
if(!defined('ED_MSG_03')) define('ED_MSG_03', __('Validation errors occurred. Please confirm the fields and submit it again.', 'email-download-link'));
if(!defined('ED_MSG_04')) define('ED_MSG_04', __('Download link sent successfully to your email address.', 'email-download-link'));
?>