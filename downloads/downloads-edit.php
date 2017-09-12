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
		'ed_form_downloadcount' 	=> $data['ed_form_downloadcount'],
		'ed_form_expirationtype' 	=> $data['ed_form_expirationtype'],
		'ed_form_expirationdate' 	=> $data['ed_form_expirationdate'],
		'ed_form_status' 			=> $data['ed_form_status'],
		'ed_form_downloadid' 		=> $data['ed_form_downloadid']
	);
}
// Form submitted, check the data
if (isset($_POST['ed_form_submit']) && $_POST['ed_form_submit'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('ed_form_edit');
	
	$form['ed_form_title'] 			= isset($_POST['ed_form_title']) ? sanitize_text_field($_POST['ed_form_title']) : '';
	$form['ed_form_description'] 	= '';
	$form['ed_form_downloadurl'] 	= isset($_POST['ed_form_downloadurl']) ? sanitize_text_field($_POST['ed_form_downloadurl']) : '';
	$form['ed_form_expirationtype'] = isset($_POST['ed_form_expirationtype']) ? sanitize_text_field($_POST['ed_form_expirationtype']) : '';
	$form['ed_form_expirationdate'] = isset($_POST['ed_form_expirationdate']) ? sanitize_text_field($_POST['ed_form_expirationdate']) : '';
	$form['ed_form_status'] 		= 'Published';
	
	$form['ed_form_downloadurl']	= esc_url_raw($form['ed_form_downloadurl']);
	
	if ($form['ed_form_title'] == '')
	{
		$ed_errors[] = __('Please enter download title.', 'email-download-link');
		$ed_error_found = true;
	}

	if ($form['ed_form_downloadurl'] == '')
	{
		$ed_errors[] = __('Please enter valid download URL.', 'email-download-link');
		$ed_error_found = true;
	}
	
	if ( ($form['ed_form_expirationtype'] <> 'Never') && ($form['ed_form_expirationtype'] <> 'Cron') )
	{
		$ed_errors[] = __('Please select valid expiration type.', 'email-download-link');
		$ed_error_found = true;
	}

	if (!preg_match("/\d{4}\-\d{2}-\d{2}/", $form['ed_form_expirationdate']))
	{
		$ed_errors[] = __('Please enter valid expiration date.', 'email-download-link');
		$ed_error_found = true;
	} 

	//	No errors found, we can add this Group to the table
	if ($ed_error_found == false)
	{	
		$action = false;
		$action = ed_cls_downloads::ed_download_link_ins($form, $action = "update");
		if($action)
		{
			$ed_success = __('Download link was successfully updated.', 'email-download-link');
		}
		else
		{
			$ed_errors[] = __('Failed to create record. Please contact plugin admin.', 'email-download-link');
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
		<p><strong><?php echo $ed_success; ?> <a href="<?php echo ED_ADMINURL; ?>?page=ed-downloads"><?php _e('Click here', 'email-download-link'); ?></a>
		<?php _e(' to view the details', 'email-download-link'); ?></strong></p>
	</div>
	<?php
}
?>
<script language="javaScript" src="<?php echo ED_URL; ?>downloads/downloads.js"></script>
<script type="text/javascript">
jQuery(document).ready(function($){
    $('#upload-btn').click(function(e) {
        e.preventDefault();
        var image = wp.media({ 
            title: 'Upload Image',
            // mutiple: true if you want to upload multiple files at once
            multiple: false
        }).open()
        .on('select', function(e){
            // This will return the selected image from the Media Uploader, the result is an object
            var uploaded_image = image.state().get('selection').first();
            // We convert uploaded_image to a JSON object to make accessing it easier
            // Output to the console uploaded_image
            console.log(uploaded_image);
            var Ihrss_path = uploaded_image.toJSON().url;
			var Ihrss_title = uploaded_image.toJSON().title;
            // Let's assign the url value to the input field
            $('#ed_form_downloadurl').val(Ihrss_path);
			$('#ed_form_title').val(Ihrss_title);
        });
    });
});
</script>
<?php
wp_enqueue_script('jquery');  // jQuery
wp_enqueue_media(); // This will enqueue the Media Uploader script
?>
<div class="form-wrap">
	<h3><?php _e('Edit download link', 'email-download-link'); ?></h3>
	<form name="ed_form" method="post" action="#" onsubmit="return _ed_downloads_submit()"  >
		<label for="tag"><?php _e('Download link', 'email-download-link'); ?></label>
		<input type="text" name="ed_form_downloadurl" id="ed_form_downloadurl" value="<?php echo $form['ed_form_downloadurl']; ?>"  size="50">
		<input type="button" name="upload-btn" id="upload-btn" class="button-secondary" value="Upload File">
		<p><?php _e('Please upload your file to generate download link or enter your download link.', 'email-download-link'); ?></p>
		
		<label for="tag"><?php _e('Title', 'email-download-link'); ?></label>
		<input name="ed_form_title" type="text" id="ed_form_title" value="<?php echo $form['ed_form_title']; ?>" maxlength="225" size="50"  />
		<p><?php _e('Please enter title for your download link.', 'email-download-link'); ?></p>
		
		<label for="tag"><?php _e('Expiration date', 'email-download-link'); ?></label>
		<input name="ed_form_expirationdate" type="text" id="ed_form_expirationdate" value="<?php echo $form['ed_form_expirationdate']; ?>" maxlength="10"  />
		<p><?php _e('Please enter expiration date for this download URL. YYYY-MM-DD.', 'email-download-link'); ?></p>
		
		<label for="tag"><?php _e('URL refresh', 'email-download-link'); ?></label>
		<select name="ed_form_expirationtype" id="ed_form_expirationtype">
			<option value='Never'  <?php if($form['ed_form_expirationtype']=='Never') { echo 'selected' ; } ?>>Never</option>
			<option value='Cron'  <?php if($form['ed_form_expirationtype']=='Cron') { echo 'selected' ; } ?>>Cron</option>
		</select>
		<p><?php _e('Please select your refresh type for the download link.', 'email-download-link'); ?></p>
	  
	  	<input type="hidden" name="ed_form_submit" id="ed_form_submit" value="yes"/>
	  	<input type="hidden" name="ed_form_id" id="ed_form_id" value="<?php echo $form['ed_form_id']; ?>"/>
		<input type="hidden" name="ed_form_guid" id="ed_form_guid" value="<?php echo $form['ed_form_guid']; ?>"/>
		<p class="submit">
		<input name="publish" lang="publish" class="button add-new-h2" value="<?php _e('Submit', 'email-download-link'); ?>" type="submit" />
		<input name="publish" lang="publish" class="button add-new-h2" onclick="_ed_redirect()" value="<?php _e('Cancel', 'email-download-link'); ?>" type="button" />
		<input name="Help" lang="publish" class="button add-new-h2" onclick="_ed_help()" value="<?php _e('Help', 'email-download-link'); ?>" type="button" />
		</p>
		<?php wp_nonce_field('ed_form_edit'); ?>
	</form>
</div>
<p class="description"><?php echo ED_OFFICIAL; ?></p>
</div>