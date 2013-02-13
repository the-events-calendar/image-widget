<?php
/**
 * Deprecated image upload integration code to support legacy versions of WordPress
 * @author Modern Tribe, Inc.
 */

class ImageWidgetDeprecated {

	private $id_base;

	function __construct( $widget ) {
		add_action( 'admin_init', array( $this, 'admin_setup' ) );
		$this->id_base = $widget->id_base;
	}

	function admin_setup() {
		global $pagenow;
		if ( 'widgets.php' == $pagenow ) {
			wp_enqueue_style( 'thickbox' );
			wp_enqueue_script( 'tribe-image-widget', plugins_url('resources/js/image-widget.deprecated.js', dirname(__FILE__)), array('thickbox'), Tribe_Image_Widget::VERSION, TRUE );
		}
		elseif ( 'media-upload.php' == $pagenow || 'async-upload.php' == $pagenow ) {
			wp_enqueue_script( 'tribe-image-widget-fix-uploader', plugins_url('resources/js/image-widget.deprecated.upload-fixer.js', dirname(__FILE__)), array('jquery'), Tribe_Image_Widget::VERSION, TRUE );
			add_filter( 'image_send_to_editor', array( $this,'image_send_to_editor'), 1, 8 );
			add_filter( 'gettext', array( $this, 'replace_text_in_thickbox' ), 1, 3 );
			add_filter( 'media_upload_tabs', array( $this, 'media_upload_tabs' ) );
			add_filter( 'image_widget_image_url', array( $this, 'https_cleanup' ) );
		}
		$this->fix_async_upload_image();
	}

	function fix_async_upload_image() {
		if(isset($_REQUEST['attachment_id'])) {
			$id = (int) $_REQUEST['attachment_id'];
			$GLOBALS['post'] = get_post( $id );
		}
	}

	/**
	 * Test context to see if the uploader is being used for the image widget or for other regular uploads
	 *
	 * @author Modern Tribe, Inc. (Peter Chester)
	 */
	function is_sp_widget_context() {
		if ( isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'],$this->id_base) !== false ) {
			return true;
		} elseif ( isset($_REQUEST['_wp_http_referer']) && strpos($_REQUEST['_wp_http_referer'],$this->id_base) !== false ) {
			return true;
		} elseif ( isset($_REQUEST['widget_id']) && strpos($_REQUEST['widget_id'],$this->id_base) !== false ) {
			return true;
		}
		return false;
	}

	/**
	 * Somewhat hacky way of replacing "Insert into Post" with "Insert into Widget"
	 *
	 * @param string $translated_text text that has already been translated (normally passed straight through)
	 * @param string $source_text text as it is in the code
	 * @param string $domain domain of the text
	 * @author Modern Tribe, Inc. (Peter Chester)
	 */
	function replace_text_in_thickbox($translated_text, $source_text, $domain) {
		if ( $this->is_sp_widget_context() ) {
			if ('Insert into Post' == $source_text) {
				return __('Insert Into Widget', 'image_widget' );
			}
		}
		return $translated_text;
	}

	/**
	 * Filter image_end_to_editor results
	 *
	 * @param string $html
	 * @param int $id
	 * @param string $alt
	 * @param string $title
	 * @param string $align
	 * @param string $url
	 * @param array $size
	 * @return string javascript array of attachment url and id or just the url
	 * @author Modern Tribe, Inc. (Peter Chester)
	 */
	function image_send_to_editor( $html, $id, $caption, $title, $align, $url, $size, $alt = '' ) {
		// Normally, media uploader return an HTML string (in this case, typically a complete image tag surrounded by a caption).
		// Don't change that; instead, send custom javascript variables back to opener.
		// Check that this is for the widget. Shouldn't hurt anything if it runs, but let's do it needlessly.
		if ( $this->is_sp_widget_context() ) {
			if ($alt=='') $alt = $title;
			?>
		<script type="text/javascript">
			// send image variables back to opener
			var win = window.dialogArguments || opener || parent || top;
			win.IW_html = '<?php echo addslashes($html); ?>';
			win.IW_img_id = '<?php echo $id; ?>';
			win.IW_alt = '<?php echo addslashes($alt); ?>';
			win.IW_caption = '<?php echo addslashes($caption); ?>';
			win.IW_title = '<?php echo addslashes($title); ?>';
			win.IW_align = '<?php echo esc_attr($align); ?>';
			win.IW_url = '<?php echo esc_url($url); ?>';
			win.IW_size = '<?php echo esc_attr($size); ?>';
		</script>
		<?php
		}
		return $html;
	}

	/**
	 * Remove from url tab until that functionality is added to widgets.
	 *
	 * @param array $tabs
	 * @author Modern Tribe, Inc. (Peter Chester)
	 */
	function media_upload_tabs($tabs) {
		if ( $this->is_sp_widget_context() ) {
			unset($tabs['type_url']);
		}
		return $tabs;
	}

	/**
	 * Adjust the image url on output to account for SSL.
	 *
	 * @param string $imageurl
	 * @return string $imageurl
	 * @author Modern Tribe, Inc. (Peter Chester)
	 */
	function https_cleanup( $imageurl = '' ) {
		// TODO: 3.5: Document that this is deprecated???
		if( isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on" ) {
			$imageurl = str_replace('http://', 'https://', $imageurl);
		} else {
			$imageurl = str_replace('https://', 'http://', $imageurl);
		}
		return $imageurl;
	}



	/**
	 * Retrieve resized image URL
	 *
	 * @param int $id Post ID or Attachment ID
	 * @param int $width desired width of image (optional)
	 * @param int $height desired height of image (optional)
	 * @return string URL
	 * @author Modern Tribe, Inc. (Peter Chester)
	 */
	public static function get_image_url( $id, $width=false, $height=false ) {
		/**/
		// Get attachment and resize but return attachment path (needs to return url)
		$attachment = wp_get_attachment_metadata( $id );
		$attachment_url = wp_get_attachment_url( $id );
		if (isset($attachment_url)) {
			if ($width && $height) {
				$uploads = wp_upload_dir();
				$imgpath = $uploads['basedir'].'/'.$attachment['file'];
				if (WP_DEBUG) {
					error_log(__CLASS__.'->'.__FUNCTION__.'() $imgpath = '.$imgpath);
				}
				$image = self::resize_image( $imgpath, $width, $height );
				if ( $image && !is_wp_error( $image ) ) {
					$image = path_join( dirname($attachment_url), basename($image) );
				} else {
					$image = $attachment_url;
				}
			} else {
				$image = $attachment_url;
			}
			if (isset($image)) {
				return $image;
			}
		}
	}

	public static function resize_image( $file, $max_w, $max_h ) {
		if ( function_exists('wp_get_image_editor') ) {
			$dest_file = $file;
			if ( function_exists('wp_get_image_editor') ) {
				$editor = wp_get_image_editor( $file );
				if ( is_wp_error( $editor ) )
					return $editor;

				$resized = $editor->resize( $max_w, $max_h );
				if ( is_wp_error( $resized ) )
					return $resized;

				$dest_file = $editor->generate_filename();
				$saved = $editor->save( $dest_file );

				if ( is_wp_error( $saved ) )
					return $saved;

			}
			return $dest_file;
		} else {
			return image_resize( $file, $max_w, $max_h );
		}
	}

}
