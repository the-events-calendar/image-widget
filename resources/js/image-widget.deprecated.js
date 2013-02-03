var imageWidget;

(function($){

	imageWidget = {

		sendToEditor : function(h) {
			// ignore content returned from media uploader and use variables passed to window instead

			// store attachment id in hidden field
			$( '#widget-'+self.IW_instance+'-image' ).val( self.IW_img_id );

			// display attachment preview
			$( '#display-widget-'+self.IW_instance+'-image' ).html( self.IW_html );

			// change width & height fields in widget to match image
			$( '#widget-'+self.IW_instance+'-width' ).val($( '#display-widget-'+self.IW_instance+'-image img').attr('width'));
			$( '#widget-'+self.IW_instance+'-height' ).val($( '#display-widget-'+self.IW_instance+'-image img').attr('height'));

			// set alignment in widget
			$( '#widget-'+self.IW_instance+'-align' ).val(self.IW_align);

			// set title in widget
			$( '#widget-'+self.IW_instance+'-title' ).val(self.IW_title);

			// set caption in widget
			$( '#widget-'+self.IW_instance+'-description' ).val(self.IW_caption);

			// set alt text in widget
			$( '#widget-'+self.IW_instance+'-alt' ).val(self.IW_alt);

			// set link in widget
			$( '#widget-'+self.IW_instance+'-link' ).val(self.IW_url);

			// close thickbox
			tb_remove();

			// change button text
			$('#add_image-widget-'+self.IW_instance+'-image').html($('#add_image-widget-'+self.IW_instance+'-image').html().replace(/Add Image/g, 'Change Image'));
		},

		changeImgWidth : function(instance) {
			var width = $( '#widget-'+instance+'-width' ).val();
			var height = Math.round(width / imageWidget.imgRatio(instance));
			imageWidget.changeImgSize(instance,width,height);
		},

		changeImgHeight : function(instance) {
			var height = $( '#widget-'+instance+'-height' ).val();
			var width = Math.round(height * imageWidget.imgRatio(instance));
			imageWidget.changeImgSize(instance,width,height);
		},

		imgRatio : function(instance) {
			var width_old = $( '#display-widget-'+instance+'-image img').attr('width');
			var height_old = $( '#display-widget-'+instance+'-image img').attr('height');
			var ratio =  width_old / height_old;
			return ratio;
		},

		changeImgSize : function(instance,width,height) {
			if (isNaN(width) || width < 1) {
				$( '#widget-'+instance+'-width' ).val('');
				width = 'none';
			} else {
				$( '#widget-'+instance+'-width' ).val(width);
				width = width + 'px';
			}
			$( '#display-widget-'+instance+'-image img' ).css({
				'width':width
			});

			if (isNaN(height) || height < 1) {
				$( '#widget-'+instance+'-height' ).val('');
				height = 'none';
			} else {
				$( '#widget-'+instance+'-height' ).val(height);
				height = height + 'px';
			}
			$( '#display-widget-'+instance+'-image img' ).css({
				'height':height
			});
		},

		changeImgAlign : function(instance) {
			var align = $( '#widget-'+instance+'-align' ).val();
			$( '#display-widget-'+instance+'-image img' ).attr(
				'class', (align == 'none' ? '' : 'align'+align)
			);
		},

		imgHandler : function(event) {
			event.preventDefault();
			window.send_to_editor = imageWidget.sendToEditor;
			tb_show("Add an Image", event.target.href, false);
		},

		setActiveWidget : function(instance_id) {
			self.IW_instance = instance_id;
		}

	};

	$(document).ready(function() {
		// Use new style event handling since $.fn.live() will be deprecated
		if ( typeof $.fn.on !== 'undefined' ) {
			$("#wpbody").on("click", ".thickbox-image-widget", imageWidget.imgHandler);
		}
		else {
			$("a.thickbox-image-widget").live('click', imageWidget.imgHandler);
		}

		// Modify thickbox link to fit window. Adapted from wp-admin\js\media-upload.dev.js.
		$('a.thickbox-image-widget').each( function() {
			var href = $(this).attr('href'), width = $(window).width(), H = $(window).height(), W = ( 720 < width ) ? 720 : width;
			if ( ! href ) return;
			href = href.replace(/&width=[0-9]+/g, '');
			href = href.replace(/&height=[0-9]+/g, '');
			$(this).attr( 'href', href + '&width=' + ( W - 80 ) + '&height=' + ( H - 85 ) );
		});
	});

})(jQuery);