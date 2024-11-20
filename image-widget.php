<?php
/*
Plugin Name: Image Widget
Plugin URI: https://wordpress.org/plugins/image-widget/
Description: A simple image widget that uses the native WordPress media manager to add image widgets to your site.
Author: The Events Calendar
Version: 4.4.11
Author URI: https://evnt.is/1aor
Text Domain: image-widget
Domain Path: /lang
*/

// Block direct requests
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

// Load the widget on widgets_init
function tribe_load_image_widget() {
	register_widget( 'Tribe_Image_Widget' );
}
add_action( 'widgets_init', 'tribe_load_image_widget' );

class Tribe_Image_Widget extends WP_Widget {

	const VERSION = '4.4.11';

	const CUSTOM_IMAGE_SIZE_SLUG = 'tribe_image_widget_custom';

	const VERSION_KEY = '_image_widget_version';

	/**
	 * Tribe Image Widget constructor
	 */
	public function __construct() {
		load_plugin_textdomain( 'image-widget', false, trailingslashit( basename( dirname( __FILE__ ) ) ) . 'lang/' );

		$widget_ops  = array( 'classname' => 'widget_sp_image', 'description' => __( 'Showcase a single image with a Title, URL, and a Description', 'image-widget' ) );
		$control_ops = array( 'id_base' => 'widget_sp_image' );

		parent::__construct( 'widget_sp_image', __( 'Image Widget', 'image-widget' ), $widget_ops, $control_ops );

		if ( $this->use_old_uploader() ) {
			require_once( 'lib/ImageWidgetDeprecated.php' );
			new ImageWidgetDeprecated( $this );
		} else {
			add_action( 'sidebar_admin_setup', array( $this, 'admin_setup' ) );
		}

		// fire admin_setup if we are in the customizer
		add_action( 'admin_enqueue_scripts', array( $this, 'maybe_admin_setup' ) );
	}

	/**
	 * Test to see if this version of WordPress supports the new image manager.
	 *
	 * @return bool True if the current version of WordPress does NOT support the current image management tech.
	 */
	private function use_old_uploader() {
		if ( defined( 'IMAGE_WIDGET_COMPATIBILITY_TEST' ) ) {
			return true;
		}

		return ! function_exists( 'wp_enqueue_media' );
	}

	/**
	 * Enqueue all the javascript and CSS.
	 */
	public function admin_setup() {
		wp_enqueue_media();

		wp_enqueue_style( 'tribe-image-widget', plugins_url( 'resources/css/admin.css', __FILE__ ), array(), self::VERSION );
		wp_enqueue_script( 'tribe-image-widget', plugins_url( 'resources/js/image-widget.js', __FILE__ ), array( 'jquery', 'media-upload', 'media-views' ), self::VERSION );

		wp_localize_script( 'tribe-image-widget', 'TribeImageWidget', array(
			'frame_title'  => __( 'Select an Image', 'image-widget' ),
			'button_title' => __( 'Insert Into Widget', 'image-widget' ),
		) );
	}

	/**
	 * Trigger the enqueueing of admin scripts and styles on widget admin page and in the "Customizer" view.
	 */
	public function maybe_admin_setup() {
		$screen = get_current_screen();

		if ( 'customize' !== $screen->base ) {
			return;
		}

		$this->admin_setup();
	}

	/**
	 * Widget frontend output
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		extract( $args );

		$instance = wp_parse_args( (array) $instance, self::get_defaults() );

		if ( ! empty( $instance['imageurl'] ) || ! empty( $instance['attachment_id'] ) ) {

			/**
			 * Filters the title of the widget.
			 *
			 * @link https://developer.wordpress.org/reference/hooks/widget_title/
			 *
			 * @param string $title    The widget title.
			 * @param array  $instance The array of widget options.
			 * @param mixed  $id_base  The widget ID.
			 */
			$instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

			/**
			 * Filters the text of the widget.
			 *
			 * @link https://developer.wordpress.org/reference/hooks/widget_text/
			 *
			 * @param string $description The widget description.
			 * @param array  $args        The array of widget arguments.
			 * @param array  $instance    The array of widget options.
			 */
			$instance['description'] = apply_filters( 'widget_text', $instance['description'], $args, $instance );

			/**
			 * Filters the link of the image widget.
			 *
			 * @since 4.0.0
			 *
			 * @param string $link     The widget image link.
			 * @param array  $args     The array of widget arguments.
			 * @param array  $instance The array of widget options.
			 */
			$instance['link'] = apply_filters( 'image_widget_image_link', esc_url( $instance['link'] ), $args, $instance );

			/**
			 * Filters the link id of the image widget.
			 *
			 * @since 4.0.0
			 *
			 * @param string $linkid   The widget image link ID.
			 * @param array  $args     The array of widget arguments.
			 * @param array  $instance The array of widget options.
			 */
			$instance['linkid'] = apply_filters( 'image_widget_image_link_id', esc_attr( $instance['linkid'] ), $args, $instance );

			/**
			 * Filters the link title of the image widget.
			 *
			 * @since 4.0.0
			 *
			 * @param string $linktitle The widget image link title.
			 * @param array  $args      The array of widget arguments.
			 * @param array  $instance  The array of widget options.
			 */
			$instance['linktitle'] = apply_filters( 'image_widget_image_link_title', esc_attr( $instance['linktitle'] ), $args, $instance );

			/**
			 * Filters the link target of the image widget.
			 *
			 * @since 4.0.0
			 *
			 * @param string $linktarget The widget image link target.
			 * @param array  $args       The array of widget arguments.
			 * @param array  $instance   The array of widget options.
			 */
			$instance['linktarget'] = apply_filters( 'image_widget_image_link_target', esc_attr( $instance['linktarget'] ), $args, $instance );

			/**
			 * Filters the width of the image in the widget.
			 *
			 * @since 4.0.0
			 *
			 * @param string $width    The width of the widget image.
			 * @param array  $args     The array of widget arguments.
			 * @param array  $instance The array of widget options.
			 */
			$instance['width'] = apply_filters( 'image_widget_image_width', abs( $instance['width'] ), $args, $instance );

			/**
			 * Filters the height of the image in the widget.
			 *
			 * @since 4.0.0
			 *
			 * @param string $height   The height of the widget image.
			 * @param array  $args     The array of widget arguments.
			 * @param array  $instance The array of widget options.
			 */
			$instance['height'] = apply_filters( 'image_widget_image_height', abs( $instance['height'] ), $args, $instance );

			/**
			 * Filters the max width of the image in the widget.
			 *
			 * @since 4.0.0
			 *
			 * @param string $maxwidth The max width of the widget image.
			 * @param array  $args     The array of widget arguments.
			 * @param array  $instance The array of widget options.
			 */
			$instance['maxwidth'] = apply_filters( 'image_widget_image_maxwidth', esc_attr( $instance['maxwidth'] ), $args, $instance );

			/**
			 * Filters the max height of the image in the widget.
			 *
			 * @since 4.0.0
			 *
			 * @param string $maxheight The max height of the widget image.
			 * @param array  $args      The array of widget arguments.
			 * @param array  $instance  The array of widget options.
			 */
			$instance['maxheight'] = apply_filters( 'image_widget_image_maxheight', esc_attr( $instance['maxheight'] ), $args, $instance );

			/**
			 * Filters the alignment of the image in the widget.
			 *
			 * @since 4.0.0
			 *
			 * @param string $align    The alignment of the widget image.
			 * @param array  $args     The array of widget arguments.
			 * @param array  $instance The array of widget options.
			 */
			$instance['align'] = apply_filters( 'image_widget_image_align', esc_attr( $instance['align'] ), $args, $instance );

			/**
			 * Filters the alt text of the image in the widget.
			 *
			 * @since 4.0.0
			 *
			 * @param string $alt      The alt text of the widget image.
			 * @param array  $args     The array of widget arguments.
			 * @param array  $instance The array of widget options.
			 */
			$instance['alt'] = apply_filters( 'image_widget_image_alt', esc_attr( $instance['alt'] ), $args, $instance );

			/**
			 * Filters the rel attribute of the image in the widget.
			 *
			 * @since 4.0.0
			 *
			 * @param string $rel      The rel attribute of the widget image.
			 * @param array  $args     The array of widget arguments.
			 * @param array  $instance The array of widget options.
			 */
			$instance['rel'] = apply_filters( 'image_widget_image_rel', esc_attr( $instance['rel'] ), $args, $instance );

			/**
			 * Checks if the IMAGE_WIDGET_COMPATIBILITY_TEST constant is defined.
			 */
			if ( ! defined( 'IMAGE_WIDGET_COMPATIBILITY_TEST' ) ) {

			    /**
			     * Assigns the attachment_id field in the instance array a value based on condition.
			     */
			    $instance['attachment_id'] = ( $instance['attachment_id'] > 0 ) ? $instance['attachment_id'] : $instance['image'];

			    /**
			     * Filters the attachment ID of the image in the widget.
			     *
			     * @since 4.0.0
			     *
			     * @param int   $attachment_id The attachment ID of the widget image.
			     * @param array $args          The array of widget arguments.
			     * @param array $instance      The array of widget options.
			     */
			    $instance['attachment_id'] = apply_filters( 'image_widget_image_attachment_id', abs( $instance['attachment_id'] ), $args, $instance );

			    /**
			     * Filters the size of the image in the widget.
			     *
			     * @since 4.0.0
			     *
			     * @param string $size     The size of the widget image.
			     * @param array  $args     The array of widget arguments.
			     * @param array  $instance The array of widget options.
			     */
			    $instance['size'] = apply_filters( 'image_widget_image_size', esc_attr( $instance['size'] ), $args, $instance );
			}

			/**
			 * Filters the URL of the image in the widget.
			 *
			 * @since 4.0.0
			 *
			 * @param string $imageurl The URL of the widget image.
			 * @param array  $args     The array of widget arguments.
			 * @param array  $instance The array of widget options.
			 */
			$instance['imageurl'] = apply_filters( 'image_widget_image_url', esc_url( $instance['imageurl'] ), $args, $instance );

			// No longer using extracted vars. This is here for backwards compatibility.
			extract( $instance );

			include( $this->getTemplateHierarchy( 'widget' ) );
		}
	}

	/**
	 * Update widget options
	 *
	 * @param object $new_instance Widget Instance
	 * @param object $old_instance Widget Instance
	 * @return object
	 */
	public function update( $new_instance, $old_instance ) {

		$instance     = $old_instance;
		$new_instance = wp_parse_args( (array) $new_instance, self::get_defaults() );

		$instance['title'] = strip_tags( $new_instance['title'] );

		if ( current_user_can( 'unfiltered_html' ) ) {
			$instance['description'] = $new_instance['description'];
		} else {
			$instance['description'] = wp_kses_post( $new_instance['description'] );
		}

		/**
		 * Make the description filterable; especially useful for working with allowing/disallowing HTML and other content.
		 *
		 * @since 4.4.6
		 *
		 * @param $description The current value of $instance['description'].
		 * @param $new_instance The new instance of the widget, i.e. the new values being saved for it.
		 * @param $old_instance The pre-existing instance of the widget, i.e. the values that are potentially being updated.
		 */
		$instance['description'] = apply_filters( 'tribe_image_widget_instance_description', $instance['description'], $new_instance, $old_instance );

		$instance['link']       = $new_instance['link'];
		$instance['linktitle']  = $new_instance['linktitle'];
		$instance['linkid']     = $new_instance['linkid'];
		$instance['linktarget'] = $new_instance['linktarget'];
		$instance['width']      = abs( $new_instance['width'] );
		$instance['height']     = abs( $new_instance['height'] );

		if ( ! defined( 'IMAGE_WIDGET_COMPATIBILITY_TEST' ) ) {
			$instance['size'] = $new_instance['size'];
		}

		$instance['align'] = $new_instance['align'];
		$instance['alt']   = $new_instance['alt'];
		$instance['rel']   = $new_instance['rel'];

		// Reverse compatibility with $image, now called $attachement_id
		if ( ! defined( 'IMAGE_WIDGET_COMPATIBILITY_TEST' ) && $new_instance['attachment_id'] > 0 ) {
			$instance['attachment_id'] = abs( $new_instance['attachment_id'] );
		} elseif ( $new_instance['image'] > 0 ) {
			$instance['attachment_id'] = $instance['image'] = abs( $new_instance['image'] );
			if ( class_exists( 'ImageWidgetDeprecated' ) ) {
				$instance['imageurl'] = ImageWidgetDeprecated::get_image_url( $instance['image'], $instance['width'], $instance['height'] );  // image resizing not working right now
			}
		}

		$instance['imageurl']     = $new_instance['imageurl']; // deprecated
		$instance['aspect_ratio'] = $this->get_image_aspect_ratio( $instance );

		return $instance;
	}

	/**
	 * Form UI
	 *
	 * @param object $instance Widget Instance
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, self::get_defaults() );
		if ( $this->use_old_uploader() ) {
			include( $this->getTemplateHierarchy( 'widget-admin.deprecated' ) );
		} else {
			include( $this->getTemplateHierarchy( 'widget-admin' ) );
		}
	}

	/**
	 * Render an array of default values.
	 *
	 * @return array default values
	 */
	private static function get_defaults() {

		$defaults = array(
			'title'       => '',
			'description' => '',
			'link'        => '',
			'linkid'      => '',
			'linktitle'   => '',
			'linktarget'  => '',
			'width'       => 0,
			'height'      => 0,
			'maxwidth'    => '100%',
			'maxheight'   => '',
			'image'       => 0, // reverse compatible - now attachement_id
			'imageurl'    => '', // reverse compatible.
			'align'       => 'none',
			'alt'         => '',
			'rel'         => '',
		);

		if ( ! defined( 'IMAGE_WIDGET_COMPATIBILITY_TEST' ) ) {
			$defaults['size']          = self::CUSTOM_IMAGE_SIZE_SLUG;
			$defaults['attachment_id'] = 0;
		}
		/**
		 * Allow users to customize the default values of various Image Widget options.
		 *
		 * @since 4.4.7
		 *
		 * @param array $defaults The array of default option values.
		 */
		return apply_filters( 'image_widget_option_defaults', $defaults );
	}

	/**
	 * Render the image html output.
	 *
	 * @param array $instance
	 * @param bool $include_link will only render the link if this is set to true. Otherwise link is ignored.
	 * @return string image html
	 */
	private function get_image_html( $instance, $include_link = true ) {

		// Backwards compatible image display.
		if ( $instance['attachment_id'] == 0 && $instance['image'] > 0 ) {
			$instance['attachment_id'] = $instance['image'];
		}

		$output = '';

		if ( $include_link && ! empty( $instance['link'] ) ) {

			$attr = array(
				'href'   => $instance['link'],
				'id'     => $instance['linkid'],
				'target' => $instance['linktarget'],
				'class'  => $this->widget_options['classname'] . '-image-link',
				'title'  => ( ! empty( $instance['linktitle'] ) ) ? $instance['linktitle'] : $instance['title'],
				'rel'    => $instance['rel'],
			);

			$attr = apply_filters( 'image_widget_link_attributes', $attr, $instance );
			$attr = array_map( 'esc_attr', $attr );

			$output = '<a';

			foreach ( $attr as $name => $value ) {
				if ( ! empty( $value ) ) {
					$output .= sprintf( ' %s="%s"', $name, $value );
				}
			}
			$output .= '>';
		}

		$size = $this->get_image_size( $instance );

		if ( is_array( $size ) ) {
			$instance['width']  = $size[0];
			$instance['height'] = $size[1];
		} elseif ( ! empty( $instance['attachment_id'] ) ) {
			//$instance['width'] = $instance['height'] = 0;
			$image_details = wp_get_attachment_image_src( $instance['attachment_id'], $size );
			if ( $image_details ) {
				$instance['imageurl'] = $image_details[0];
				$instance['width']    = $image_details[1];
				$instance['height']   = $image_details[2];
			}

			$image_srcset = function_exists( 'wp_get_attachment_image_srcset' )
				? wp_get_attachment_image_srcset( $instance['attachment_id'], $size )
				: false;
			if ( $image_srcset ) {
				$instance['srcset'] = $image_srcset;

				$image_sizes = function_exists( 'wp_get_attachment_image_sizes' )
					? wp_get_attachment_image_sizes( $instance['attachment_id'], $size )
					: false;
	 			if ( $image_sizes ) {
					$instance['sizes'] = $image_sizes;
				}
			}
		}

		$instance['width']  = abs( $instance['width'] );
		$instance['height'] = abs( $instance['height'] );

		$attr = array();

		if ( ! empty( $instance['alt'] ) ) {
			$attr['alt'] = $instance['alt'];
		} elseif ( ! empty( $instance['title'] ) ) {
			$attr['alt'] = $instance['title'];
		}

		if ( is_array( $size ) ) {
			$attr['class'] = 'attachment-' . join( 'x', $size );
		} else {
			$attr['class'] = 'attachment-' . $size;
		}

		$attr['style'] = '';
		if ( ! empty( $instance['maxwidth'] ) ) {
			$attr['style'] .= "max-width: {$instance['maxwidth']};";
		}

		if ( ! empty( $instance['maxheight'] ) ) {
			$attr['style'] .= "max-height: {$instance['maxheight']};";
		}

		if ( ! empty( $instance['align'] ) && $instance['align'] != 'none' ) {
			$attr['class'] .= " align{$instance['align']}";
		}

		if ( ! empty( $instance['srcset'] ) ) {
			$attr['srcset'] = $instance['srcset'];
		}

		if ( ! empty( $instance['sizes'] ) ) {
			$attr['sizes'] = $instance['sizes'];
		}

		/**
		 * Allow filtering of the image attributes used on the front-end.
		 *
		 * @param array $attr Image attributes to be filtered.
		 * @param array $instance The current widget instance.
		 */
		$attr = apply_filters( 'image_widget_image_attributes', $attr, $instance );

		// If there is an imageurl, use it to render the image. Eventually we should kill this and simply rely on attachment_ids.
		if ( ! empty( $instance['imageurl'] ) ) {
			// If all we have is an image src url we can still render an image.
			$attr['src'] = esc_url( $instance['imageurl'] );
			$attr        = array_map( 'esc_attr', $attr );
			$hwstring    = image_hwstring( $instance['width'], $instance['height'] );

			$output .= rtrim( "<img $hwstring" );

			foreach ( $attr as $name => $value ) {
				$output .= sprintf( ' %s="%s"', $name, $value );
			}

			$output .= ' />';

		} elseif ( abs( $instance['attachment_id'] ) > 0 ) {
			$output .= wp_get_attachment_image( $instance['attachment_id'], $size, false, $attr );
		}

		if ( $include_link && ! empty( $instance['link'] ) ) {
			$output .= '</a>';
		}

		return $output;
	}

	/**
	 * Get all possible image sizes to choose from
	 *
	 * @return array
	 */
	private function possible_image_sizes() {
		$registered = get_intermediate_image_sizes();
		// label other sizes with their image size "ID"
		$registered = array_combine( $registered, $registered );

		$possible_sizes = array_merge( $registered, array(
			'full'                       => __( 'Full Size', 'image-widget' ),
			'thumbnail'                  => __( 'Thumbnail', 'image-widget' ),
			'medium'                     => __( 'Medium', 'image-widget' ),
			'large'                      => __( 'Large', 'image-widget' ),
			self::CUSTOM_IMAGE_SIZE_SLUG => __( 'Custom', 'image-widget' ),
		) );

		/**
		 * Allow filtering the image sizes available for use in the Image Widget dropdown
		 *
		 * @param array $possible_sizes The array of available sizes.
		 */
		return (array) apply_filters( 'image_size_names_choose', $possible_sizes );
	}

	/**
	 * Assesses the image size in case it has not been set or in case there is a mismatch.
	 *
	 * @param $instance
	 * @return array|string
	 */
	private function get_image_size( $instance ) {
		if ( ! empty( $instance['size'] ) && $instance['size'] != self::CUSTOM_IMAGE_SIZE_SLUG ) {
			$size = $instance['size'];
		} elseif ( isset( $instance['width'] ) && is_numeric( $instance['width'] ) && isset( $instance['height'] ) && is_numeric( $instance['height'] ) ) {
			//$size = array(abs($instance['width']),abs($instance['height']));
			$size = array( $instance['width'], $instance['height'] );
		} else {
			$size = 'full';
		}

		return $size;
	}

	/**
	 * Establish the aspect ratio of the image.
	 *
	 * @param $instance
	 * @return float|number
	 */
	private function get_image_aspect_ratio( $instance ) {
		if ( ! empty( $instance['aspect_ratio'] ) ) {
			return abs( $instance['aspect_ratio'] );
		} else {
			$attachment_id = ( ! empty( $instance['attachment_id'] ) ) ? $instance['attachment_id'] : $instance['image'];
			if ( ! empty( $attachment_id ) ) {
				$image_details = wp_get_attachment_image_src( $attachment_id, 'full' );
				if ( $image_details ) {
					return ( $image_details[1] / $image_details[2] );
				}
			}
		}
	}

	/**
	 * Loads theme files in appropriate hierarchy: 1) child theme,
	 * 2) parent template, 3) plugin resources. will look in the image-widget/
	 * directory in a theme and the views/ directory in the plugin
	 *
	 * @param string $template template file to search for
	 * @return template path
	 */

	public function getTemplateHierarchy( $template ) {
		// whether or not .php was added
		$template_slug = rtrim( $template, '.php' );
		$template      = $template_slug . '.php';

		if ( $theme_file = locate_template( array( 'image-widget/' . $template ) ) ) {
			$file = $theme_file;
		} else {
			$file = 'views/' . $template;
		}

		/**
		 * Allow filtering of currently-retrieved Image Widget template file's path.
		 *
		 * @param string $file The retrieved template file's path.
		 */
		return apply_filters( 'sp_template_image-widget_' . $template, $file );
	}
}
