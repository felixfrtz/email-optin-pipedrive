<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php
class ed_cls_registerhook
{
	public static function ed_activation()
	{
		global $wpdb;
		
		add_option('email-download-link', "1.0");

		// Plugin tables
		$array_tables_to_plugin = array('ed_emaillist','ed_downloadform','ed_pluginconfig');
		$errors = array();
		
		// loading the sql file, load it and separate the queries
		$sql_file = ED_DIR.'sql'.DS.'ed-createdb.sql';
		$prefix = $wpdb->prefix;
        $handle = fopen($sql_file, 'r');
        $query = fread($handle, filesize($sql_file));
        fclose($handle);
        $query=str_replace('CREATE TABLE IF NOT EXISTS ','CREATE TABLE IF NOT EXISTS '.$prefix, $query);
        $queries=explode('-- SQLQUERY ---', $query);

        // run the queries one by one
        $has_errors = false;
        foreach($queries as $qry)
		{
            $wpdb->query($qry);
        }
		
		// list the tables that haven't been created
        $missingtables=array();
        foreach($array_tables_to_plugin as $table_name)
		{
			if(strtoupper($wpdb->get_var("SHOW TABLES like  '". $prefix.$table_name . "'")) != strtoupper($prefix.$table_name))  
			{
                $missingtables[]=$prefix.$table_name;
            }
        }
		
		// add error in to array variable
        if($missingtables) 
		{
			$errors[] = __('These tables could not be created on installation ' . implode(', ',$missingtables), 'email-download-link');
            $has_errors=true;
        }
		
		// if error call wp_die()
        if($has_errors) 
		{
			wp_die( __( $errors[0] , 'email-download-link' ) );
			return false;
		}
		else
		{
			ed_cls_default::ed_pluginconfig_default();
			ed_cls_default::ed_downloads_default();
		}
        return true;
	}

	public static function ed_deactivation()
	{
		// do not generate any output here
	}
	
	public static function ed_admin_option()
	{
		// do not generate any output here
	}
	
	public static function ed_adminmenu()
	{		
		add_menu_page( __( 'Email download link', 'email-download-link' ), 
			__( 'Download Link', 'email-download-link' ), 'admin_dashboard', 'email-download-link', 'ed_admin_option', ED_URL.'images/icon.png', 51 );
			
		add_submenu_page('email-download-link', __( 'Email download link', 'email-download-link' ), 
			__( 'Downloads', 'email-download-link' ), "manage_options", 'ed-downloads', array( 'ed_cls_intermediate', 'ed_downloads' ));
			
		add_submenu_page('email-download-link', __( 'Email download link', 'email-download-link' ), 
			__( 'Cron Details', 'email-download-link' ), "manage_options", 'ed-cron', array( 'ed_cls_intermediate', 'ed_cron' ));
			
		add_submenu_page('email-download-link', __( 'Email download link', 'email-download-link' ), 
			__( 'Settings', 'email-download-link' ), "manage_options", 'ed-settings', array( 'ed_cls_intermediate', 'ed_settings' ));
			
		add_submenu_page('email-download-link', __( 'Email download link', 'email-download-link' ), 
			__( 'Download History', 'email-download-link' ), "manage_options", 'ed-downloadhistory', array( 'ed_cls_intermediate', 'ed_downloadhistory' ));
	}
	
	public static function ed_widget_loading()
	{
		register_widget( 'ed_widget_register' );
	}	
}

class ed_form_submuit
{
	public static function ed_formdisplay($form_setting = array())
	{
		$ed = "";
		$ed_alt_nm = '';
		$ed_alt_em = '';
		$ed_alt_company = '';
		$ed_alt_success = '';
		$ed_alt_techerror = '';
		$ed_error = false;
		
		if(count($form_setting) == 0)
		{
			return $es;
		}
		else
		{
			$ed_title 		= $form_setting['ed_title'];
			$ed_desc		= $form_setting['ed_desc'];
			$ed_name		= $form_setting['ed_name'];
			$ed_name_mand	= $form_setting['ed_name_mand'];
			$ed_form_id		= $form_setting['ed_form_id'];
			
			$ed_form_downloaguid_array = array();
			if($ed_form_id == 0 || $ed_form_id == "" || $ed_form_id == "0")
			{
				$ed_form_downloaguid_array = ed_cls_downloads::ed_download_link_random(1);
				if(count($ed_form_downloaguid_array) > 0)
				{
					$ed_email_form_guid	 = $ed_form_downloaguid_array[0]['ed_form_guid'];
				}
			}
			else
			{
				$ed_form_downloaguid_array = ed_cls_downloads::ed_download_link_view($ed_form_id, "");
				if(count($ed_form_downloaguid_array) > 0)
				{
					$ed_email_form_guid	 = $ed_form_downloaguid_array['ed_form_guid'];
				}
			}
		}
		
		if ( isset( $_POST['ed_btn'] ) ) 
		{
			check_admin_referer('ed_form_subscribers');
			
			if($ed_name == "YES")
			{
				$ed_txt_nm = isset($_POST['ed_txt_nm']) ? sanitize_text_field($_POST['ed_txt_nm']) : '';
			}
			else
			{
				$ed_txt_nm = "";
			}
			
			$ed_txt_em = isset($_POST['ed_txt_em']) ? sanitize_text_field($_POST['ed_txt_em']) : '';
			$ed_txt_company = isset($_POST['ed_txt_company']) ? sanitize_text_field($_POST['ed_txt_company']) : '';
			$ed_id = isset($_POST['ed_txt_id']) ? sanitize_text_field($_POST['ed_txt_id']) : '';
			
			if($ed_name == "YES" && $ed_name_mand == "YES" && $ed_txt_nm == "")
			{
				$ed_alt_nm = '<span class="ed_validation" style="color: #f00;">'.ED_MSG_01.'</span>';
				$ed_alt_company = '<span class="ed_validation" style="color: #f00;">'.ED_MSG_01.'</span>';
				$ed_error = true;
			}
			
			if( $ed_txt_nm  <> "")
			{
				$ed_txt_nm = sanitize_text_field($ed_txt_nm);
			}

			if( $ed_txt_company  <> "")
			{
				$ed_txt_company = sanitize_text_field($ed_txt_company);
			}

			if($ed_txt_em == "")
			{
				$ed_alt_em = '<span class="ed_validation" style="color: #f00;">'.ED_MSG_01.'</span>';
				$ed_error = true;
			}
			
			if(!is_email($ed_txt_em) && $ed_txt_em <> "")
			{
				$ed_alt_em = '<span class="es_af_validation" style="color: #000000;">'.ED_MSG_02.'</span>';
				$ed_error = true;
			}
			
			if(!$ed_error)
			{
				$homeurl = home_url();
				$samedomain = strpos($_SERVER['HTTP_REFERER'], $homeurl);
				if (($samedomain !== false) && $samedomain < 5) 
				{					
					$sts = ed_cls_subscribers::ed_subscriber_create($ed_txt_nm, $ed_txt_company, $ed_txt_em, $ed_email_form_guid);
					if($sts == "suss")
					{
						$ed_email_id = ed_cls_subscribers::ed_subscriber_foremail($ed_txt_em, $ed_email_form_guid);
						
						if($ed_email_id > 0)
						{
							$ed_email_id = ed_cls_sendemail::ed_sendemail_prepare($ed_email_id);
						}
						$ed_alt_success = '<span class="ed_sent_successfully" style="color: #00CC00;">'.ED_MSG_04.'</span>';
					}
				}
			}
		}
		
		$ed = $ed . '<form method="post" action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '">';
		
		if($ed_desc	<> "")
		{
			$ed = $ed . '<p>';
				$ed = $ed . '<span class="ed_short_desc">';
					$ed = $ed . $ed_desc;
				$ed = $ed . '</span>';
			$ed = $ed . '</p>';
		
		}
		
		if($ed_name == "YES")
		{
			$ed = $ed . '<p>';
				$ed = $ed . __('Name', 'email-download-link');
				if($ed_name_mand == "YES")
				{
					$ed = $ed . ' *';
				}
				$ed = $ed . '<br>';
				$ed = $ed . '<span class="ed_css_txt">';
					$ed = $ed . '<input class="ed_tb_css" name="ed_txt_nm" id="ed_txt_nm" value="" maxlength="225" type="text">';
				$ed = $ed . '</span>';
				$ed = $ed . $ed_alt_nm;
			$ed = $ed . '</p>';
		}
		
		$ed = $ed . '<p>';
			$ed = $ed . __('E-Mail *', 'email-download-link');
			$ed = $ed . '<br>';
			$ed = $ed . '<span class="ed_css_txt">';
				$ed = $ed . '<input class="ed_tb_css" name="ed_txt_em" id="ed_txt_em" value="" maxlength="225" type="text">';
			$ed = $ed . '</span>';
			$ed = $ed . $ed_alt_em;
		$ed = $ed . '</p>';

		$ed = $ed . '<p>';
			$ed = $ed . __('Firma *', 'email-download-link');
			$ed = $ed . '<br>';
			$ed = $ed . '<span class="ed_css_txt">';
				$ed = $ed . '<input class="ed_tb_css" name="ed_txt_company" id="ed_txt_company" value="" maxlength="225" type="text">';
			$ed = $ed . '</span>';
			$ed = $ed . $ed_alt_company;
		$ed = $ed . '</p>';

		
		$ed = $ed . '<p>';
			$ed = $ed . '<input class="ed_bt_css" name="ed_btn" id="ed_btn" value="'.__('Download', 'email-download-link').'" type="submit">';
			$ed = $ed . '<input name="ed_txt_id" id="ed_txt_id" value="'.$ed_email_form_guid.'" type="hidden">';
		$ed = $ed . '</p>';
		
		if($ed_error)
		{
			$ed = $ed . '<span class="ed_validation_full" style="color: #f00;">'.ED_MSG_03.'</span>';
		}
		else
		{
			$ed = $ed . $ed_alt_success;
		}
		
		$ed = $ed . wp_nonce_field('ed_form_subscribers');
		
		$ed = $ed . '</form>';
		
		return $ed;
	}
}
	
class ed_widget_register extends WP_Widget 
{
	function __construct() 
	{
		$widget_ops = array('classname' => 'widget_text ed-widget', 'description' => __(ED_PLUGIN_DISPLAY, 'email-download-link'), ED_PLUGIN_NAME);
		parent::__construct(ED_PLUGIN_NAME, __(ED_PLUGIN_DISPLAY, 'email-download-link'), $widget_ops);
	}
	
	function widget( $args, $instance ) 
	{
		extract( $args, EXTR_SKIP );
		
		$ed_title 		= apply_filters( 'widget_title', empty( $instance['ed_title'] ) ? '' : $instance['ed_title'], $instance, $this->id_base );
		$ed_desc		= $instance['ed_desc'];
		$ed_name		= $instance['ed_name'];
		$ed_name_mand	= $instance['ed_name_mand'];
		$ed_form_id		= $instance['ed_form_id'];

		echo $args['before_widget'];
		if ( ! empty( $ed_title ) )
		{
			echo $args['before_title'] . $ed_title . $args['after_title'];
		}
		
		$form_setting = array(
			'ed_title' 		=> $ed_title,
			'ed_desc' 		=> $ed_desc,
			'ed_name' 		=> $ed_name,
			'ed_name_mand' 	=> $ed_name_mand,
			'ed_form_id' 	=> $ed_form_id
		);
		
		$ed = ed_form_submuit::ed_formdisplay($form_setting);
		echo $ed;
		
		echo $args['after_widget'];
	}
	
	function update( $new_instance, $old_instance ) 
	{
	
		$instance 					= $old_instance;
		$instance['ed_title'] 		= ( ! empty( $new_instance['ed_title'] ) ) ? strip_tags( $new_instance['ed_title'] ) : '';
		$instance['ed_desc'] 		= ( ! empty( $new_instance['ed_desc'] ) ) ? strip_tags( $new_instance['ed_desc'] ) : '';
		$instance['ed_name'] 		= ( ! empty( $new_instance['ed_name'] ) ) ? strip_tags( $new_instance['ed_name'] ) : '';
		$instance['ed_name_mand'] 	= ( ! empty( $new_instance['ed_name_mand'] ) ) ? strip_tags( $new_instance['ed_name_mand'] ) : '';
		$instance['ed_form_id'] 	= ( ! empty( $new_instance['ed_form_id'] ) ) ? strip_tags( $new_instance['ed_form_id'] ) : '';
		return $instance;
	}
	
	function form( $instance ) 
	{
		$defaults = array(
			'ed_title' 		=> '',
		    'ed_desc' 		=> '',
			'ed_name' 		=> '',
			'ed_name_mand' 	=> '',
			'ed_form_id' 	=> ''
        );
		
		$instance 		= wp_parse_args( (array) $instance, $defaults);
		$ed_title 		= $instance['ed_title'];
        $ed_desc 		= $instance['ed_desc'];
        $ed_name 		= $instance['ed_name'];
		$ed_name_mand 	= $instance['ed_name_mand'];
		$ed_form_id 	= $instance['ed_form_id'];
		?>
		<p>
			<label for="<?php echo $this->get_field_id('ed_title'); ?>"><?php _e('Widget title', 'email-download-link'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('ed_title'); ?>" name="<?php echo $this->get_field_name('ed_title'); ?>" type="text" value="<?php echo $ed_title; ?>" />
        </p>
		<p>
			<label for="<?php echo $this->get_field_id('ed_desc'); ?>"><?php _e('Short description for your download form.', 'email-download-link'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('ed_desc'); ?>" name="<?php echo $this->get_field_name('ed_desc'); ?>" type="text" value="<?php echo $ed_desc; ?>" />
        </p>
		<p>
			<label for="<?php echo $this->get_field_id('ed_name'); ?>"><?php _e('Display NAME box', 'email-download-link'); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('ed_name'); ?>" name="<?php echo $this->get_field_name('ed_name'); ?>">
				<option value="YES" <?php $this->ed_selected($ed_name == 'YES'); ?>>YES</option>
				<option value="NO" <?php $this->ed_selected($ed_name == 'NO'); ?>>NO</option>
			</select>
        </p>
		<p>
			<label for="<?php echo $this->get_field_id('ed_name_mand'); ?>"><?php _e('Do you want to set NAME box is mandatory box?', 'email-download-link'); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('ed_name_mand'); ?>" name="<?php echo $this->get_field_name('ed_name_mand'); ?>">
				<option value="YES" <?php $this->ed_selected($ed_name_mand == 'YES'); ?>>YES</option>
				<option value="NO" <?php $this->ed_selected($ed_name_mand == 'NO'); ?>>NO</option>
			</select>
        </p>
		<p>
			<label for="<?php echo $this->get_field_id('ed_form_id'); ?>"><?php _e('Select download link for this form.', 'email-download-link'); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('ed_form_id'); ?>" name="<?php echo $this->get_field_name('ed_form_id'); ?>">
			<option value="0">Random download link</option>
			<?php
			$download = array();
			$download = ed_cls_downloads::ed_download_link_view_page(0, 500, 0);
			
			if(count($download) > 0)
			{
				foreach ($download as $download_data)
				{
					?>
					<option value="<?php echo $download_data['ed_form_id']; ?>" <?php $this->ed_selected($download_data['ed_form_id'] == $ed_form_id); ?>>
					<?php echo $download_data['ed_form_title']; ?> (<?php echo $download_data['ed_form_id']; ?>)
					</option>
					<?php
				}
			}
			?>
			</select>
        </p>
		<?php
	}
	
	function ed_selected($var) 
	{
		if ($var==1 || $var==true) 
		{
			echo 'selected="selected"';
		}
	}
}

function emaildownload_shortcode( $atts ) 
{
	if ( ! is_array( $atts ) )
	{
		return '';
	}
	
	//[email-download-link namefield="YES" id="1"]
	$namefield 	= isset($atts['namefield']) ? $atts['namefield'] : 'YES';
	$id 		= isset($atts['id']) ? $atts['id'] : '0';
	
	if($namefield <> "YES")
	{
		$namefield = "NO";
	}
	
	if(!is_numeric($id))
	{
		$id = 0;
	}
	
	$arr = array();
	$arr["ed_title"] 		= "";
	$arr["ed_desc"] 		= "";
	$arr["ed_name"] 		= $namefield;
	$arr["ed_name_mand"] 	= $namefield;
	$arr["ed_form_id"] 		= $id;
	
	return ed_form_submuit::ed_formdisplay($arr);
}

function ed_download_link( $namefield = "YES", $id = 0 )
{
	if($namefield <> "YES")
	{
		$namefield = "NO";
	}
	
	if(!is_numeric($id))
	{
		$id = 0;
	}
	
	$arr = array();
	$arr["ed_title"] 		= "";
	$arr["ed_desc"] 		= "";
	$arr["ed_name"] 		= $namefield;
	$arr["ed_name_mand"] 	= $namefield;
	$arr["ed_form_id"] 		= $id;
	echo ed_form_submuit::ed_formdisplay($arr);
}
?>