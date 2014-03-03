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
        unmarklogin.login_submit  = $('.login-submit');
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
                    unmarklogin.login_spinner.fadeIn();
                });
            } else {
                unmarklogin.login_spinner.fadeOut(400, function () {
                    unmarklogin.login_wrapper.animate({ top: '0' }, 500);
                    if (message){
                        showMessage(true, message);
                    }
                });
            }
        }

        // Successfull Login - Show Checkmark and redirect to login
        function login_success() {
            unmarklogin.login_spinner.fadeOut(400, function () {
                unmarklogin.login_success.fadeIn(400, function () {
                    setTimeout(function(){ window.location.href = "/marks" }, 800);
                });
            });
        }

        // Process the login
        // Decide what do do on sucess or failure
        function process_login(query) {
            unmark.ajax('/login', 'post', query, function (res) {
                if (typeof res.lookup_type != 'undefined') {
                    login_success(); // Run the redirection
                } else {
                    toggle_login_form(false, 'Invalid Email or Password');
                }
            });
        }

        // Change the icon for submit to a spinner
        function showMessage(error, message) {

            var form     = (unmarklogin.pass_wrapper.is(':visible')) ? unmarklogin.pass_wrapper : unmarklogin.login_wrapper,
                eclass   = (error) ? 'error' : '';
                response = form.find('.response-message');

            form.find('button').hide(); // Hide Submit Button
            form.find('.field-input').val(''); // Empty Fields
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
                query = 'email='+email+'&password='+pass;
            setTimeout(function(){ process_login(query) }, 1500);
        });

        // Forgot Password Submit
        unmarklogin.pass_form.on('submit', function (e) {
            e.preventDefault();
            unmarklogin.forget_submit.find('i').removeClass('icon-go').addClass('icon-spinner');
            var email = $('#forgot_email').val(),
                query = 'email='+email;
            unmark.ajax('/tools/forgotPassword', 'post', query, function (res) {
                if (res.success) {
                    showMessage(false, 'A confirmation link will be sent via email.');
                } else {
                    showMessage(true, 'Email not recogonized');
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
            unmarklogin.login_submit.fadeIn();
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
