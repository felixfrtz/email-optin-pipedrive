<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php if ( ! empty( $_POST ) && ! wp_verify_nonce( $_REQUEST['wp_create_nonce'], 'downloads-nonce' ) )  { die('<p>Security check failed.</p>'); } ?>
<?php
if (isset($_POST['frm_ed_display']) && $_POST['frm_ed_display'] == 'yes')
{
	$guid = isset($_GET['guid']) ? sanitize_text_field($_GET['guid']) : '0';
	ed_cls_security::ed_check_guid($guid);
	
	$result = ed_cls_downloads::ed_download_link_count(0, $guid);
	if ($result != '1')
	{
		?><div class="error fade"><p><strong><?php _e('Oops, selected details doesnt exist', 'email-download-link'); ?></strong></p></div><?php
	}
	else
	{
		if (isset($_GET['ac']) && $_GET['ac'] == 'del' && isset($_GET['guid']) && sanitize_text_field($_GET['guid']) != '')
		{
			check_admin_referer('ed_form_show');
			ed_cls_downloads::ed_download_link_delete_guid($guid);
			$ed_success_msg = true;
			$ed_success = __('Selected record was successfully deleted.', 'email-download-link');
		}
	}
	
	if ($ed_success_msg == true)
	{
		?><div class="updated fade"><p><strong><?php echo $ed_success; ?></strong></p></div><?php
	}
	
}
?>
<script language="javaScript" src="<?php echo ED_URL; ?>downloads/downloads.js"></script>
<div class="wrap">
  <div id="icon-plugins" class="icon32"></div>
  <h2><?php _e(ED_PLUGIN_DISPLAY, 'email-download-link'); ?></h2>
  <div class="tool-box">
  <form name="frm_ed_display" method="post">
  <h3>
  <?php _e('Download links', 'email-download-link'); ?> 
	<a class="page-title-action" href="<?php echo ED_ADMINURL; ?>?page=ed-downloads&amp;ac=add"><?php _e('Add New', 'email-download-link'); ?></a>
  </h3>
	<?php
	$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
	$limit = 100;
	$offset = ($pagenum - 1) * $limit;
	$total = ed_cls_downloads::ed_download_link_count(0, "");
	$total = ceil( $total / $limit );
	$myData = array();
	$myData = ed_cls_downloads::ed_download_link_view_page($offset, $limit, 0);
	?>
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
		  	<!--<th class="check-column" scope="col" style="padding: 8px 2px;"><input type="checkbox" /></th>-->
			<th scope="col"><?php _e('Title', 'email-download-link'); ?></th>
			<th scope="col"><?php _e('Download URL', 'email-download-link'); ?></th>
			<th scope="col"><?php _e('Short Code', 'email-download-link'); ?></th>
			<th scope="col"><?php _e('Downloads', 'email-download-link'); ?></th>
			<th scope="col"><?php _e('Expiration Type', 'email-download-link'); ?></th>
			<th scope="col"><?php _e('Expiration Date', 'email-download-link'); ?></th>
          </tr>
        </thead>
        <tfoot>
          <tr>
		  	<!--<th class="check-column" scope="col" style="padding: 8px 2px;"><input type="checkbox" /></th>-->
			<th scope="col"><?php _e('Title', 'email-download-link'); ?></th>
			<th scope="col"><?php _e('Download URL', 'email-download-link'); ?></th>
			<th scope="col"><?php _e('Short Code', 'email-download-link'); ?></th>
			<th scope="col"><?php _e('Downloads', 'email-download-link'); ?></th>
			<th scope="col"><?php _e('Expiration Type', 'email-download-link'); ?></th>
			<th scope="col"><?php _e('Expiration Date', 'email-download-link'); ?></th>
          </tr>
        </tfoot>
        <tbody>
		<?php 
		$i = 0;
		$displayisthere = FALSE;
		if(count($myData) > 0)
		{
			foreach ($myData as $data)
			{					
				?>
				<tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
				<!--<td align="left"><input type="checkbox" value="<?php //echo $data['ed_form_id']; ?>"></td>-->
				<td><?php echo $data['ed_form_title']; ?>
				<div class="row-actions">
					<span class="edit">
					<a title="View Details" href="<?php echo ED_ADMINURL; ?>?page=ed-downloads&amp;ac=view&amp;guid=<?php echo $data['ed_form_guid']; ?>"><?php _e('View Details', 'email-download-link'); ?></a> 
					</span>
					<span class="edit">
					| <a title="Edit" href="<?php echo ED_ADMINURL; ?>?page=ed-downloads&amp;ac=edit&amp;guid=<?php echo $data['ed_form_guid']; ?>"><?php _e('Edit', 'email-download-link'); ?></a> 
					</span>
					<span class="trash">
					| <a onClick="javascript:_ed_delete('<?php echo $data['ed_form_guid']; ?>')" href="javascript:void(0);"><?php _e('Delete', 'email-download-link'); ?></a>
					</span>
					<span class="view">
					| <a title="Downloads" href="<?php echo ED_ADMINURL; ?>?page=ed-downloadhistory&amp;ac=downloads&amp;id=<?php echo $data['ed_form_downloadid']; ?>"><?php _e('Downloads', 'email-download-link'); ?></a> 
					</span>
				</div>
				</td>    
				<td align="left">
				<a target="_blank" href="<?php echo stripslashes($data['ed_form_downloadurl']); ?>"><img src="<?php echo ED_URL; ?>images/download.png" alt="Download URL" /></a>
				</td>
				<td align="left">[email-download-link namefield="YES" id="<?php echo $data['ed_form_id']; ?>"]</td>
				<td align="left"><?php echo $data['ed_form_downloadcount']; ?></td>
				<td align="left"><?php echo $data['ed_form_expirationtype']; ?></td>
				<td align="left"><?php echo $data['ed_form_expirationdate']; ?></td>
				</tr>
				<?php
				$i = $i+1;
			} 
		}
		else
		{
			?>
			<tr>
				<td colspan="6" align="center"><?php _e('No records available', 'email-download-link'); ?></td>
			</tr>
			<?php 
		}
		?>
        </tbody>
      </table>
	  <?php
	  $args = array(
			'base'               => add_query_arg( 'pagenum', '%#%' ),
			'format'             => '',
			'total'              => $total,
			'current'            => $pagenum,
			'show_all'           => false,
			'end_size'           => 1,
			'mid_size'           => 2,
			'prev_next'          => true,
			'prev_text'          => __('&laquo;'),
			'next_text'          => __('&raquo;'),
			'type'               => 'plain',
			'add_args'           => false,
			'add_fragment'       => '',
			'before_page_number' => '',
			'after_page_number'  => ''
		);
		$page_links = paginate_links($args);
	 ?>	
      <?php wp_nonce_field('ed_form_show'); ?>
	  <input type="hidden" name="wp_create_nonce" id="wp_create_nonce" value="<?php echo wp_create_nonce( 'downloads-nonce' ); ?>"/>
	  <input type="hidden" name="frm_ed_display" value="yes"/>
	</form>
	<div class="tablenav bottom">
		<div class="alignleft actions bulkactions">
			<a href="<?php echo ED_ADMINURL; ?>?page=ed-downloads&amp;ac=add"><input class="button action" type="button" value="<?php _e('Add New', 'email-download-link'); ?>" /></a>
			<a href="<?php echo ED_ADMINURL; ?>?page=ed-downloadhistory&ac=export"><input class="button action" type="button" value="<?php _e('Export Emails', 'email-download-link'); ?>" /></a>
			<a href="<?php echo ED_FAV; ?>" target="_blank"><input class="button action" type="button" value="<?php _e('Help', 'email-download-link'); ?>" /></a>
		</div>
		<div class="tablenav-pages">
			<div>
				<span class="pagination-links">
				<?php echo $page_links; ?>
				</span>
			</div>
		</div>
	</div>
  </div>
  <p class="description"><?php echo ED_OFFICIAL; ?></p>
</div>