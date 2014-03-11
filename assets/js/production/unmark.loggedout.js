/*! DEVELOPMENT VERSION */ 
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

/*!
    Password Reset Scripts for Unmark.it
    Copyright 2014 - Plain - http://plainmade.com
*/

(function ($) {

    $(document).ready(function () {

        var unmarkreset = {};
            unmarkreset.helper      = $('.gethere');
            unmarkreset.helptrigger = $('.forgot-pass');
            unmarkreset.firstpass   = $('#password');
            unmarkreset.secondpass  = $('#password2');
            unmarkreset.submitbtn   = $('.login-submit');
            unmarkreset.resetform   = $('#unmarkReset');
            unmarkreset.message     = $('.response-message');

        // Show Error Message & Spinner
        function showMessage(error, message) {
            var eclass   = (error) ? 'error' : '';
            unmarkreset.message.removeClass('error').addClass(eclass).text(message).fadeIn();
        }

        // Show or Hide the spinner
        function showSpinner(show) {
            if (show) {
                unmarkreset.submitbtn.find('i').removeClass('icon-go').addClass('icon-spinner');
            } else {
                unmarkreset.submitbtn.find('i').removeClass('icon-spinner').addClass('icon-go');
            }
        }

        // Clean up the form after an error
        function cleanupForm() {
            showSpinner(false);
            unmarkreset.firstpass.val('');
            unmarkreset.secondpass.val('');
        }

        // Resets the password
        function resetPassword(query) {
            unmark.ajax('/tools/resetPassword', 'post', query, function (res) {
                cleanupForm();
                if (res.success) {
                    showMessage(false, 'Your password has been changed. Redirecting now...')
                    setTimeout(function(){ window.location.href = "/" }, 3000);
                } else {
                    if (typeof res.errors[91] !== 'undefined') {
                        return showMessage(true, 'Invalid Token, Please check your email or contact support.');
                    }
                    showMessage(true, 'Password must contain both lower and uppercase letters and at least one number.');
                }
            });
        }

        // Show/Hide Help
        unmarkreset.helptrigger.on('click', function (e) {
            e.preventDefault();
            unmarkreset.helper.fadeToggle();
        });

        // Show second password field
        unmarkreset.firstpass.on('keypress change', function () {
            unmarkreset.message.fadeOut();
        });

        // Handle Password Form Update
        unmarkreset.resetform.on('submit', function (e) {
            e.preventDefault();

            var query,
                pass = unmarkreset.firstpass.val(),
                pass2 = unmarkreset.secondpass.val(),
                token = unmark.vars.token;

            showSpinner(true); // Show spinner

            if (pass === pass2) {
                query = 'token='+token+'&password='+pass;
                resetPassword(query);
            } else {
                cleanupForm();
                showMessage(true, 'Passwords do not match. Try again.');
            }

        });


    });

}(window.jQuery));

/*!
    Login Scripts for Unmark.it
    Copyright 2014 - Plain - http://plainmade.com
*/

(function ($) {

    $(document).ready(function () {

        // Set up Variables
        var unmarklogin = {};

        unmarklogin.message = $('.response-message');
        unmarklogin.login_wrapper = $('.loginWrapper');
        unmarklogin.login_spinner = $('.unmark-spinner');
        unmarklogin.login_success = $('.unmark-success');
        unmarklogin.login_form    = $('#unmarkLogin');
        unmarklogin.pass_form     = $('#unmarkForgotPass');
        unmarklogin.forget_submit = $('.forgot-submit');
        unmarklogin.input_fields  = $('input.field-input');
        unmarklogin.helper_buttom = $('.forgot-pass');
        unmarklogin.pass_wrapper  = $('.forgotPassWrapper');
        unmarklogin.login_page    = $('#unmark_login_page');
        unmarklogin.about_page    = $('#unmark_about_page');

        // Toggle the Login Form
        function toggle_login_form(hide, message) {
            if (hide === true) {
                unmarklogin.login_wrapper.animate({ top: '-500px' }, 500, function () {
                    $(this).hide();
                    unmarklogin.login_spinner.fadeIn();
                });
            } else {
                unmarklogin.login_spinner.fadeOut(400, function () {
                    $(this).hide();
                    unmarklogin.login_wrapper.show().animate({ top: '0' }, 500);
                    if (message){
                        showMessage(true, message);
                    }
                });
            }
        }

        // Successfull Login - Show Checkmark and redirect to login
        function login_success(url) {
            unmarklogin.login_spinner.fadeOut(400, function () {
                unmarklogin.login_success.fadeIn(400, function () {
                    setTimeout(function(){ window.location.href = url }, 800);
                });
            });
        }

        // Process the login
        // Decide what do do on sucess or failure
        function process_login(query) {
            unmark.ajax('/login', 'post', query, function (res) {
                if (res.success === true) {
                    login_success(res.redirect_url); // Run the redirection
                } else {
                    toggle_login_form(false, res.message);
                }
            });
        }

        // Change the icon for submit to a spinner
        function showMessage(error, message) {

            var form     = (unmarklogin.pass_wrapper.is(':visible')) ? unmarklogin.pass_wrapper : unmarklogin.login_wrapper,
                eclass   = (error) ? 'error' : '';
                response = form.find('.response-message');

            form.find('#password').val(''); // Empty Password Field
            response.removeClass('error').addClass(eclass).text(message).fadeIn(); // Update Class & Show Message
        }

        // Toggle the forgot password screen
        function toggleForgotPass() {
            if (unmarklogin.pass_wrapper.is(':visible')) {
                unmarklogin.pass_wrapper.animate({ top: '-500px' }, 500, function () {
                    $(this).hide();
                    toggle_login_form();
                });
            } else {
                unmarklogin.login_wrapper.animate({ top: '-500px' }, 500, function () {
                    $(this).hide();
                    unmarklogin.pass_wrapper.show().animate({ top: '0' }, 500);
                });
            }
        }

        // Login Submit Action
        unmarklogin.login_form.on('submit', function (e) {
            e.preventDefault(); // prevent page submit
            toggle_login_form(true); // Hide the Login Form

            // Set and get variables & proces login
            var email = $('#email').val(),
                pass  = $('#password').val(),
                query = 'email='+unmark.urlEncode(email)+'&password='+unmark.urlEncode(pass);
            setTimeout(function(){ process_login(query) }, 1500);
        });

        // Forgot Password Submit
        unmarklogin.pass_form.on('submit', function (e) {
            e.preventDefault();
            unmarklogin.forget_submit.find('i').removeClass('icon-go').addClass('icon-spinner');
            var email = $('#forgot_email').val(),
                query = 'email='+unmark.urlEncode(email);
            unmark.ajax('/tools/forgotPassword', 'post', query, function (res) {
                if (res.success) {
                    showMessage(false, 'A confirmation link will be sent via email.');
                } else {
                    showMessage(true, 'Email not recognized');
                }
                unmarklogin.forget_submit.find('i').removeClass('icon-spinner').addClass('icon-go');
            });
        });

        // Shows the Welcome Screen
        function toggle_welcome() {
            var aboutbtn = $('.login-page-bottom');
            if (aboutbtn.is(':visible')) {
                aboutbtn.fadeOut();
                unmarklogin.about_page.fadeOut().delay().fadeIn(800);
                unmarklogin.login_page.animate({ top: '-130%' }, 1000);
            } else {
                unmarklogin.about_page.fadeOut();
                unmarklogin.login_page.animate({ top: '0' }, 1000, function () {
                    aboutbtn.fadeIn(800);
                });
            }
        }

        // Show Submit Button on Key Press
        unmarklogin.input_fields.on('change', function () {
            unmarklogin.message.slideUp();
        });

        // Show Submit Button on key press on the forgot form
        unmarklogin.pass_form.find('input.field-input').on('keypress change', function (e) {
            unmarklogin.forget_submit.fadeIn();
            unmarklogin.message.slideUp();
        });

        // Toggle Forgot Password
        unmarklogin.helper_buttom.on('click', function (e) {
            e.preventDefault();
            toggleForgotPass();
        });

        $('.toggle_welcome').on('click', function (e) {
            e.preventDefault();
            toggle_welcome();
        });


    });

}(window.jQuery));

/*!
    Register/Signup Scripts for Unmark.it
    Copyright 2014 - Plain - http://plainmade.com
*/

(function ($) {

    // Show Error Message
    function showMessage(form, error, message) {
        var eclass   = (error) ? 'error' : '';
        form.parent().find('.response-message').removeClass('error').addClass(eclass).text(message).fadeIn();
    }

    // Show or Hide Spinner
    function showSpinner(form, show) {
        var button = form.find('.login-submit i');
        if (show) {
            button.removeClass('icon-go').addClass('icon-spinner');
        } else {
            button.removeClass('icon-spinner').addClass('icon-go');
        }
    }

    unmark.register_user = function (form) {
        var query,
            email = form.find('#email').val(),
            pass = form.find('#password').val(),
            pass2 = form.find('#password2').val();

        if (pass !== pass2) {
            return showMessage(form, true, 'The passwords must match.');
        }

        showSpinner(form, true);

        query = 'email='+unmark.urlEncode(email)+'&password='+unmark.urlEncode(pass);

        unmark.ajax('/register/user', 'post', query, function (res) {

            if (res.success) {
                showMessage(form, false, 'You are now registered, logging you in...');
                setTimeout(function(){ window.location.href = "/" }, 2500);
            } else {
                showSpinner(form, false);
                showMessage(form, true, res.message);
            }

        });

    };

    $(document).ready(function () {

        $('#register_user').on('submit', function (e) {
            e.preventDefault();
            unmark.register_user($(this));
        });

    });



}(window.jQuery));
