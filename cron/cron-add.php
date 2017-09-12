<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<div class="wrap">
<?php
$ed_errors = array();
$ed_success = '';
$ed_error_found = false;

$result = ed_cls_settings::ed_setting_count(1);
if ($result != '1')
{
	?><div class="error fade"><p><strong><?php _e('Oops, selected details doesnt exist.', 'email-download-link'); ?></strong></p></div><?php
	$form = array(
		'ed_c_id' 				=> '',
		'ed_c_fromname' 		=> '',
		'ed_c_fromemail' 		=> '',
		'ed_c_mailtype' 		=> '',
		'ed_c_adminmailoption' 	=> '',
		'ed_c_adminemail' 		=> '',
		'ed_c_adminmailsubject' => '',
		'ed_c_adminmailcontant' => '',
		'ed_c_usermailoption' 	=> '',
		'ed_c_usermailsubject' 	=> '',
		'ed_c_usermailcontant' 	=> '',
		'ed_c_downloadstart' 	=> '',
		'ed_c_downloadpgtxt' 	=> '',
		'ed_c_pipedrivekey'		=> '',
		'ed_c_pipedrivedomain'	=> '',
		'ed_c_cronurl' 			=> '',
		'ed_c_cronmailcontent' 	=> ''
	);
}
else
{
	$ed_errors = array();
	$ed_success = '';
	$ed_error_found = false;
	
	$data = array();
	$data = ed_cls_settings::ed_setting_select(1);
		
	// Preset the form fields
	$form = array(
		'ed_c_id' 				=> $data['ed_c_id'],
		'ed_c_fromname' 		=> $data['ed_c_fromname'],
		'ed_c_fromemail' 		=> $data['ed_c_fromemail'],
		'ed_c_mailtype' 		=> $data['ed_c_mailtype'],
		'ed_c_adminmailoption' 	=> $data['ed_c_adminmailoption'],
		'ed_c_adminemail'		=> $data['ed_c_adminemail'],
		'ed_c_adminmailsubject' => $data['ed_c_adminmailsubject'],
		'ed_c_adminmailcontant' => $data['ed_c_adminmailcontant'],
		'ed_c_usermailoption' 	=> $data['ed_c_usermailoption'],
		'ed_c_usermailsubject' 	=> $data['ed_c_usermailsubject'],
		'ed_c_usermailcontant' 	=> $data['ed_c_usermailcontant'],
		'ed_c_downloadstart' 	=> $data['ed_c_downloadstart'],
		'ed_c_downloadpgtxt' 	=> $data['ed_c_downloadpgtxt'],
		'ed_c_pipedrivekey' 	=> $data['ed_c_pipedrivekey'],
		'ed_c_pipedrivedomain' 	=> $data['ed_c_pipedrivedomain'],
		'ed_c_cronurl' 			=> $data['ed_c_cronurl'],
		'ed_c_cronmailcontent' 	=> $data['ed_c_cronmailcontent']
	);
}

// Form submitted, check the data
if (isset($_POST['ed_form_submit']) && $_POST['ed_form_submit'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('ed_form_add');
	
	$ed_c_id 						= isset($_POST['ed_c_id']) ? sanitize_text_field($_POST['ed_c_id']) : '1';
	$ed_c_cronmailcontent 			= isset($_POST['ed_c_cronmailcontent']) ? wp_filter_post_kses($_POST['ed_c_cronmailcontent']) : '';
	$form['ed_c_cronmailcontent'] 	= $ed_c_cronmailcontent;
	
	//	No errors found, we can add this Group to the table
	if ($ed_error_found == false)
	{
		$action = "";
		$action = ed_cls_settings::ed_setting_update2($ed_c_cronmailcontent, $ed_c_id);
		
		if($action == "sus")
		{
			$ed_success = __('Details was successfully updated.', 'email-download-link');
		}
		else
		{
			$ed_error_found == true;
			$ed_errors[] = __('Oops, details not update.', 'email-download-link');
		}
	}
}

if ($ed_error_found == true && isset($ed_errors[0]) == true)
{
	?><div class="error fade"><p><strong><?php echo $ed_errors[0]; ?></strong></p></div><?php
}

if ($ed_error_found == false && strlen($ed_success) > 0)
{
	?>
	<div class="updated fade">
		<p><strong><?php echo $ed_success; ?></strong></p>
	</div>
	<?php
}
?>
<script language="javaScript" src="<?php echo ED_URL; ?>cron/cron.js"></script>
<div class="form-wrap">
	<div id="icon-plugins" class="icon32"></div>
	<h2><?php _e(ED_PLUGIN_DISPLAY, 'email-download-link'); ?></h2>
	<h3><?php _e('Cron Details', 'email-download-link'); ?></h3>
	<form name="ed_form" method="post" action="#" onsubmit="return _ed_submit()"  >
      
      <label for="tag-link"><?php _e('Cron job URL', 'email-download-link'); ?></label>
      <input name="ed_c_cronurl" type="text" id="ed_c_cronurl" value="<?php echo esc_html(stripslashes($form['ed_c_cronurl'])); ?>" maxlength="225" size="75"  />
      <p><?php _e('Please find your cron job URL. This is read only field not able to modify from admin.', 'email-download-link'); ?></p>
	  
	  <label for="tag-link"><?php _e('Admin mail content', 'email-download-link'); ?></label>
	  <textarea size="100" id="ed_c_cronmailcontent" rows="10" cols="73" name="ed_c_cronmailcontent"><?php echo esc_html(stripslashes($form['ed_c_cronmailcontent'])); ?></textarea>
	  <p><?php _e('Enter the mail content for admin. This will send whenever cron URL is triggered.', 'email-download-link'); ?><br />(Keywords: ###DATE###)</p>

      <input type="hidden" name="ed_form_submit" value="yes"/>
	  <input type="hidden" name="ed_c_id" id="ed_c_id" value="<?php echo $form['ed_c_id']; ?>"/>
      <p class="submit">
        <input name="publish" lang="publish" class="button add-new-h2" value="<?php _e('Submit', 'email-download-link'); ?>" type="submit" />
        <input name="publish" lang="publish" class="button add-new-h2" onclick="_ed_redirect()" value="<?php _e('Cancel', 'email-download-link'); ?>" type="button" />
        <input name="Help" lang="publish" class="button add-new-h2" onclick="_ed_help()" value="<?php _e('Help', 'email-download-link'); ?>" type="button" />
      </p>
	  <?php wp_nonce_field('ed_form_add'); ?>
    </form>
</div>
<p class="description"><?php echo ED_OFFICIAL; ?></p>
</div>