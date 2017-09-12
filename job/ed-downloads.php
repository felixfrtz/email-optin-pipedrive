<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php
if(isset($_GET['ed']))
{
	if($_GET['ed'] == "downloads")
	{
		$noerror 	= true;
		$subscriber = false;
		$guid 		= isset($_GET['guid']) ? sanitize_text_field($_GET['guid']) : '';	
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
				if ($result == '1')
				{
					$data = array();
					$data = ed_cls_downloads::ed_downloads_downloadid($guiddownload);
					$downloadabspath = $data['ed_form_downloadabspath'];
					$ed_form_downloadurl = $data['ed_form_downloadurl'];

					//if (file_exists($downloadabspath)) 
					//{
						ed_cls_downloads::ed_download_link_countadd($guiddownload);
						$subscriber = ed_cls_subscribers::ed_subscriber_download_completed($guidemail, $guiddownload);
						if($subscriber)
						{
							try
							{
								$url = $ed_form_downloadurl;
								set_time_limit(0);
								$ch = curl_init();
								curl_setopt($ch, CURLOPT_URL, $url);
								curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
								curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
								curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
								$r = curl_exec($ch);
								curl_close($ch);
								header('Expires: 0'); // no cache
								header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
								header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
								header('Cache-Control: private', false);
								header('Content-Type: application/force-download');
								header('Content-Disposition: attachment; filename="' . basename($url) . '"');
								header('Content-Transfer-Encoding: binary');
								header('Content-Length: ' . strlen($r)); // provide file size
								header('Connection: close');
								echo $r;
								die();
							} 
							catch (Exception $e) 
							{
								echo "Oops.. We are getting some technical error 0.0.1. Please contact <b>Email Download Link</b> plugin author.";
							}
						}
						else
						{
							echo "Oops.. We are getting some technical error 1.0.0. Please contact <b>Email Download Link</b> plugin author.";
						}
					//}
					//else
					//{
					//	echo "Oops.. We are getting some technical error 1.0.1. Please contact <b>Email Download Link</b> plugin author.";
					//}
				}
				else
				{
					echo "Oops.. We are getting some technical error 1.0.2. Please contact <b>Email Download Link</b> plugin author.";
				}
			}
			else
			{
				echo "Oops.. We are getting some technical error 1.0.3. Please contact <b>Email Download Link</b> plugin author.";
			}
		}
		else
		{
			echo "Oops.. We are getting some technical error 1.0.4. Please contact <b>Email Download Link</b> plugin author.";
		}
	}
	else
	{
		echo "Oops.. We are getting some technical error 1.0.5. Please contact <b>Email Download Link</b> plugin author.";
	}
}
die();
?>