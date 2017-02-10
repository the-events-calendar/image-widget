<?php
/**
 * Deprecated Widget admin template
 */

// Block direct requests
if ( !defined('ABSPATH') )
	die('-1');
?>
<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'image-widget'); ?>:</label>
	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr(strip_tags($instance['title'])); ?>" /></p>

<p><label for="<?php echo $this->get_field_id('image'); ?>"><?php _e('Image', 'image-widget'); ?>:</label>
	<?php
	$media_upload_iframe_src = "media-upload.php?type=image&post_id=0&widget_id=".$this->id; //NOTE #1: the widget id is added here to allow uploader to only return array if this is used with image widget so that all other uploads are not harmed.
	$image_upload_iframe_src = apply_filters('image_upload_iframe_src', "$media_upload_iframe_src");
	$image_title = (!empty($instance['image'])) ? __('Change Image', 'image-widget') : __('Add Image', 'image-widget');
	?><br />
	<a href="<?php echo $image_upload_iframe_src; ?>&TB_iframe=true" id="add_image-<?php echo $this->get_field_id('image'); ?>" class="thickbox-image-widget" title='<?php echo $image_title; ?>' onClick="imageWidget.setActiveWidget('<?php echo $this->id; ?>');return false;" style="text-decoration:none"><img src='images/media-button-image.gif' alt='<?php echo $image_title; ?>' align="absmiddle" /> <?php echo $image_title; ?></a>
<div id="display-<?php echo $this->get_field_id('image'); ?>"><?php echo $this->get_image_html($instance); ?></div>
<br clear="all" />
<input id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('image'); ?>" type="hidden" value="<?php echo $instance['image']; ?>" />
</p>

<p><label for="<?php echo $this->get_field_id('description'); ?>"><?php _e('Caption', 'image-widget'); ?>:</label>
	<textarea rows="8" class="widefat" id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>"><?php echo format_to_edit($instance['description']); ?></textarea></p>

<p><label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Link', 'image-widget'); ?>:</label>
	<input class="widefat" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="text" value="<?php echo esc_attr(strip_tags($instance['link'])); ?>" /><br />
	<select name="<?php echo $this->get_field_name('linktarget'); ?>" id="<?php echo $this->get_field_id('linktarget'); ?>">
		<option value="_self"<?php selected( $instance['linktarget'], '_self' ); ?>><?php _e('Stay in Window', 'image-widget'); ?></option>
		<option value="_blank"<?php selected( $instance['linktarget'], '_blank' ); ?>><?php _e('Open New Window', 'image-widget'); ?></option>
	</select></p>

<p><label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Width', 'image-widget'); ?>:</label>
	<input id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo esc_attr(strip_tags($instance['width'])); ?>" onchange="imageWidget.changeImgWidth('<?php echo $this->id; ?>')" /></p>

<p><label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Height', 'image-widget'); ?>:</label>
	<input id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo esc_attr(strip_tags($instance['height'])); ?>" onchange="imageWidget.changeImgHeight('<?php echo $this->id; ?>')" /></p>

<p><label for="<?php echo $this->get_field_id('align'); ?>"><?php _e('Align', 'image-widget'); ?>:</label>
	<select name="<?php echo $this->get_field_name('align'); ?>" id="<?php echo $this->get_field_id('align'); ?>" onchange="imageWidget.changeImgAlign('<?php echo $this->id; ?>')">
		<option value="none"<?php selected( $instance['align'], 'none' ); ?>><?php _e('none', 'image-widget'); ?></option>
		<option value="left"<?php selected( $instance['align'], 'left' ); ?>><?php _e('left', 'image-widget'); ?></option>
		<option value="center"<?php selected( $instance['align'], 'center' ); ?>><?php _e('center', 'image-widget'); ?></option>
		<option value="right"<?php selected( $instance['align'], 'right' ); ?>><?php _e('right', 'image-widget'); ?></option>
	</select></p>

<p><label for="<?php echo $this->get_field_id('alt'); ?>"><?php _e('Alternate Text', 'image-widget'); ?>:</label>
	<input id="<?php echo $this->get_field_id('alt'); ?>" name="<?php echo $this->get_field_name('alt'); ?>" type="text" value="<?php echo esc_attr(strip_tags($instance['alt'])); ?>" /></p>