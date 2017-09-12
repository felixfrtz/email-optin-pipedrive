<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php
class ed_cls_default
{	
	public static function ed_pluginconfig_default()
	{
		global $wpdb;
		$prefix = $wpdb->prefix;
		
		$result = ed_cls_settings::ed_setting_count(0);
		if ($result == 0)
		{
			$admin_email = get_option('admin_email');
			$blogname = get_option('blogname');
			
			if($admin_email == "")
			{
				$admin_email = "admin@gmail.com";
			}
			
			$ed_guid = ed_cls_common::ed_generate_guid();
			$home_url = home_url('/');
			$cronurl = $home_url . "?ed=cron&guid=". $ed_guid;
			
			$ed_c_fromname 			= "Admin";
			$ed_c_fromemail 		= $admin_email; 
			$ed_c_mailtype 			= "WP HTML MAIL"; 
			$ed_c_adminmailoption 	= "YES"; 
			$ed_c_adminemail 		= $admin_email; 
			$ed_c_adminmailsubject 	= $blogname . " : Download link from Email Download Link plugin";
			$ed_c_adminmailcontant 	= "Hi Admin, \r\n\r\nWe have received a new download request. \r\n\r\nEmail: ###EMAIL### \r\nName : ###NAME### \r\n\r\nThank You\r\n".$blogname;
			$ed_c_usermailoption 	= "YES"; 
			$ed_c_usermailsubject 	= $blogname . " : Requested download link from Email Download Link plugin.";
			$ed_c_usermailcontant 	= "Hi ###NAME###, \r\n\r\nThank you for requesting your download. Here is the download for <a href='###DOWNLOADLINK###'>###TITLE###</a> that you requested. \r\n\r\nPlease note that this link will be valid only for few days. \r\n\r\nThank You\r\n".$blogname; 
			$ed_c_downloadstart 	= "";
			$ed_c_downloadpgtxt		= "";
			$ed_c_pipedrivekey 		= "";
			$ed_c_pipedrivedomain 	= "";
			$ed_c_cronurl 			= $cronurl;
			$ed_c_cronmailcontent 	= "Hi Admin, \r\n\r\nCron URL has been triggered successfully on ###DATE###. And your download link urls are refreshed. \r\n\r\nThank You\r\n".$blogname;
			$ed_c_expiredlinkcontant 	= "Sorry but your download link has expired. Please generate new download link from the blog.";
			$ed_c_invalidlinkcontant 	= "Invalid download link!";
					
			$sSql = $wpdb->prepare("INSERT INTO `".$prefix."ed_pluginconfig` 
					(`ed_c_fromname`,`ed_c_fromemail`, `ed_c_mailtype`, `ed_c_adminmailoption`, `ed_c_adminemail`, `ed_c_adminmailsubject`,
					`ed_c_adminmailcontant`,`ed_c_usermailoption`, `ed_c_usermailsubject`, `ed_c_usermailcontant`, `ed_c_downloadstart`, 
					`ed_c_downloadpgtxt`,`ed_c_pipedrivekey`, `ed_c_pipedrivedomain`, `ed_c_cronurl`, `ed_c_cronmailcontent`, `ed_c_expiredlinkcontant`, `ed_c_invalidlinkcontant`)
					VALUES(%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", 
					array($ed_c_fromname,$ed_c_fromemail, $ed_c_mailtype, $ed_c_adminmailoption, $ed_c_adminemail, $ed_c_adminmailsubject,
					$ed_c_adminmailcontant, $ed_c_usermailoption, $ed_c_usermailsubject, $ed_c_usermailcontant, $ed_c_downloadstart, 
					$ed_c_downloadpgtxt, $ed_c_pipedrivekey, $ed_c_pipedrivedomain, $ed_c_cronurl, $ed_c_cronmailcontent, $ed_c_expiredlinkcontant, $ed_c_invalidlinkcontant));
			
			$wpdb->query($sSql);
		}
		return true;
	}
	
	public static function ed_downloads_default()
	{
		global $wpdb;
		$prefix = $wpdb->prefix;
		
		$result = ed_cls_downloads::ed_download_link_count(0, "");
		if ($result == 0)
		{
			$ed_form_guid 			= ed_cls_common::ed_generate_guid();
			$ed_form_downloadid 	= ed_cls_common::ed_generate_guid();
			$ed_form_title 			= "Sample download link";
			$ed_form_description	= "This is sample download link form from email download link plugin.";
			$ed_form_downloadurl	= ED_URL . "sample/sample.png";
			$request_file_path		= ED_URL . "sample/sample.png";
			$ed_form_expirationtype	= "Never";
			$ed_form_expirationdate	= "9999-12-31";
			$ed_form_status			= "Published";
			
			$sSql = $wpdb->prepare("INSERT INTO `".$prefix."ed_downloadform` (`ed_form_guid`,
			`ed_form_title`, `ed_form_description`, `ed_form_downloadurl`,  `ed_form_downloadabspath`, `ed_form_downloadcount`, 
			`ed_form_expirationtype`, `ed_form_expirationdate`, `ed_form_status`, `ed_form_downloadid`) VALUES(%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", 
			array($ed_form_guid, $ed_form_title , $ed_form_description, $ed_form_downloadurl, $request_file_path, 0, 
			$ed_form_expirationtype, $ed_form_expirationdate, $ed_form_status, $ed_form_downloadid));
			
			$wpdb->query($sSql);
		}
	}
}
?>