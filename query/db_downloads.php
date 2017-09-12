<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php
class ed_cls_downloads
{
	public static function ed_download_link_view_page($offset = 0, $limit = 0, $id)
	{
		global $wpdb;
		$prefix = $wpdb->prefix;
		$arrRes = array();
		$sSql = "SELECT * FROM `".$prefix."ed_downloadform` where ed_form_downloadid <> '' ";
		
		if($id > 0)
		{
			$sSql = $sSql . " and ed_form_id=".$id;
		}
		
		$sSql = $sSql . " order by ed_form_id desc";
		$sSql = $sSql . " LIMIT $offset, $limit";

		$arrRes = $wpdb->get_results($sSql, ARRAY_A);
		return $arrRes;
	}
	
	public static function ed_download_link_view($ed_form_id = 0, $ed_form_guid = "")
	{
		global $wpdb;
		$prefix = $wpdb->prefix;
		$arrRes = array();
		$sSql = "SELECT * FROM `".$prefix."ed_downloadform` where 1=1";
		
		if($ed_form_id > 0)
		{
			$sSql = $sSql . " and ed_form_id=".$ed_form_id;
			$arrRes = $wpdb->get_row($sSql, ARRAY_A);
		}
		elseif($ed_form_guid <> "")
		{
			$sSql = $sSql . " and ed_form_guid='".$ed_form_guid."'";
			$arrRes = $wpdb->get_row($sSql, ARRAY_A);
		}
		else
		{
			$arrRes = $wpdb->get_results($sSql, ARRAY_A);
		}		
		return $arrRes;
	}
	
	public static function ed_downloads_downloadid($ed_form_downloadid = "")
	{
		global $wpdb;
		$prefix = $wpdb->prefix;
		$arrRes = array();
		$sSql = "SELECT * FROM `".$prefix."ed_downloadform` where 1=1";
		
		if($ed_form_downloadid <> "")
		{
			$sSql = $sSql . " and ed_form_downloadid='".$ed_form_downloadid."'";			
		}
		
		//echo "<br><br>" . $sSql . "<br><br><br><br>";
	
		$arrRes = $wpdb->get_row($sSql, ARRAY_A);
		return $arrRes;
	}
	
	public static function ed_downloads_form_guid($ed_email_form_guid = "")
	{
		global $wpdb;
		$prefix = $wpdb->prefix;
		$arrRes = array();
		$sSql = "SELECT * FROM `".$prefix."ed_downloadform` where 1=1";
		
		if($ed_email_form_guid <> "")
		{
			$sSql = $sSql . " and ed_form_guid='".$ed_email_form_guid."'";			
		}
	
		$arrRes = $wpdb->get_row($sSql, ARRAY_A);
		//echo "<br><br>" . $sSql . "<br><br><br><br>";
		
		return $arrRes;
	}
	
	public static function ed_download_link_count($id = 0, $guid = "")
	{
		global $wpdb;
		$prefix = $wpdb->prefix;
		$result = '0';
		if($id > 0)
		{
			$sSql = $wpdb->prepare("SELECT COUNT(*) AS `count` FROM `".$prefix."ed_downloadform` WHERE `ed_form_id` = %d", array($id));
		}
		elseif($guid <> "")
		{
			$sSql = $wpdb->prepare("SELECT COUNT(*) AS `count` FROM `".$prefix."ed_downloadform` WHERE `ed_form_guid` = %s", array($guid));
		}
		else
		{
			$sSql = "SELECT COUNT(*) AS `count` FROM `".$prefix."ed_downloadform`";
		}
		
		//echo $sSql;
		
		$result = $wpdb->get_var($sSql);
		return $result;
	}
	
	public static function ed_download_link_count_downloadid($guid = "")
	{
		global $wpdb;
		$prefix = $wpdb->prefix;
		$result = '0';
		$sSql = $wpdb->prepare("SELECT COUNT(*) AS `count` FROM `".$prefix."ed_downloadform` WHERE `ed_form_downloadid` = %s", array($guid));
		
		//echo $sSql . "<br>";
		
		$result = $wpdb->get_var($sSql);
		return $result;
	}
	
	public static function ed_download_link_random($count = 0)
	{
		global $wpdb;
		$prefix = $wpdb->prefix;
		$arrRes = array();
		$sSql = $wpdb->prepare("SELECT * FROM `".$prefix."ed_downloadform` ORDER BY RAND() LIMIT %d", array($count));
		$arrRes = $wpdb->get_results($sSql, ARRAY_A);
		return $arrRes;
	}
	
	public static function ed_download_link_delete_id($id = 0)
	{
		global $wpdb;
		$prefix = $wpdb->prefix;
		$sSql = $wpdb->prepare("DELETE FROM `".$prefix."ed_downloadform` WHERE `ed_form_id` = %d LIMIT 1", $id);
		$wpdb->query($sSql);
		return true;
	}
	
	public static function ed_download_link_delete_guid($guid = "")
	{
		global $wpdb;
		$prefix = $wpdb->prefix;
		$sSql = $wpdb->prepare("DELETE FROM `".$prefix."ed_downloadform` WHERE `ed_form_guid` = %s LIMIT 1", $guid);
		$wpdb->query($sSql);
		return true;
	}
	
	public static function ed_download_link_ins($data = array(), $action = "insert")
	{
		global $wpdb;
		$prefix = $wpdb->prefix;
		
		$ed_form_guid = ed_cls_common::ed_generate_guid();
		$ed_form_downloadid = ed_cls_common::ed_generate_guid();
		
		$upload_path_info = wp_upload_dir();
		$request_file_path = str_replace( $upload_path_info['baseurl'], $upload_path_info['basedir'], $data["ed_form_downloadurl"] );
		$request_file_path  = str_replace( "/", "\\", $request_file_path );
		
		if($action == "insert")
		{
			$sSql = $wpdb->prepare("INSERT INTO `".$prefix."ed_downloadform` (`ed_form_guid`,
			`ed_form_title`, `ed_form_description`, `ed_form_downloadurl`,  `ed_form_downloadabspath`, `ed_form_downloadcount`, 
			`ed_form_expirationtype`, `ed_form_expirationdate`, `ed_form_status`, `ed_form_downloadid`) VALUES(%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", 
			array($ed_form_guid, $data["ed_form_title"], $data["ed_form_description"], $data["ed_form_downloadurl"], $request_file_path, 0, 
			$data["ed_form_expirationtype"], $data["ed_form_expirationdate"], $data["ed_form_status"], $ed_form_downloadid));
		}
		elseif($action == "update")
		{
			$sSql = $wpdb->prepare("UPDATE `".$prefix."ed_downloadform` SET `ed_form_title` = %s, `ed_form_description` = %s, `ed_form_downloadurl` = %s,
			`ed_form_downloadabspath` = %s, `ed_form_expirationtype` = %s, `ed_form_expirationdate` = %s, `ed_form_status` = %s WHERE ed_form_id = %d LIMIT 1", 
			array($data["ed_form_title"], $data["ed_form_description"], $data["ed_form_downloadurl"], $request_file_path, $data["ed_form_expirationtype"], 
			$data["ed_form_expirationdate"], $data["ed_form_status"], $data["ed_form_id"]));
		}
		
		$wpdb->query($sSql);
		return true;
	}
	
	public static function ed_download_link_countadd($guid = "")
	{
		global $wpdb;
		$prefix = $wpdb->prefix;
		$sSql = $wpdb->prepare("UPDATE `".$prefix."ed_downloadform` SET `ed_form_downloadcount` = ed_form_downloadcount+1 
		WHERE ed_form_downloadid = %s LIMIT 1", array($guid));
		$wpdb->query($sSql);
		return true;
	}
	
	public static function ed_download_link_cron_refresh()
	{
		global $wpdb;
		$prefix = $wpdb->prefix;
		$arrRes = array();
		$currentdate = date('Y-m-d G:i:s'); 
		
		$sSql = "SELECT * FROM `".$prefix."ed_downloadform` where ed_form_expirationtype <> 'Never' and ed_form_status = 'Published'";
		$sSql = $sSql . " and ed_form_expirationdate > '".$currentdate."'";
		$arrRes = $wpdb->get_results($sSql, ARRAY_A);
		
		if(count($arrRes) > 0)
		{
			foreach ($arrRes as $data)
			{
				$guid = ed_cls_common::ed_generate_guid();
				$sSql = $wpdb->prepare("UPDATE `".$prefix."ed_downloadform` SET `ed_form_downloadid` = %s WHERE ed_form_id = %d LIMIT 1", array($guid, $data["ed_form_id"]));
				$wpdb->query($sSql);
			}
		}
		return true;
	}
	
}
?>