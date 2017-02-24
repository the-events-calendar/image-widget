<?php
/**
 * Image widget admin template
 */

// Block direct requests
if ( ! defined( 'ABSPATH' ) )
	die( '-1' );

$id_prefix = $this->get_field_id( '' );
?>
<div class="uploader">
	<input type="submit" class="button" name="<?php echo esc_attr( $this->get_field_name( 'uploader_button' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'uploader_button' ) ); ?>" value="<?php esc_attr_e( 'Select an Image', 'image-widget' ); ?>" onclick="imageWidget.uploader( '<?php echo esc_attr( $this->id ); ?>', '<?php echo esc_attr( $id_prefix ); ?>' ); return false;" />
	<div class="tribe_preview" id="<?php echo esc_attr( $this->get_field_id( 'preview' ) ); ?>">
		<?php echo $this->get_image_html( $instance, false ); ?>
	</div>
	<input type="hidden" id="<?php echo esc_attr( $this->get_field_id( 'attachment_id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'attachment_id' ) ); ?>" value="<?php echo abs( $instance['attachment_id'] ); ?>" />
	<input type="hidden" id="<?php echo esc_attr( $this->get_field_id( 'imageurl' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'imageurl' ) ); ?>" value="<?php echo esc_attr( $instance['imageurl'] ); ?>" />
</div>
<br clear="all" />

<div id="<?php echo esc_attr( $this->get_field_id( 'fields' ) ); ?>" <?php if ( empty( $instance['attachment_id'] ) && empty( $instance['imageurl'] ) ) { ?>style="display:none;"<?php } ?>>
	<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'image-widget' ); ?>:</label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( strip_tags( $instance['title'] ) ); ?>" /></p>

	<p><label for="<?php echo esc_attr( $this->get_field_id( 'alt' ) ); ?>"><?php esc_html_e( 'Alternate Text', 'image-widget' ); ?>:</label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'alt' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'alt' ) ); ?>" type="text" value="<?php echo esc_attr( strip_tags( $instance['alt'] ) ); ?>" /></p>

	<p><label for="<?php echo esc_attr( $this->get_field_id( 'rel' ) ); ?>"><?php esc_html_e( 'Related', 'image-widget' ); ?>:</label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'rel' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'rel' ) ); ?>" type="text" value="<?php echo esc_attr( strip_tags( $instance['rel'] ) ); ?>" /><br>
		<?php
		// Description text for the "Related" field.
		$related_description = sprintf(
			esc_html__( 'A recommended HTML5 related terms list is available %1$shere%2$s.', 'image-widget' ),
			'<a href="http://microformats.org/wiki/existing-rel-values#HTML5_link_type_extensions" target="_blank">',
			'</a>'
		);

		// The output has been properly escaped above.
		echo "<span class='description'> $related_description </span>"; ?>
	</p>

	<p><label for="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"><?php esc_html_e( 'Caption', 'image-widget' ); ?>:</label>
	<textarea rows="8" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'description' ) ); ?>"><?php echo format_to_edit( $instance['description'] ); ?></textarea></p>

	<p><label for="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>"><?php esc_html_e( 'Link', 'image-widget' ); ?>:</label>
	<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'link' ) ); ?>" type="text" value="<?php echo esc_attr( strip_tags( $instance['link'] ) ); ?>" /><br />
	<label for="<?php echo esc_attr( $this->get_field_id( 'linkid' ) ); ?>"><?php esc_html_e( 'Link ID', 'image-widget' ); ?>:</label>
	<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'linkid' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'linkid' ) ); ?>" type="text" value="<?php echo esc_attr( strip_tags( $instance['linkid'] ) ); ?>" /><br />
	<select name="<?php echo esc_attr( $this->get_field_name( 'linktarget' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'linktarget' ) ); ?>">
		<option value="_self"<?php selected( $instance['linktarget'], '_self' ); ?>><?php esc_html_e( 'Stay in Window', 'image-widget' ); ?></option>
		<option value="_blank"<?php selected( $instance['linktarget'], '_blank' ); ?>><?php esc_html_e( 'Open New Window', 'image-widget' ); ?></option>
	</select></p>

	<?php
	// Backwards compatibility prior to storing attachment ids
	?>
	<div id="<?php echo esc_attr( $this->get_field_id( 'custom_size_selector' ) ); ?>" <?php if ( empty( $instance['attachment_id'] ) && ! empty( $instance['imageurl'] ) ) { $instance['size'] = self::CUSTOM_IMAGE_SIZE_SLUG; ?>style="display:none;"<?php } ?>>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'size' ) ); ?>"><?php esc_html_e( 'Size', 'image-widget' ); ?>:</label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'size' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'size' ) ); ?>" onChange="imageWidget.toggleSizes( '<?php echo esc_attr( $this->id ); ?>', '<?php echo esc_attr( $id_prefix ); ?>' );">
				<?php foreach ( $this->possible_image_sizes() as $size_key => $size_label ) : ?>
					<option value="<?php echo esc_attr( $size_key ); ?>"<?php selected( $instance['size'], $size_key ); ?>><?php echo esc_html( $size_label ); ?></option>
				<?php endforeach ?>
			</select>
		</p>
	</div>
	<div id="<?php echo esc_attr( $this->get_field_id( 'custom_size_fields' ) ); ?>" <?php if ( empty( $instance['size'] ) || $instance['size'] != self::CUSTOM_IMAGE_SIZE_SLUG ) { ?>style="display:none;"<?php } ?>>

		<input type="hidden" id="<?php echo esc_attr( $this->get_field_id( 'aspect_ratio' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'aspect_ratio' ) ); ?>" value="<?php echo esc_attr( $this->get_image_aspect_ratio( $instance ) ); ?>" />

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'width' ) ); ?>"><?php esc_html_e( 'Width', 'image-widget' ); ?>:</label>
		<input id="<?php echo esc_attr( $this->get_field_id( 'width' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'width' ) ); ?>" type="text" value="<?php echo esc_attr( strip_tags( $instance['width'] ) ); ?>" onchange="imageWidget.changeImgWidth( '<?php echo esc_attr( $this->id ); ?>', '<?php echo esc_attr( $id_prefix ); ?>' )" size="3" /></p>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'height' ) ); ?>"><?php esc_html_e( 'Height', 'image-widget' ); ?>:</label>
		<input id="<?php echo esc_attr( $this->get_field_id( 'height' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'height' ) ); ?>" type="text" value="<?php echo esc_attr( strip_tags( $instance['height'] ) ); ?>" onchange="imageWidget.changeImgHeight( '<?php echo esc_attr( $this->id ); ?>', '<?php echo esc_attr( $id_prefix ); ?>' )" size="3" /></p>

	</div>

	<p><label for="<?php echo esc_attr(  $this->get_field_id( 'align' ) ); ?>"><?php esc_html_e( 'Align', 'image-widget' ); ?>:</label>
	<select name="<?php echo esc_attr(  $this->get_field_name( 'align' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'align' ) ); ?>">
		<option value="none"<?php selected( $instance['align'], 'none' ); ?>><?php esc_html_e( 'none', 'image-widget' ); ?></option>
		<option value="left"<?php selected( $instance['align'], 'left' ); ?>><?php esc_html_e( 'left', 'image-widget' ); ?></option>
		<option value="center"<?php selected( $instance['align'], 'center' ); ?>><?php esc_html_e( 'center', 'image-widget' ); ?></option>
		<option value="right"<?php selected( $instance['align'], 'right' ); ?>><?php esc_html_e( 'right', 'image-widget' ); ?></option>
	</select></p>
</div>
