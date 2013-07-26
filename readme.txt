=== Image Widget ===
Contributors: ModernTribe, peterchester, mattwiebe
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=4BSPTNFFY6AL6
Tags: widget, image, ad, banner, simple, upload, sidebar, admin, thickbox, resize, arabic, brazilian portuguese, dutch, italian, japanese, spanish, swedish, widget-only
Requires at least: 3.5
Tested up to: 3.6
Stable tag: 4.0.8

== Description ==

A simple image widget that uses the native WordPress media manager to add image widgets to your site.

* MU Compatible
* Handles image resizing and alignment
* Link the image
* Title and Description
* Very versatile.  All fields are optional.
* Upload, link to external image, or select an image from your media collection using the built in thickbox browser.
* Supports override of template so that you can override the template for your theme!
* Responsive

Supported Languages:

* Arabic
* Brazilian Portuguese
* Dutch
* Italian
* Japanese
* Spanish
* Swedish

If you would like your own language to be supported, please contribute translations here:

http://wordpress.org/support/topic/image-widget-40-translations-needed-2

This plugin is actively supported and we will do our best to help you. In return we simply as 3 things:

1. Help Out. If you see a question on the forum you can help with or have a great idea and want to code it up and submit a patch, that would be just plain awesome and we will shower your with praise. Might even be a good way to get to know us and lead to some paid work if you freelance.  Also, we are happy to post translations if you provide them.
1. Donate - if this is generating enough revenue to support our time it makes all the difference in the world
https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=4BSPTNFFY6AL6
1. Support us by buying our Premium plugins. In particular, check out our Events Calendar Pro http://tri.be/wordpress-events-calendar-pro/?src=imagewidget

Note, we're also on github in case you want to add some pull requests or a fork!

https://github.com/moderntribe/image-widget

== Installation ==

= Install =

1. In your WordPress administration, go to the Plugins page
1. Activate the Image Widget plugin and a subpage for the plugin will appear
   in your Manage menu.
1. Go to the Appearance > Widget page and place the widget in your sidebar in the Design

If you find any bugs or have any ideas, please mail us.

Please visit the forum for questions or comments: http://wordpress.org/tags/image-widget/

= Requirements =

* PHP 5.1 or above
* WordPress 3.5 or above

== Documentation ==

The built in template can be overridden by files within your template.

= Default vs. Custom Templates =

The Image Widget comes with a default template for the widget output. If you would like to alter the widget display code, create a new folder called "image-widget" in your template directory and copy over the "views/widget.php" file.

Edit the new file to your hearts content. Please do not edit the one in the plugin folder as that will cause conflicts when you update the plugin to the latest release.

New in 3.2: You may now also use the "sp_template_image-widget_widget.php" filter to override the default template behavior for .php template files. Eg: if you wanted widget.php to reside in a folder called my-custom-templates/ and wanted it to be called my-custom-name.php:

`add_filter('sp_template_image-widget_widget.php', 'my_template_filter');
function my_template_filter($template) {
	return get_template_directory() . '/my-custom-templates/my-custom-name.php';
}`

= Filters =

There are a number of filters in the code that will allow you to override data as you see fit. The best way to learn what filters are available is always by simply searching the code for 'apply_filters'. But all the same, here are a few of the more essential filters:

*widget_title*

This is actually a pretty typical filter in widgets and is applied to the widget title.

*widget_text*

Another very typical widget filter that is applied to the description body text. This filter also takes 2 additional arguments for $args and $instance so that you can learn more about the specific widget instance in the process of filtering the content.

*image_widget_image_attachment_id*

Filters the attachment id of the image.
Accepts additional $args and $instance arguments.

*image_widget_image_url*

Filters the url of the image displayed in the widget.
Accepts additional $args and $instance arguments.
THIS IS DEPRECATED AND WILL EVENTUALLY BE DELETED

*image_widget_image_width*

Filters the display width of the image.
Accepts additional $args and $instance arguments.

*image_widget_image_height*

Filters the display height of the image.
Accepts additional $args and $instance arguments.

*image_widget_image_maxwidth*

Filters the inline max-width style of the image. Hint: override this to use this in responsive designs :)
Accepts additional $args and $instance arguments.
Return null to remove this css from the image output (defaults to '100%').

*image_widget_image_maxheight*

Filters the inline max-height style of the image.
Accepts additional $args and $instance arguments.
Return null to remove this css from the image output (defaults to null)

*image_widget_image_size*

Filters the selected image 'size' corresponding to WordPress registered sizes.
If this is set to 'tribe_image_widget_custom' then the width and height are used instead.
Accepts additional $args and $instance arguments.

*image_widget_image_align*

Filters the display alignment of the image.
Accepts additional $args and $instance arguments.

*image_widget_image_alt*

Filters the alt text of the image.
Accepts additional $args and $instance arguments.

*image_widget_image_link*

Filters the url that the image links to.
Accepts additional $args and $instance arguments.

*image_widget_image_link_target*

Filters the link target of the image link.
Accepts additional $args and $instance arguments.

*image_widget_image_attributes*

Filters a list of image attributes used in the image output. Similar to 'wp_get_attachment_image_attributes'
Accepts $instance arguments

*image_widget_link_attributes*

Filters a list of attributes used in the image link. Similar to 'wp_get_attachment_image_attributes'
Accepts $instance arguments

= Have You Supported the Image Widget? =

If so, then THANK YOU! Also, feel free to add this line to your wp-config.php file to prevent the image widget from displaying a message after upgrades.

define( 'I_HAVE_SUPPORTED_THE_IMAGE_WIDGET', true );

For more info on the philosophy here, check out our blog post: http://tri.be/define-i-have-donated-true/

== Changelog ==

= 4.0.8 =

* Responsive support in honor of Josh Broton's WordCamp SF talk about responsive design. max-width now defaults to 100%;

= 4.0.7 =

* Add Spanish translation (thank you @mzaweb)

= 4.0.6 =

* Rename all language files and implement a couple more minor language bug fixes a la @understandard
* Added support for the constant 'I_HAVE_SUPPORTED_THE_IMAGE_WIDGET' to turn off the message that appears after upgrading.  (@crienoloog, i hope this puts a smile on your face.)

= 4.0.5 =

* Added Japanese (and fixed a minor language string bug - thank you @understandard)
* Added Arabic (thank you @modmenpc)

= 4.0.4 =

Super minor fix to enable saving of a blank caption. (thanks @crdunst)

= 4.0.3 =

Fixed javascript bug caused by log message.

= 4.0.2 =

Fix oversized screenshot.

= 4.0.1 =

Language updates:

* Brazilian Portuguese (Thank you @guhemama)
* Spanish (Thank you @javiandgo)

= 4.0 =

* Significant upgrades to support the new WordPress media manager (Thank you @kyleunzicker, @dancameron, @dudekpj, @JakePT)
* Significant improvements the administrative user interface.
* Abstracted support for older versions of WordPress so that that we don't break old versions with this upgrade (Though there's no reason you should up grade this widget and NOT your WP install! You should always keep WordPress core up to date!)
* Added 'image_widget_link_attributes' filter to easily process link attributes and to default to having the link 'title' be the 'alt' or 'title' content. (Thank you @ZeroGravity, @pixelyzed, and @javiandgo)
* Updated Translations
** Swedish (Tomas Lindhoff <tomas@xhost.se>)
** Dutch (Presis <contact@presis.nl>)
** Italian (@maxgx)

= 3.3.8 =

* Added italian translations courtesy of @maxgx

= 3.3.7 =

* Add filters so that people can more easily adjust the output of the widget as per @TCBarrett's request.

= 3.3.6 =

* Czech translation courtesy of Vladislav Musilek at blogísek (http://blog.musilda.cz).

= 3.3.5 =

* Fix filtered media library inserts thanks to @miraclemaker as well as @oxyc, @BjornW and innumerable others in the support forum (http://wordpress.org/support/topic/plugin-image-widget-add-image-upload-an-image-select-insert-into-widget-no-image-is-shown)
* Adjusted HTTPS/SSL handling so that it's only applied in the view. ( thanks @TheFluffyDoneky and @aerobrent )
* Added a filter for the image url: 'image_widget_image_url'
* Add Dutch language translation ( thank you Carsten Alsemgeest - presis.nl )
* Rename all language files to lowercase image_widget to match the localization string.

= 3.3.4 =

* Fix javascript bugs in the widget admin UI. ( thanks for filing this @joo-joo )
* Fix notices in php error log.
* Add widget description filter $args and $instance ( thanks @jeffreyzinn )
* Fixed localization and renamed key to 'image-widget'

= 3.3.3 =

* Romanian translation courtesy of Alexander Ovsov at Web Geek Science (http://webhostinggeeks.com).

= 3.3.2 =

* Remove extra esc_attr() from the $title display. (Thank you @romaspit)

= 3.3.1 =

* Add minor security updates.
* Update readme, thumbnails and other minor descriptors.

= 3.3 =

* Fix to allow the widget to work in the non-async (browser) uploader. Props Bjorn Wijers

= 3.2.11 =

* Yet another minor JS fix to hopefully address issues of lightbox not working

= 3.2.10 =

* Fix JS typo.

= 3.2.9 =

* Minor JS fix to hopefully address issues of lightbox not working
* Use new the new [jQuery.fn.on](http://api.jquery.com/on/) method for forward compatibility.

= 3.2.8 =

* Minor bugfix courtesy of Takayuki Miyauchi (@miya0001)
* Polish translation courtesy of Łukasz Kliś

= 3.2.7 =

* Update javascript to work with the new version of WordPress (thanks Matt Wiebe!!! @mattwiebe)
* Added Japanese translation courtesy of Takayuki Miyauchi (@miya0001)

= 3.2.6 =

* Add HTTPS support courtesy of David Paul Ellenwood (DPE@SGS)

= 3.2.5 =

* Added Swedish translation courtesy of Tomas Lindhoff (@Tomas)

= 3.2.4 =

* Added javascript conflict prevention code thanks to @rcain.

= 3.2.3 =

* Added French translation courtesy of Dominique Corbex (@Domcox)

= 3.2.2 =

* Added Portuguese translation courtesy of Gustavo Machado

= 3.2.1 =

* Fix image widget public declaration bug.

= 3.2 =

* Abstract views for widget output and widget admin.
* Support theme override of the widget output!  Now you can layout the widget however you'd like.
* Added filter to override template call.

= 3.1.6 =

* Fixed Wordpress 3.0 bugs. (Thanks @kenvunz)

= 3.1.5 =

Fixed PHP 5 bug.  Removed 'public' declaration. http://wordpress.org/support/topic/362167  Thanks @mpwalsh8, @jleuze, @PoLaR5, @NancyA and @phoney36

= 3.1.4 =

* Added support for ALT tags.  If no alt tag is entered the title is used.

= 3.1.3 =

* Added German language support (Thank you Rüdiger Weiß!!!)

= 3.1.2 =

* Fix bug: XHTML Compliance (thanks HGU for offering a patch and thanks @webmasterlistingarts for filing the bug)
* Replaced `<p>` with `<div>` in description to also improve XHTML compliance.

= 3.1.1 =

* Fix bug: php4 reported error: PHP Parse error:  syntax error, unexpected T_STRING, expecting T_OLD_FUNCTION or T_FUNCTION or T_VAR or '}' (thanks @natashaelaine and @massimopaolini)

= 3.0.10 =

* Fix bug: improve tab filters.

= 3.0.9 =

* Fix bug: update tabs filter to not kill tabs if upload window is for non widget uses.

= 3.0.8 =

* Remove the "From URL" tab since it isn't supported.
* Replace "Insert into Post" with "Insert into Widget" in thickbox.

= 3.0.7 =

* Fix Dean's Fcuk editor conflict. (Thanks for the report Laurie @L_T_G)
* Fix IE8 bug (Remove extra comma from line 66 of js - thanks for the report @reface)
* Update functions and enqueued scripts to only trigger on widget page.

= 3.0.6 =

* Fix crash on insert into post.

= 3.0.5 =

Thank you @smurkas, @squigie and @laurie!!!  Special thanks to Cameron Clark from http://prolifique.com a.k.a @capnhairdo for contributing invaluable javascript debugging skills and throwing together some great code.

* PHP4 compatibility
* Tighter integration with the thickbok uploader attributes including caption, description, alignment, and link
* Tighter image resize preview
* Add Image link becomes "Change Image" once image has been added

= 3.0.4 =

* Minor description changes

= 3.0.3 =

* Fixed the broken "Add Image" link (THANK YOU @SMURKAS!!!)

= 3.0.2 =

* Added PHPDoc comments
* Temporarily fixed install bug where no image is saved if resize is not working. (thank you Paul Kaiser from Champaign, Il for your helpful QA support)

= 3.0.1 =

* Added 'sp_image_widget' domain for language support.

= 3.0 =

* Completely remodeled the plugin to use the native WordPress uploader and be compatible with Wordpress 2.8 plugin architecture.
* Removed externalized widget admin.

= 2.2.2 =

* Update <li> to be $before_widget and $after_widget (Thanks again to Lois Turley)

= 2.2.1 =

* Update `<div>` to be `<li>` (Thanks to Lois Turley)

= 2.2 =

* Fixed missing DIV close tag (Thank you Jesper Goos)
* Updated all short tags to proper php tags (Thank you Jonathan Volks from Mannix Marketing)

= 2.1 =

* Link Target

= 2.0 =

* Multi widget support
* WP 2.7.1 Compatibility
* Class encapsulation

== Upgrade Notice ==

= 4.0 =

Please be advised that this is a significant upgrade. You should definitely back up your database before updating in case your existing image widgets have any problems.

Also, several languages will no longer be supported until new translations are submitted. Currently, we have support for Italian, Dutch, and Swedish. If you would like to contribute your own language files, please post links here:

http://wordpress.org/support/topic/image-widget-40-translations-needed-2

== Screenshots ==

1. Image Widget admin screen.
1. Media manager integration.
1. Image Widget on the front of a plain Wordpress install.

== Frequently Asked Questions ==

= Where do I go to file a bug or ask a question? =

Please visit the forum for questions or comments: http://wordpress.org/tags/image-widget/