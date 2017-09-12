<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php
function ed_plugin_query_vars($vars) 
{
	$vars[] = 'ed';
	return $vars;
}
add_filter('query_vars', 'ed_plugin_query_vars');

function ed_plugin_parse_request($qstring)
{
	if (array_key_exists('ed', $qstring->query_vars)) 
	{
		$page = $qstring->query_vars['ed'];
		switch($page)
		{
			case 'download':
				require_once(ED_DIR.'job'.DIRECTORY_SEPARATOR.'ed-download.php');
				break;
			case 'downloads':
				require_once(ED_DIR.'job'.DIRECTORY_SEPARATOR.'ed-downloads.php');
				break;
			case 'export':
				require_once(ED_DIR.'job'.DIRECTORY_SEPARATOR.'ed-export.php');
				break;
			case 'cron':
				require_once(ED_DIR.'job'.DIRECTORY_SEPARATOR.'ed-cron.php');
				break;
		}
	}
}
add_action('parse_request', 'ed_plugin_parse_request');
?>