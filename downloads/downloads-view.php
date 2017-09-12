<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<div class="wrap">
<?php
$guid = isset($_GET['guid']) ? sanitize_text_field($_GET['guid']) : '';
ed_cls_security::ed_check_guid($guid);

$result = ed_cls_downloads::ed_download_link_count(0, $guid);
if ($result != '1')
{
	?><div class="error fade"><p><strong><?php _e('Oops, selected details doesnt exist.', 'email-download-link'); ?></strong></p></div><?php
}
else
{
	$ed_errors = array();
	$ed_success = '';
	$ed_error_found = false;

	$data = array();
	$data = ed_cls_downloads::ed_download_link_view(0, $guid);
	
	// Preset the form fields
	$form = array(
		'ed_form_id' 				=> $data['ed_form_id'],
		'ed_form_guid' 				=> $data['ed_form_guid'],
		'ed_form_title' 			=> $data['ed_form_title'],
		'ed_form_description' 		=> $data['ed_form_description'],
		'ed_form_downloadurl' 		=> $data['ed_form_downloadurl'],
		'ed_form_downloadabspath' 	=> $data['ed_form_downloadabspath'],
		'ed_form_downloadcount' 	=> $data['ed_form_downloadcount'],
		'ed_form_expirationtype' 	=> $data['ed_form_expirationtype'],
		'ed_form_expirationdate' 	=> $data['ed_form_expirationdate'],
		'ed_form_status' 			=> $data['ed_form_status'],
		'ed_form_downloadid' 		=> $data['ed_form_downloadid']
	);
}
?>
<script language="javaScript" src="<?php echo ED_URL; ?>downloads/downloads.js"></script>
<div class="form-wrap">
	<h3><?php _e('View download link', 'email-download-link'); ?></h3>
	<form name="ed_form" method="post" action="#" onsubmit=""  >
		
		<label for="tag"><strong><?php _e('Title', 'email-download-link'); ?> : </strong></label>
		<p>&nbsp;<?php echo $form['ed_form_title']; ?></p>
		<p></p>

		
		<label for="tag"><strong><?php _e('Description', 'email-download-link'); ?> : </strong></label>
		<p>&nbsp;NA</p>
		<p></p>
		
		<label for="tag"><strong><?php _e('Download link', 'email-download-link'); ?> : </strong></label>
		<p>&nbsp;<?php echo $form['ed_form_downloadurl']; ?></p>
		<p></p>
		
		<label for="tag"><strong><?php _e('Download absolute path', 'email-download-link'); ?> : </strong></label>
		<p>&nbsp;<?php echo $form['ed_form_downloadabspath']; ?></p>
		<p></p>
		
		<label for="tag"><strong><?php _e('Total Downloads', 'email-download-link'); ?> : </strong></label>
		<p>&nbsp;<?php echo $form['ed_form_downloadcount']; ?></p>
		<p></p>
		
		<label for="tag"><strong><?php _e('Expiration Type', 'email-download-link'); ?> : </strong></label>
		<p>&nbsp;<?php echo $form['ed_form_expirationtype']; ?></p>
		<p></p>
		
		<label for="tag"><strong><?php _e('Expiration Date', 'email-download-link'); ?> : </strong></label>
		<p>&nbsp;<?php echo $form['ed_form_expirationdate']; ?></p>
		<p></p>
		
		<label for="tag"><strong><?php _e('Status', 'email-download-link'); ?> : </strong></label>
		<p>&nbsp;<?php echo $form['ed_form_status']; ?></p>
		<p></p>
		
		<label for="tag"><strong><?php _e('Download Id', 'email-download-link'); ?> : </strong></label>
		<p>&nbsp;<?php echo $form['ed_form_downloadid']; ?></p>
		<p></p>
		
		<label for="tag"><strong><?php _e('Short Code', 'email-download-link'); ?> : </strong></label>
		<p>&nbsp;[email-download-link namefield="YES" id="<?php echo $form['ed_form_id']; ?>"]</p>
		<p></p>
		
		<label for="tag"><strong><?php _e('Guid Code', 'email-download-link'); ?> : </strong></label>
		<p>&nbsp;<?php echo $form['ed_form_guid']; ?></p>
		<p></p>
		
		<br />
		<p class="submit">
		<input name="Back" lang="publish" class="button add-new-h2" onclick="_ed_redirect()" value="<?php _e('Back', 'email-download-link'); ?>" type="button" />
		<input name="Help" lang="publish" class="button add-new-h2" onclick="_ed_help()" value="<?php _e('Help', 'email-download-link'); ?>" type="button" />
		</p>
	</form>
</div>
<p class="description"><?php echo ED_OFFICIAL; ?></p>
</div>