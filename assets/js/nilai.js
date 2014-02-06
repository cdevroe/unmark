/*!
    Main scripts for Nilai.co
    Copyright 2014 - Plain - http://plainmade.com

    A set of helper functions that can be called and used throughout the app

*/

if (nilai === undefined) { var nilai = {}; }

(function ($) {

    // Basic Ajax Function used throughout the app
    nilai.ajax = function (path, method, query, callback, data_type, async) {
        var csrf_token   = nilai.urlEncode(nilai.vars.csrf_token),
            data_type    = (data_type !== undefined) ? data_type : 'json',
            async        = (async !== undefined) ? async : true,
            added_vars   = 'csrf_token=' + csrf_token + '&content_type=' + data_type;
            query        = (nilai.empty(query)) ? added_vars : query + '&' + added_vars;

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

    // Simple Swap Class Method that uses regex
    nilai.swapClass = function (elem, removals, additions) {
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
        self.each( function ( i, it ) {
            var cn = ' ' + it.className + ' ';
            while ( patt.test( cn ) ) {
                cn = cn.replace( patt, ' ' );
            }
            it.className = $.trim( cn );
        });
     
        // Return new swap
        return !additions ? self : self.addClass( additions );
    };

    // Replace special chars
    nilai.replaceSpecial = function(str) {
        if (str !== undefined && str !== null) {
            var regex = null;
            for (var i in nilai.special_chars) {
                regex = new RegExp(i, 'gi');
                str   = str.replace(regex, nilai.special_chars[i]);
            }
        }
        return str;
    };

    // Encode for URL
    nilai.urlEncode = function(str) {
        str = nilai.replaceSpecial(str);
        return encodeURIComponent(str);
    };

    // Nice Check Empty Function
    nilai.empty = function(v) {
        var l = (v !== undefined && v !== null) ? v.length : 0;
        return (v === false || v === '' || v === null || v === 0 || v === undefined || l < 1);
    };

    // Default Pushstate using PJAX
    $(document).ready(function () {
        
        var container = $('.main-content');

        $(document).pjax("a[href*='/']", container);
        $(document).on('pjax:complete', function() {
            nilai.updateDom(container.text());
        });

    });

}(window.jQuery));