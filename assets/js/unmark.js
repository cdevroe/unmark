/*!
    Main scripts for Unmark.it
    Copyright 2014 - Plain - http://plainmade.com

    A set of helper functions that can be called and used throughout the app

*/

if (unmark === undefined) { var unmark = {}; }

(function ($) {

    // Basic Ajax Function used throughout the app
    unmark.ajax = function (path, method, query, callback, data_type, async) {
        var csrf_token   = unmark.urlEncode(unmark.vars.csrf_token),
            data_type    = (data_type !== undefined) ? data_type : 'json',
            async        = (async !== undefined) ? async : true,
            added_vars   = 'csrf_token=' + csrf_token + '&content_type=' + data_type;
            query        = (unmark.empty(query)) ? added_vars : query + '&' + added_vars;

        $.ajax({
            'dataType': data_type,
            'cache': false,
            'url': path,
            'type': method.toUpperCase(),
            'data': query,
            'async': async,
            'success': function (res) {
                if ($.isFunction(callback)) {
                    callback(res);
                }
            },
            'error': function(xhr, status, error) {
                var json = {
                    'error': error,
                    'status': status,
                    'request': xhr
                };
                if ($.isFunction(callback)) {
                    callback(json);
                }
            }
        });

    };

    // Query String Helper
    unmark.readQuery = function (v) {
        var query = window.location.search.substring(1);
        var vars = query.split("&");
        for ( var i=0; i<vars.length; i++ ) {
            var pair = vars[i].split("=");
            if( pair[0] == v ){ return pair[1]; }
        }
        return(false);
    };

    // Simple Swap Class Method that uses regex
    unmark.swapClass = function (elem, removals, additions) {
        var self = elem;

        // Check for simple replacement
        if ( removals.indexOf( '*' ) === -1 ) {
            self.removeClass( removals );
            return !additions ? self : self.addClass( additions );
        }

        // If regex is passed in create pattern and search/replace
        var patt = new RegExp( '\\s' +
                removals.
                    replace( /\*/g, '[A-Za-z0-9-_]+' ).
                    split( ' ' ).
                    join( '\\s|\\s' ) +
                '\\s', 'g' );

        // Run the replace with regex pattern
        self.each( function (i, it) {
            var cn = ' ' + it.className + ' ';
            while ( patt.test(cn) ) {
                cn = cn.replace(patt, ' ');
            }
            it.className = $.trim(cn);
        });

        // Return new swap
        return !additions ? self : self.addClass(additions);
    };

    // Replace special chars
    unmark.replaceSpecial = function(str) {
        if (str !== undefined && str !== null) {
            var regex = null;
            for (var i in unmark.special_chars) {
                regex = new RegExp(i, 'gi');
                str   = str.replace(regex, unmark.special_chars[i]);
            }
        }
        return str;
    };

    // Encode for URL
    unmark.urlEncode = function(str) {
        str = unmark.replaceSpecial(str);
        return encodeURIComponent(str);
    };

    // Nice Check Empty Function
    unmark.empty = function(v) {
        var l = (v !== undefined && v !== null) ? v.length : 0;
        return (v === false || v === '' || v === null || v === 0 || v === undefined || l < 1);
    };

    // Function to Create/Update Cookies
    unmark.createCookie = function (name,value,days) {
        if (days) {
            var date = new Date();
            date.setTime(date.getTime()+(days*24*60*60*1000));
            var expires = "; expires="+date.toGMTString();
        }
        else var expires = "";
        document.cookie = name+"="+value+expires+"; path=/";
    };

    // Function to Read Cookie
    unmark.readCookie = function (name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for(var i=0;i < ca.length;i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1,c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
        }
        return null;
    };

    // Prefity Link
    unmark.prettyLink = function (link) {
        link = link.replace(/https?:\/\/(www.)?/, '');
        if(link.substr(-1) === '/') {
            link = link.substr(0, link.length - 1);
        }
        return link;
    };

    // Function to parse query string
    unmark.read_query_str = function (name) {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(location.search);
        return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    }

    /**
	 * Extends given function by calling the original function and then executing
	 * another piece of code after original invocation
	 * @returns New called function result (if not null) or original function result otherwise
	 */
	unmark.extendFunction = function (functionName, newFunction) {
		this[functionName] = (function(_obj, _super, _new) {
			return function() {
				var _origResult = _super.apply(_obj, arguments);
				var _newResult = _new.apply(_obj, arguments);
				return _newResult !== null ? _newResult : _origResult;
			};
		})(this, this[functionName], newFunction);
	};


}(window.jQuery));
