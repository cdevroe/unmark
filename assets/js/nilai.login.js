/*!
    Login Scripts for Nilai.co
    Copyright 2014 - Plain - http://plainmade.com
*/

(function ($) {

    $(document).ready(function () {

        // Set up Variables
        var nilailogin = {};

        nilailogin.message = $('.response-message');
        nilailogin.login_wrapper = $('.loginWrapper');
        nilailogin.login_spinner = $('.nilai-spinner');
        nilailogin.login_success = $('.nilai-success');
        nilailogin.login_form    = $('#nilaiLogin');
        nilailogin.pass_form     = $('#nilaiForgotPass');
        nilailogin.login_submit  = $('.login-submit');
        nilailogin.forget_submit = $('.forgot-submit');
        nilailogin.input_fields  = $('input.field-input');
        nilailogin.helper_buttom = $('.forgot-pass');
        nilailogin.pass_wrapper  = $('.forgotPassWrapper');

        // Toggle the Login Form
        function toggle_login_form(hide, message) {
            if (hide === true) {
                nilailogin.login_wrapper.animate({ top: '-400px' }, 500, function () {
                    nilailogin.login_spinner.fadeIn();
                });
            } else {
                nilailogin.login_spinner.fadeOut(400, function () {
                    nilailogin.login_wrapper.animate({ top: '0' }, 500);
                    if (message){
                        showMessage(true, message);
                    }
                });
            }
        }

        // Successfull Login - Show Checkmark and redirect to login
        function login_success() {
            nilailogin.login_spinner.fadeOut(400, function () {
                nilailogin.login_success.fadeIn(400, function () {
                    setTimeout(function(){ window.location.href = "/marks" }, 800);
                });
            });   
        }

        // Process the login
        // Decide what do do on sucess or failure
        function process_login(query) {
            nilai.ajax('/login', 'post', query, function (res) {
                if (typeof res.lookup_type != 'undefined') {
                    login_success(); // Run the redirection
                } else {
                    toggle_login_form(false, 'Invalid Email or Password');
                }
            });           
        }

        // Change the icon for submit to a spinner
        function showMessage(error, message) {

            var form     = (nilailogin.pass_wrapper.is(':visible')) ? nilailogin.pass_wrapper : nilailogin.login_wrapper,
                eclass   = (error) ? 'error' : '';
                response = form.find('.response-message');

            form.find('button').hide(); // Hide Submit Button
            form.find('.field-input').val(''); // Empty Fields
            response.removeClass('error').addClass(eclass).text(message).fadeIn(); // Update Class & Show Message
        }
        
        // Toggle the forgot password screen
        function toggleForgotPass() {
            if (nilailogin.pass_wrapper.is(':visible')) {
                nilailogin.pass_wrapper.animate({ top: '-400px' }, 500, function () {
                    $(this).hide();
                    toggle_login_form();
                });
            } else {
                nilailogin.login_wrapper.animate({ top: '-400px' }, 500, function () {
                    nilailogin.pass_wrapper.show().animate({ top: '0' }, 500);
                });
            }
        }

        // Login Submit Action
        nilailogin.login_form.on('submit', function (e) {
            e.preventDefault(); // prevent page submit
            toggle_login_form(true); // Hide the Login Form

            // Set and get variables & proces login
            var email = $('#email').val(),
                pass  = $('#password').val(),
                query = 'email='+email+'&password='+pass;
            setTimeout(function(){ process_login(query) }, 1500);
        });

        // Forgot Password Submit
        nilailogin.pass_form.on('submit', function (e) {
            e.preventDefault();
            nilailogin.forget_submit.find('i').removeClass('icon-go').addClass('icon-spinner');
            var email = $('#forgot_email').val(),
                query = 'email='+email;
            nilai.ajax('/tools/forgotPassword', 'post', query, function (res) {
                if (res.success) {
                    showMessage(false, 'A confirmation link will be sent via email.');
                } else {
                    showMessage(true, 'Email not recogonized');
                }
                nilailogin.forget_submit.find('i').removeClass('icon-spinner').addClass('icon-go');
            });
        });

        // Show Submit Button on Key Press
        nilailogin.input_fields.on('change', function () { 
            nilailogin.login_submit.fadeIn();
            nilailogin.message.slideUp();
        });

        // Show Submit Button on key press on the forgot form
        nilailogin.pass_form.find('input.field-input').on('keypress change', function (e) {
            nilailogin.forget_submit.fadeIn();
            nilailogin.message.slideUp();
        });

        // Toggle Forgot Password
        nilailogin.helper_buttom.on('click', function (e) {
            e.preventDefault();
            toggleForgotPass();
        });

    });

}(window.jQuery));