<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php
class ed_cls_subscribers
{
	public static function ed_subscribers_view_page($offset = 0, $limit = 0, $ed_email_id = 0, $ed_email_guid = "", $ed_email_downloadid = "")
	{
		global $wpdb;
		$prefix = $wpdb->prefix;
		$arrRes = array();
		$sSql = "SELECT * FROM `".$prefix."ed_emaillist` where ed_email_mail <> '' ";
		
		if($ed_email_id > 0)
		{
			$sSql = $sSql . " and ed_email_id=".$ed_email_id;
		}
		
		if($ed_email_guid <> "")
		{
			$sSql = $sSql . " and ed_email_guid='".$ed_email_guid."'";
		}
		
		if($ed_email_downloadid <> "")
		{
			$sSql = $sSql . " and ed_email_downloadid='".$ed_email_downloadid."'";
		}
		
		$sSql = $sSql . " order by ed_email_downloaddate desc";
		$sSql = $sSql . " LIMIT $offset, $limit";

		//echo $sSql . "<br>";
		
		$arrRes = $wpdb->get_results($sSql, ARRAY_A);
		return $arrRes;
	}
	
	public static function ed_subscribers_view_page_email($offset = 0, $limit = 0, $ed_email_mail = "")
	{
		global $wpdb;
		$prefix = $wpdb->prefix;
		$arrRes = array();
		$sSql = "SELECT * FROM `".$prefix."ed_emaillist` where ed_email_mail <> '' ";
		
		if($ed_email_mail <> "")
		{
			$sSql = $sSql . " and ed_email_mail='".$ed_email_mail. "'";
		}
		
		$sSql = $sSql . " order by ed_email_downloaddate desc";
		$sSql = $sSql . " LIMIT $offset, $limit";

		$arrRes = $wpdb->get_results($sSql, ARRAY_A);
		return $arrRes;
	}
	
	public static function ed_subscribers_count($ed_email_id = 0, $ed_email_guid = "", $ed_email_downloadid = "", $ed_email_mail = "")
	{
		global $wpdb;
		$prefix = $wpdb->prefix;
		$result = '0';
		if($ed_email_id > 0)
		{
			$sSql = $wpdb->prepare("SELECT COUNT(*) AS `count` FROM `".$prefix."ed_emaillist` WHERE `ed_email_id` = %d", array($ed_email_id));
		}
		elseif($ed_email_guid <> "")
		{
			$sSql = $wpdb->prepare("SELECT COUNT(*) AS `count` FROM `".$prefix."ed_emaillist` WHERE `ed_email_guid` = %s", array($ed_email_guid));
		}
		elseif($ed_email_downloadid <> "")
		{
			$sSql = $wpdb->prepare("SELECT COUNT(*) AS `count` FROM `".$prefix."ed_emaillist` WHERE `ed_email_downloadid` = %s", array($ed_email_downloadid));
		}
		elseif($ed_email_mail <> "")
		{
			$sSql = $wpdb->prepare("SELECT COUNT(*) AS `count` FROM `".$prefix."ed_emaillist` WHERE `ed_email_mail` = %s", array($ed_email_mail));
		}
		else
		{
			$sSql = "SELECT COUNT(*) AS `count` FROM `".$prefix."ed_emaillist`";
		}
	
		$result = $wpdb->get_var($sSql);
		return $result;
	}
	
	public static function ed_subscribers_distinct_count()
	{
		global $wpdb;
		$prefix = $wpdb->prefix;
		$result = '0';
		$sSql = "SELECT COUNT(distinct ed_email_mail) AS `count` FROM `".$prefix."ed_emaillist`";
	
		$result = $wpdb->get_var($sSql);
		return $result;
	}
	
	public static function ed_subscribers_export_page($option = "")
	{
		global $wpdb;
		$prefix = $wpdb->prefix;
		$arrRes = array();
		
		if($option == "ed_unique_details")
		{
			$sSql = "SELECT distinct(ed_email_mail) as Email";  
			$sSql = $sSql . " FROM `".$prefix."ed_emaillist` where ed_email_mail <> '' ";
		}
		elseif($option == "ed_full_details")
		{
			$sSql = "SELECT ed_email_name as Name, ed_email_mail as Email, ed_email_downloaddate as Date,";  
			$sSql = $sSql . " ed_email_downloadcount as Count, ed_email_downloadstatus as Status,ed_email_downloadid as Downloadid"; 
			$sSql = $sSql . " FROM `".$prefix."ed_emaillist` where ed_email_mail <> '' ";
		}
		else
		{
			$sSql = "SELECT distinct(ed_email_mail) as Email";  
			$sSql = $sSql . " FROM `".$prefix."ed_emaillist` where ed_email_mail <> '' ";
		}
		
		$sSql = $sSql . " order by ed_email_downloaddate desc";
		
		$arrRes = $wpdb->get_results($sSql, ARRAY_A);
		return $arrRes;
	}
	
	public static function ed_subscriber_delete_id($id = 0)
	{
		global $wpdb;
		$prefix = $wpdb->prefix;
		$sSql = $wpdb->prepare("DELETE FROM `".$prefix."ed_emaillist` WHERE `ed_email_id` = %d LIMIT 1", $id);
		$wpdb->query($sSql);
		return true;
	}
	
	public static function ed_subscriber_delete_guid($guid = "")
	{
		global $wpdb;
		$prefix = $wpdb->prefix;
		$sSql = $wpdb->prepare("DELETE FROM `".$prefix."ed_emaillist` WHERE `ed_email_guid` = %s LIMIT 1", $guid);
		$wpdb->query($sSql);
		return true;
	}
	
	public static function ed_subscriber_create($ed_txt_nm = "", $ed_txt_company = "", $ed_txt_em = "", $ed_email_form_guid = "")
	{
		global $wpdb;
		$prefix = $wpdb->prefix;
		$count = '0';
		
		//Select downloadid
		$downloads = array();
		$downloads = ed_cls_downloads::ed_downloads_form_guid($ed_email_form_guid);
		$ed_email_downloadid = $downloads['ed_form_downloadid'];
				
		$returnvalue = "";
		$currentdate = date('Y-m-d G:i:s'); 
		$sSql = $wpdb->prepare("SELECT COUNT(*) AS `count` FROM `".$prefix."ed_emaillist` WHERE `ed_email_mail` = %s and ed_email_downloadid = %s", array($ed_txt_em, $ed_email_downloadid));
		$count = $wpdb->get_var($sSql);
		
		if($count == 1)
		{
			$sSql = $wpdb->prepare("UPDATE `".$prefix."ed_emaillist` SET `ed_email_downloaddate` = %s, 
			`ed_email_downloadcount` = ed_email_downloadcount+1, `ed_email_downloadstatus` = %s WHERE ed_email_mail = %s and ed_email_downloadid = %s LIMIT 1", 
			array($currentdate, "Pending", $ed_txt_em, $ed_email_downloadid));			
			$wpdb->query($sSql);
			$returnvalue = "suss";
		}
		else
		{
			$ed_email_guid = ed_cls_common::ed_generate_guid();
			$sSql = $wpdb->prepare("INSERT INTO `".$prefix."ed_emaillist` (`ed_email_guid`, 
			`ed_email_name`, `ed_email_company`, `ed_email_mail`, `ed_email_downloaddate`, `ed_email_downloadcount`, 
			`ed_email_downloadstatus`, `ed_email_downloadid`, `ed_email_form_guid`) VALUES(%s, %s, %s, %s, %s, %d, %s, %s, %s)", 
			array($ed_email_guid, $ed_txt_nm, $ed_txt_company, $ed_txt_em, $currentdate, 0, "Pending", $ed_email_downloadid, $ed_email_form_guid ));
			$wpdb->query($sSql);
			$returnvalue = "suss";
		}
		
		//echo $sSql . "<br><br><br><br>";
		
		return $returnvalue;
	}
	
	public static function ed_subscriber_download_completed($ed_email_guid = "", $ed_email_downloadid = "")
	{
		global $wpdb;
		$prefix = $wpdb->prefix;
		$currentdate = date('Y-m-d G:i:s'); 
		
		$sSql = $wpdb->prepare("SELECT COUNT(*) AS `count` FROM `".$prefix."ed_emaillist` WHERE `ed_email_guid` = %s and ed_email_downloadid = %s", array($ed_email_guid, $ed_email_downloadid));
		$count = $wpdb->get_var($sSql);
		
		if($count == 1)
		{
			$sSql = $wpdb->prepare("UPDATE `".$prefix."ed_emaillist` SET `ed_email_downloaddate` = %s, 
			`ed_email_downloadcount` = ed_email_downloadcount+1, `ed_email_downloadstatus` = %s WHERE ed_email_guid = %s and ed_email_downloadid = %s LIMIT 1", 
			array($currentdate, "Downloaded", $ed_email_guid, $ed_email_downloadid));			
			$wpdb->query($sSql);
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public static function ed_subscriber_foremail($ed_txt_em = "", $ed_email_form_guid = "")
	{
		global $wpdb;
		$returnid = 0;
		$prefix = $wpdb->prefix;
		
		$downloads = array();
		$downloads = ed_cls_downloads::ed_downloads_form_guid($ed_email_form_guid);
		$ed_email_downloadid = $downloads['ed_form_downloadid'];
				
		$arrRes = array();
		$sSql = "SELECT * FROM `".$prefix."ed_emaillist` where ed_email_mail <> '' ";
		$sSql = $sSql . " and ed_email_mail='".$ed_txt_em. "'";
		$sSql = $sSql . " and ed_email_downloadid='".$ed_email_downloadid. "'";
		$sSql = $sSql . " LIMIT 1";
		$arrRes = $wpdb->get_results($sSql, ARRAY_A);
		
		//echo $sSql . "<br><br><br><br>";
		
		if(count($arrRes) > 0)
		{
			$returnid = $arrRes[0]["ed_email_id"];
		}
		
		return $returnid;
	}
}
?>