<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php
class ed_cls_common
{
	public static function ed_disp_status($value)
	{
		$returnstring = "";
		switch ($value) 
		{
			case "Confirmed":
				$returnstring = '<span style="color:#006600;font-weight:bold;">Confirmed</span>';
				break;
			case "Unconfirmed":
				$returnstring = '<span style="color:#FF0000">Unconfirmed</span>';
				break;
			case "Unsubscribed":
				$returnstring = '<span style="color:#999900">Unsubscribed</span>';
				break;
			case "Single Opt In":
				$returnstring = '<span style="color:#0000FF">Single Opt In</span>';
				break;
			case "Viewed":
				$returnstring = '<span style="color:#00CC00;font-weight:bold">Viewed</span>';
				break;
			case "Nodata":
				$returnstring = '<span style="color:#999900;">Nodata</span>';
				break;
			case "Disable":
				$returnstring = '<span style="color:#FF0000">Disable</span>';
				break;
			case "In Queue":
				$returnstring = '<span style="color:#FF0000">In Queue</span>';
				break;
			case "Sent":
				$returnstring = '<span style="color:#00FF00;font-weight:bold;">Sent</span>';
				break;
			case "Cron Mail":
				$returnstring = '<span style="color:#ffd700;font-weight:bold;">Cron Mail</span>';
				break;	
			case "Instant Mail":
				$returnstring = '<span style="color:#993399;">Instant Mail</span>';
				break;
			default:
       			$returnstring = $value;
		}
		return $returnstring;
	}
		
	public static function ed_generate_guid($length = 30) 
	{
		$guid = rand();
		$length = 6;
		$rand1 = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, $length);
		$rand2 = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, $length);
		$rand3 = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, $length);
		$rand4 = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, $length);
		$rand5 = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, $length);
		$rand6 = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, $length);
		$guid = $rand1."-".$rand2."-".$rand3."-".$rand4."-".$rand5;
		return $guid;
	}	
	
	public static function ed_client_os() 
	{
		$http_user_agent = $_SERVER['HTTP_USER_AGENT'];
		return $http_user_agent;
	}
	
	public static function ed_download($arrays, $filename = 'output.csv', $option) 
	{
		$string = '';
		$c=0;
		$filename = 'email-download-link-'.$option.'-'.date('Ymd_His').".csv";
		foreach($arrays AS $array) 
		{
			$val_array = array();
			$key_array = array();
			foreach($array AS $key => $val) 
			{
				$key_array[] = $key;
				$val = str_replace('"', '""', $val);
				$val_array[] = "\"$val\"";
			}
			if($c == 0) 
			{
				$string .= implode(",", $key_array)."\n";
			}
			$string .= implode(",", $val_array)."\n";
			$c++;
		}
		ob_clean();
		header('Content-type: application/ms-excel');
		header('Content-Disposition: attachment; filename='.$filename);
		echo $string;
	}
	
}

class ed_cls_security
{
	public static function ed_check_number($value) 
	{
		if(!is_numeric($value)) 
		{ 
			die('<p>Security check failed. Are you sure you want to do this?</p>'); 
		}
	}
	
	public static function ed_check_guid($value) 
	{
		$value_length1 = strlen($value);
		$value_noslash = str_replace("-", "", $value);
		$value_length2 = strlen($value_noslash);
		
		if( $value_length1 != 34 || $value_length2 != 30)
		{
			die('<p>Security check failed. Are you sure you want to do this?</p>'); 
		}
		
		if (preg_match('/[^a-z]/', $value_noslash))
		{
			die('<p>Security check failed. Are you sure you want to do this?</p>'); 
		}
	}
	
	public static function ed_check_guid_return_false($value) 
	{
		$value_length1 = strlen($value);
		$value_noslash = str_replace("-", "", $value);
		$value_length2 = strlen($value_noslash);
		$returnvalue = true;
		
		if( $value_length1 != 34 || $value_length2 != 30)
		{
			$returnvalue = false; 
		}
		
		if (preg_match('/[^a-z]/', $value_noslash))
		{
			$returnvalue = false;
		}
		
		return $returnvalue;
	}
	
	public static function ed_check_guid_download_link($value) 
	{
		$value_length1 = strlen($value);
		$value_noslash = str_replace("-", "", $value);
		$value_noslash = str_replace("--", "", $value_noslash);
		$value_length2 = strlen($value_noslash);
		$returnvalue = true;
		
		if( $value_length1 != 70 || $value_length2 != 60)
		{
			$returnvalue = false; 
		}
		
		if (preg_match('/[^a-z]/', $value_noslash))
		{
			$returnvalue = false;
		}
		
		return $returnvalue;
	}
}
?>