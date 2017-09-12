<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php
if(isset($_GET['ed']))
{
	if($_GET['ed'] == "download")
	{
		$blogname 	= get_option('blogname');
		$noerror 	= true;
		$home_url 	= home_url('/');
		
		// Load query string
		$guid = isset($_GET['guid']) ? sanitize_text_field($_GET['guid']) : '';
		
		// Load default settings
		$data = array();
		$data = ed_cls_settings::ed_setting_select(1);
		
		// Check errors in the query string
		if ( $guid == '' )
		{
			$noerror = false;
		}
		else
		{
			$noerror = ed_cls_security::ed_check_guid_download_link($guid);
		}
		
		if($noerror)
		{
			$dashposition = strpos($guid, "--");
			if ($dashposition == 34) 
			{		
				$guiddownload = substr($guid, 0, 34); 
				$guidemail = substr($guid, 36, 36); 
				
				$result = ed_cls_downloads::ed_download_link_count_downloadid($guiddownload);
				if ($result != '1')
				{
					$message = esc_html(stripslashes($data['ed_c_expiredlinkcontant']));
					$message = str_replace("\r\n", "<br />", $message);
					echo $message;
				}
				else
				{
					$data = array();
					$data = ed_cls_downloads::ed_download_link_view(0, $guiddownload);
					$downloadurl = $home_url . "?ed=downloads&guid=".$guid;
					?>
					<html>
					<head>
					<title><?php echo $blogname; ?></title>
					<meta http-equiv="refresh" content="5; url=<?php echo $downloadurl; ?>" />
					<script type="text/javascript">
					var ss = 10;
					function countdown() 
					{
						ss = ss-1;
						if (ss < 0) 
						{
							// No action required.
						}
						else 
						{
							document.getElementById("countdown").innerHTML = ss;
							window.setTimeout("countdown()", 1000);
						}
					}
					</script>
					</head>
					<body onLoad="countdown()">
						<?php _e('Your download should automatically begin in a few seconds.', 'email-download-link'); ?>
						<span style="font-weight:bold;" id="countdown"></span>
					</body>
					</html>
					<?php
				}
			}
			else
			{
				$message = esc_html(stripslashes($data['ed_c_invalidlinkcontant']));
				$message = str_replace("\r\n", "<br />", $message);
				echo $message;
			}
		}
		else
		{
			$message = esc_html(stripslashes($data['ed_c_invalidlinkcontant']));
			$message = str_replace("\r\n", "<br />", $message);
			echo $message;
		}
	}
}
die();
?>