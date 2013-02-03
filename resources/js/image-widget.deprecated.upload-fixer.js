jQuery(document).ready(function() {

    jQuery('form#image-form').submit(function(){
        var wp_ref = jQuery("input[name='_wp_http_referer']").val();
        // _wp_http_referer only contains the widget_sp_image if the
        // previous action was pressing the add image link in an Image Widget
        // https://developer.mozilla.org/en/Core_JavaScript_1.5_Reference/Objects/String/indexOf
        if( wp_ref.indexOf('widget_sp_image') != -1 ) {
            var parsed_url = parse_url(wp_ref);
            var nw_action_url = jQuery('form#image-form').attr('action');

            // make sure the widget_sp_image is not part of the form action url
            // so we will add it to fix the context
            if( nw_action_url.indexOf('widget_sp_image') == -1 ) {
                nw_action_url = nw_action_url + '&' + parsed_url.query;
                jQuery('form#image-form').attr('action', nw_action_url);
            }
        }
        return true;
    });

	//also update the filter form with a new hidden field
	//is the filter form present on the page?
	if (jQuery("form#filter").length>0) {

		//code for retrieving GET vars (we want the value of widget_id)
		var widget_id = '';
		document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
			function decode(s) {
				return decodeURIComponent(s.split("+").join(" "));
			}

			var key = decode(arguments[1]);
			if (key == 'widget_id') {
				widget_id = decode(arguments[2]);
			}
		});

		if (widget_id.length > 0) {//do we have a value?

			//insert hidden field into form
			jQuery('form#filter').append(
				jQuery('<input/>')
				.attr('type', 'hidden')
				.attr('name', 'widget_id')
				.val(widget_id)
			);
		}
	}
});


/*
 * Thanks to http://github.com/kvz/phpjs/raw/master/functions/url/parse_url.js
 */
function parse_url (str, component) {
    // http://kevin.vanzonneveld.net
    // +      original by: Steven Levithan (http://blog.stevenlevithan.com)
    // + reimplemented by: Brett Zamir (http://brett-zamir.me)
    // + input by: Lorenzo Pisani
    // + input by: Tony
    // + improved by: Brett Zamir (http://brett-zamir.me)
    // %          note: Based on http://stevenlevithan.com/demo/parseuri/js/assets/parseuri.js
    // %          note: blog post at http://blog.stevenlevithan.com/archives/parseuri
    // %          note: demo at http://stevenlevithan.com/demo/parseuri/js/assets/parseuri.js
    // %          note: Does not replace invalid characters with '_' as in PHP, nor does it return false with
    // %          note: a seriously malformed URL.
    // %          note: Besides function name, is essentially the same as parseUri as well as our allowing
    // %          note: an extra slash after the scheme/protocol (to allow file:/// as in PHP)
    // *     example 1: parse_url('http://username:password@hostname/path?arg=value#anchor');
    // *     returns 1: {scheme: 'http', host: 'hostname', user: 'username', pass: 'password', path: '/path', query: 'arg=value', fragment: 'anchor'}
    var key = ['source', 'scheme', 'authority', 'userInfo', 'user', 'pass', 'host', 'port',
            'relative', 'path', 'directory', 'file', 'query', 'fragment'],
        ini = (this.php_js && this.php_js.ini) || {},
        mode = (ini['phpjs.parse_url.mode'] &&
            ini['phpjs.parse_url.mode'].local_value) || 'php',
        parser = {
            php: /^(?:([^:\/?#]+):)?(?:\/\/()(?:(?:()(?:([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?))?()(?:(()(?:(?:[^?#\/]*\/)*)()(?:[^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,
            strict: /^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,
            loose: /^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/\/?)?((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/ // Added one optional slash to post-scheme to catch file:/// (should restrict this)
        };

    var m = parser[mode].exec(str),
        uri = {},
        i = 14;
    while (i--) {
        if (m[i]) {
            uri[key[i]] = m[i];
        }
    }

    if (component) {
        return uri[component.replace('PHP_URL_', '').toLowerCase()];
    }
    if (mode !== 'php') {
        var name = (ini['phpjs.parse_url.queryKey'] &&
            ini['phpjs.parse_url.queryKey'].local_value) || 'queryKey';
        parser = /(?:^|&)([^&=]*)=?([^&]*)/g;
        uri[name] = {};
        uri[key[12]].replace(parser, function ($0, $1, $2) {
            if ($1) {uri[name][$1] = $2;}
        });
    }
    delete uri.source;
    return uri;
}

/* /wp-admin/media-upload.php?type=image&widget_id=widget_sp_image-11& */