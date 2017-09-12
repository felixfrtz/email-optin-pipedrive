<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php
class ed_cls_settings
{
	public static function ed_setting_select($id = 1)
	{
		global $wpdb;
		$prefix = $wpdb->prefix;
		$arrRes = array();
		$sSql = "SELECT * FROM `".$prefix."ed_pluginconfig` where 1=1";
		$sSql = $sSql . " and ed_c_id=".$id;
		$arrRes = $wpdb->get_row($sSql, ARRAY_A);
		return $arrRes;
	}
	
	public static function ed_setting_count($id = "")
	{
		global $wpdb;
		$prefix = $wpdb->prefix;
		$result = '0';
		if($id > 0)
		{
			$sSql = $wpdb->prepare("SELECT COUNT(*) AS `count` FROM `".$prefix."ed_pluginconfig` WHERE `ed_c_id` = %s", array($id));
		}
		else
		{
			$sSql = "SELECT COUNT(*) AS `count` FROM `".$prefix."ed_pluginconfig`";
		}
		$result = $wpdb->get_var($sSql);
		return $result;
	}
	
	public static function ed_setting_update1($data = array())
	{
		
		global $wpdb;
		$prefix = $wpdb->prefix;
		$sSql = $wpdb->prepare("UPDATE `".$prefix."ed_pluginconfig` SET 
			`ed_c_fromname` = %s, `ed_c_fromemail` = %s, `ed_c_mailtype` = %s, `ed_c_adminmailoption` = %s, 
			`ed_c_adminemail` = %s, `ed_c_adminmailsubject` = %s, `ed_c_adminmailcontant` = %s, `ed_c_usermailoption` = %s, 
			`ed_c_usermailsubject` = %s, `ed_c_usermailcontant` = %s, `ed_c_downloadstart` = %s, `ed_c_downloadpgtxt` = %s
			,`ed_c_pipedrivekey` = %s, `ed_c_pipedrivedomain` = %s, `ed_c_expiredlinkcontant` = %s, `ed_c_invalidlinkcontant` = %s
			 WHERE ed_c_id = %d	LIMIT 1", 
			array($data["ed_c_fromname"], $data["ed_c_fromemail"], $data["ed_c_mailtype"], $data["ed_c_adminmailoption"], 
			$data["ed_c_adminemail"], $data["ed_c_adminmailsubject"], $data["ed_c_adminmailcontant"], $data["ed_c_usermailoption"],
			$data["ed_c_usermailsubject"], $data["ed_c_usermailcontant"],  $data["ed_c_downloadstart"], $data["ed_c_downloadpgtxt"],$data["ed_c_pipedrivekey"], $data["ed_c_pipedrivedomain"],
			$data["ed_c_expiredlinkcontant"],  $data["ed_c_invalidlinkcontant"], 
			$data["ed_c_id"]));
		$wpdb->query($sSql);
		
		return "sus";
	}
	
	public static function ed_setting_update2($ed_c_cronmailcontent = "", $ed_c_id = 1)
	{
		
		global $wpdb;
		$prefix = $wpdb->prefix;
		$sSql = $wpdb->prepare("UPDATE `".$prefix."ed_pluginconfig` SET `ed_c_cronmailcontent` = %s WHERE ed_c_id = %d LIMIT 1", 
		array($ed_c_cronmailcontent, $ed_c_id ));
		$wpdb->query($sSql);
		
		return "sus";
	}
}
?>