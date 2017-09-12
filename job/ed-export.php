<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php
if(isset($_GET['ed']))
{
	if($_GET['ed'] == "export")
	{			
		if($_SERVER['REQUEST_METHOD'] == "POST") 
		{
			if (strpos($_SERVER['HTTP_REFERER'], get_option('siteurl')) !== false) 
			{
				global $wpdb;
				$option = isset($_REQUEST['option']) ? $_REQUEST['option'] : '';
				switch ($option) 
				{
					case "ed_unique_details":					
						$data = array();
						$data = ed_cls_subscribers::ed_subscribers_export_page("ed_unique_details");
						ed_cls_common::ed_download($data, '', 'u');
						break;
					case "ed_full_details":
						$data = array();
						$data = ed_cls_subscribers::ed_subscribers_export_page("ed_full_details");
						ed_cls_common::ed_download($data, '', 'r');
						break;
					default:
						_e('Unexpected url submit has been detected', 'email-download-link');
						break;
				}
			}
			else
			{
				_e('Unexpected url submit has been detected. Same URL is not detected.', 'email-download-link');
			}
		}
		else
		{
			_e('Unexpected url submit has been detected. Request is not posted.', 'email-download-link');
		}
	}
}
die();
?>