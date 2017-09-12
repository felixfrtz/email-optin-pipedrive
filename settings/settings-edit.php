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
		'ed_c_id' => '',
		'ed_c_fromname' => '',
		'ed_c_fromemail' => '',
		'ed_c_mailtype' => '',
		'ed_c_adminmailoption' => '',
		'ed_c_adminemail' => '',
		'ed_c_adminmailsubject' => '',
		'ed_c_adminmailcontant' => '',
		'ed_c_usermailoption' => '',
		'ed_c_usermailsubject' => '',
		'ed_c_usermailcontant' => '',
		'ed_c_downloadstart' => '',
		'ed_c_downloadpgtxt' => '',
		'ed_c_pipedrivekey' => '',
		'ed_c_pipedrivedomain' => ''
		//'ed_c_cronurl' => '',
		//'ed_c_cronmailcontent' => ''
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
		'ed_c_adminemail' 		=> $data['ed_c_adminemail'],
		'ed_c_adminmailsubject' => $data['ed_c_adminmailsubject'],
		'ed_c_adminmailcontant' => $data['ed_c_adminmailcontant'],
		'ed_c_usermailoption' 	=> $data['ed_c_usermailoption'],
		'ed_c_usermailsubject' 	=> $data['ed_c_usermailsubject'],
		'ed_c_usermailcontant' 	=> $data['ed_c_usermailcontant'],
		'ed_c_downloadstart' 	=> $data['ed_c_downloadstart'],
		'ed_c_downloadpgtxt' 	=> $data['ed_c_downloadpgtxt'],
		'ed_c_pipedrivekey' 	  => $data['ed_c_pipedrivekey'],
		'ed_c_pipedrivedomain'	  => $data['ed_c_pipedrivedomain'],
		//'ed_c_cronurl' => $data['ed_c_cronurl']
		//'ed_c_cronmailcontent' => $data['ed_c_cronmailcontent']
		'ed_c_expiredlinkcontant' => $data['ed_c_expiredlinkcontant'],
		'ed_c_invalidlinkcontant' => $data['ed_c_invalidlinkcontant'],
		
	);
}

	
// Form submitted, check the data
if (isset($_POST['ed_form_submit']) && $_POST['ed_form_submit'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('ed_form_edit');
	
	$form['ed_c_fromname'] 				= isset($_POST['ed_c_fromname']) ? sanitize_text_field($_POST['ed_c_fromname']) : '';
	$form['ed_c_fromemail']				= isset($_POST['ed_c_fromemail']) ? sanitize_text_field($_POST['ed_c_fromemail']) : '';
	$form['ed_c_mailtype'] 				= isset($_POST['ed_c_mailtype']) ? sanitize_text_field($_POST['ed_c_mailtype']) : '';
	$form['ed_c_adminmailoption'] 		= isset($_POST['ed_c_adminmailoption']) ? sanitize_text_field($_POST['ed_c_adminmailoption']) : '';
	$form['ed_c_adminemail'] 			= isset($_POST['ed_c_adminemail']) ? sanitize_text_field($_POST['ed_c_adminemail']) : '';
	$form['ed_c_adminmailsubject'] 		= isset($_POST['ed_c_adminmailsubject']) ? sanitize_text_field($_POST['ed_c_adminmailsubject']) : '';
	$form['ed_c_adminmailcontant'] 		= isset($_POST['ed_c_adminmailcontant']) ? wp_filter_post_kses($_POST['ed_c_adminmailcontant']) : '';
	$form['ed_c_usermailoption'] 		= 'YES';
	$form['ed_c_usermailsubject'] 		= isset($_POST['ed_c_usermailsubject']) ? sanitize_text_field($_POST['ed_c_usermailsubject']) : '';
	$form['ed_c_usermailcontant'] 		= isset($_POST['ed_c_usermailcontant']) ? wp_filter_post_kses($_POST['ed_c_usermailcontant']) : '';
	$form['ed_c_downloadstart'] 		= isset($_POST['ed_c_downloadstart']) ? wp_filter_post_kses($_POST['ed_c_downloadstart']) : '';
	$form['ed_c_downloadpgtxt'] 		= isset($_POST['ed_c_downloadpgtxt']) ? wp_filter_post_kses($_POST['ed_c_downloadpgtxt']) : '';
	//$form['ed_c_cronurl'] 			= isset($_POST['ed_c_cronurl']) ? $_POST['ed_c_cronurl'] : '';
	//$form['ed_c_cronmailcontent'] 	= isset($_POST['ed_c_cronmailcontent']) ? $_POST['ed_c_cronmailcontent'] : '';
	$form['ed_c_expiredlinkcontant'] 	= isset($_POST['ed_c_expiredlinkcontant']) ? wp_filter_post_kses($_POST['ed_c_expiredlinkcontant']) : '';
	$form['ed_c_invalidlinkcontant'] 	= isset($_POST['ed_c_invalidlinkcontant']) ? wp_filter_post_kses($_POST['ed_c_invalidlinkcontant']) : '';

	$form['ed_c_fromemail'] 				= sanitize_email($form['ed_c_fromemail']); 
	$form['ed_c_pipedrivekey'] 				= isset($_POST['ed_c_pipedrivekey']) ? sanitize_text_field($_POST['ed_c_pipedrivekey']) : '';
	$form['ed_c_pipedrivedomain'] 			= isset($_POST['ed_c_pipedrivedomain']) ? sanitize_text_field($_POST['ed_c_pipedrivedomain']) : '';

	if ($form['ed_c_fromname'] == '')
	{
		$ed_errors[] = __('Please enter sender of notifications from name.', 'email-download-link');
		$ed_error_found = true;
	}
	
	if ($form['ed_c_fromemail'] == '')
	{
		$ed_errors[] = __('Please enter sender of notifications from email.', 'email-download-link');
		$ed_error_found = true;
	}
	
	//	No errors found, we can add this Group to the table
	if ($ed_error_found == false)
	{	
		$action = "";
		$action = ed_cls_settings::ed_setting_update1($form);
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
	?>
		<div class="error fade">
			<p><strong><?php echo $ed_errors[0]; ?></strong></p>
		</div>
	<?php
}
if ($ed_error_found == false && strlen($ed_success) > 0)
{
	?>
	<div class="updated fade">
		<p>
			<strong>
				<?php echo $ed_success; ?> 
				<a href="<?php echo ED_ADMINURL; ?>?page=ed-settings"><?php _e('Click here', 'email-download-link'); ?></a>
				<?php _e(' to view the details', 'email-download-link'); ?>
			</strong>
		</p>
	</div>
	<?php
}
?>
<script language="javaScript" src="<?php echo ED_URL; ?>settings/settings.js"></script>
<style>
.form-table th {
    width: 350px;
}
</style>
<div class="form-wrap">
	<div id="icon-plugins" class="icon32"></div>
	<h2><?php _e(ED_PLUGIN_DISPLAY, 'email-download-link'); ?></h2>
	<h3><?php _e('Settings', 'email-download-link'); ?></h3>
	<form name="ed_form" method="post" action="#" onsubmit="return _ed_submit()"  >
	<table class="form-table">
	<tbody>
		<tr>
			<th scope="row">
				<label for="elp"><?php _e('Sender of notifications', 'email-download-link'); ?>
				<p class="description"><?php _e('Choose a FROM name and FROM email address for all emails from this plugin.', 'email-download-link'); ?></p></label>
			</th>
			<td>
				<input name="ed_c_fromname" type="text" id="ed_c_fromname" value="<?php echo esc_html(stripslashes($form['ed_c_fromname'])); ?>" maxlength="225" />
				<input name="ed_c_fromemail" type="text" id="ed_c_fromemail" value="<?php echo esc_html(stripslashes($form['ed_c_fromemail'])); ?>" size="35" maxlength="225" />
			</td>
		</tr>
		<tr>
			<th scope="row"> 
				<label for="elp"><?php _e('Mail type', 'email-download-link'); ?>
				<p class="description"><?php _e('Option 1 & 2 is to send mails with default Wordpress method wp_mail(). Option 3 & 4 is to send mails with PHP method mail()', 'email-download-link'); ?></p></label>
			</th>
			<td>
				<select name="ed_c_mailtype" id="ed_c_mailtype">
					<option value='WP HTML MAIL' <?php if($form['ed_c_mailtype'] == 'WP HTML MAIL') { echo 'selected' ; } ?>>1. WP HTML MAIL</option>
					<option value='WP PLAINTEXT MAIL' <?php if($form['ed_c_mailtype'] == 'WP PLAINTEXT MAIL') { echo 'selected' ; } ?>>2. WP PLAINTEXT MAIL</option>
					<option value='PHP HTML MAIL' <?php if($form['ed_c_mailtype'] == 'PHP HTML MAIL') { echo 'selected' ; } ?>>3. PHP HTML MAIL</option>
					<option value='PHP PLAINTEXT MAIL' <?php if($form['ed_c_mailtype'] == 'PHP PLAINTEXT MAIL') { echo 'selected' ; } ?>>4. PHP PLAINTEXT MAIL</option>
				</select>
			</td>
		</tr>
		<!-------------------------------------------------------------------------------->
		<tr>
			<th scope="row"> 
				<label for="elp"><?php _e('Download link mail subject', 'email-download-link'); ?>
				<p class="description"><?php _e('Enter the mail subject for download link mail. This will send whenever user is requested download link.', 'email-download-link'); ?></p></label>
			</th>
			<td><input name="ed_c_usermailsubject" type="text" id="ed_c_usermailsubject" value="<?php echo esc_html(stripslashes($form['ed_c_usermailsubject'])); ?>" size="60" maxlength="225" /></td>
		</tr>
		<tr>
			<th scope="row"> 
				<label for="elp"><?php _e('Download link mail content', 'email-download-link'); ?>
				<p class="description"><?php _e('Enter the mail content for download link mail. This will send whenever user is requested download link.', 'email-download-link'); ?> 
				(Keyword: ###NAME###, ###EMAIL###, ###DOWNLOADLINK###, ###DOWNLOADLINKDIRECT###)</p>
			</label>
			</th>
			<td><textarea size="100" id="ed_c_usermailcontant" rows="10" cols="58" name="ed_c_usermailcontant"><?php echo esc_html(stripslashes($form['ed_c_usermailcontant'])); ?></textarea></td>
		</tr>
		<!-------------------------------------------------------------------------------->
		<tr>
			<th scope="row"> 
				<label for="elp"><?php _e('Mail to admin', 'email-download-link'); ?>
				<p class="description"><?php _e('To send admin notifications for new download email, This option must be set to YES.', 'email-download-link'); ?></p></label>
			</th>
			<td>			
			<select name="ed_c_adminmailoption" id="ed_c_adminmailoption">
				<option value='YES' <?php if($form['ed_c_adminmailoption'] == 'YES') { echo 'selected' ; } ?>>YES</option>
				<option value='NO' <?php if($form['ed_c_adminmailoption'] == 'NO') { echo 'selected' ; } ?>>NO</option>
			</select>
			</td>
		</tr>
		<tr>
			<th scope="row"> 
				<label for="elp"><?php _e('Admin email addresses', 'email-download-link'); ?>
				<p class="description"><?php _e('Enter the admin email addresses that should receive notifications (separate by comma).', 'email-download-link'); ?></p></label>
			</th>
			<td><input name="ed_c_adminemail" type="text" id="ed_c_adminemail" value="<?php echo esc_html(stripslashes($form['ed_c_adminemail'])); ?>" size="60" maxlength="225" /></td>
		</tr>
		<tr>
			<th scope="row"> 
				<label for="elp"><?php _e('Admin mail subject', 'email-download-link'); ?>
				<p class="description"><?php _e('Enter the subject for admin mail. This will send whenever new download email sent to requester.', 'email-download-link'); ?></p></label>
			</th>
			<td><input name="ed_c_adminmailsubject" type="text" id="ed_c_adminmailsubject" value="<?php echo esc_html(stripslashes($form['ed_c_adminmailsubject'])); ?>" size="60" maxlength="225" /></td>
		</tr>
		<tr>
			<th scope="row"> 
				<label for="elp"><?php _e('Admin mail content', 'email-download-link'); ?>
				<p class="description"><?php _e('Enter the mail content for admin. This will send whenever new download email sent to requester.', 'email-download-link'); ?> 
				(Keyword: ###NAME###, ###EMAIL###, ###DOWNLOADLINK###, ###DOWNLOADLINKDIRECT###)</p></label>
			</th>
			<td><textarea size="100" id="ed_c_adminmailcontant" rows="10" cols="58" name="ed_c_adminmailcontant"><?php echo esc_html(stripslashes($form['ed_c_adminmailcontant'])); ?></textarea></td>
		</tr>
		<!-------------------------------------------------------------------------------->
		<tr>
			<th scope="row"> 
				<label for="elp"><?php _e('Expired link content', 'email-download-link'); ?>
				<p class="description"><?php _e('Enter the content to display if download link has expired.', 'email-download-link'); ?></p></label>
			</th>
			<td><textarea size="100" id="ed_c_expiredlinkcontant" rows="5" cols="58" name="ed_c_expiredlinkcontant"><?php echo esc_html(stripslashes($form['ed_c_expiredlinkcontant'])); ?></textarea></td>
		</tr>
		<tr>
			<th scope="row"> 
				<label for="elp"><?php _e('Invalid link content', 'email-download-link'); ?>
				<p class="description"><?php _e('Enter the content to display if download link is invalid.', 'email-download-link'); ?></p></label>
			</th>
			<td><textarea size="100" id="ed_c_invalidlinkcontant" rows="5" cols="58" name="ed_c_invalidlinkcontant"><?php echo esc_html(stripslashes($form['ed_c_invalidlinkcontant'])); ?></textarea></td>
		</tr>
		<!-------------------------------------------------------------------------------->

		<tr>
			<th scope="row"> 
				<label for="elp"><?php _e('Pipedrive API Key', 'email-download-link'); ?>
				<p class="description"><?php _e('Enter your Pipedrive API Key.', 'email-download-link'); ?></p></label>
			</th>
			<td><input name="ed_c_pipedrivekey" type="text" id="ed_c_pipedrivekey" value="<?php echo esc_html(stripslashes($form['ed_c_pipedrivekey'])); ?>" size="60" maxlength="225" /></td>
		</tr>
		<tr>

		<tr>
			<th scope="row"> 
				<label for="elp"><?php _e('Pipedrive Subdomain', 'email-download-link'); ?>
				<p class="description"><?php _e('Enter your Pipedrive Subdomain (e.g. mycompany as in mycompany.pipedrive.com.', 'email-download-link'); ?></p></label>
			</th>
			<td><input name="ed_c_pipedrivedomain" type="text" id="ed_c_pipedrivedomain" value="<?php echo esc_html(stripslashes($form['ed_c_pipedrivedomain'])); ?>" size="60" maxlength="225" /></td>
		</tr>
		<tr>
		<!-------------------------------------------------------------------------------->
	</tbody>
	</table>
	<div style="padding-top:10px;"></div>
	<input type="hidden" name="ed_form_submit" value="yes"/>
	<input type="hidden" name="ed_c_id" id="ed_c_id" value="<?php echo $form['ed_c_id']; ?>"/>
	<p class="submit">
		<input name="publish" lang="publish" class="button add-new-h2" value="<?php _e('Save Settings', 'email-download-link'); ?>" type="submit" />
		<input name="publish" lang="publish" class="button add-new-h2" onclick="_ed_redirect()" value="<?php _e('Cancel', 'email-download-link'); ?>" type="button" />
		<input name="Help" lang="publish" class="button add-new-h2" onclick="_ed_help()" value="<?php _e('Help', 'email-download-link'); ?>" type="button" />
	</p>
	<?php wp_nonce_field('ed_form_edit'); ?>
    </form>
</div>
<p class="description"><?php echo ED_OFFICIAL; ?></p>
</div>