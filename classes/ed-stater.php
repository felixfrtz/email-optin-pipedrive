<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php
require_once(ED_DIR.'classes'.DIRECTORY_SEPARATOR.'ed-register.php');
require_once(ED_DIR.'classes'.DIRECTORY_SEPARATOR.'ed-intermediate.php');
require_once(ED_DIR.'classes'.DIRECTORY_SEPARATOR.'ed-common.php');
require_once(ED_DIR.'classes'.DIRECTORY_SEPARATOR.'ed-directly.php');
require_once(ED_DIR.'classes'.DIRECTORY_SEPARATOR.'ed-pipedrive.php');
require_once(ED_DIR.'classes'.DIRECTORY_SEPARATOR.'ed-sendemail.php');
require_once(ED_DIR.'query'.DIRECTORY_SEPARATOR.'db_downloads.php');
require_once(ED_DIR.'query'.DIRECTORY_SEPARATOR.'db_settings.php');
require_once(ED_DIR.'query'.DIRECTORY_SEPARATOR.'db_subscribers.php');
require_once(ED_DIR.'query'.DIRECTORY_SEPARATOR.'db_default.php');
?>