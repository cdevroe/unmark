/*!
    Password Reset Scripts for Nilai.co
    Copyright 2014 - Plain - http://plainmade.com
*/

(function ($) {

    $(document).ready(function () {

        var nilaireset = {};
            nilaireset.helper      = $('.gethere');
            nilaireset.helptrigger = $('.forgot-pass');
            nilaireset.firstpass   = $('#password');
            nilaireset.secondpass  = $('#password2');
            nilaireset.submitbtn   = $('.login-submit');
            nilaireset.resetform   = $('#nilaiReset');
            nilaireset.message     = $('.response-message');

        // Show Error Message & Spinner
        function showMessage(error, message) {
            var eclass   = (error) ? 'error' : '';
            nilaireset.message.removeClass('error').addClass(eclass).text(message).fadeIn();
        }

        // Show or Hide the spinner
        function showSpinner(show) {
            if (show) {
                nilaireset.submitbtn.find('i').removeClass('icon-go').addClass('icon-spinner');
            } else {
                nilaireset.submitbtn.find('i').removeClass('icon-spinner').addClass('icon-go');
            }
        }

        // Clean up the form after an error
        function cleanupForm() {
            showSpinner(false);
            nilaireset.submitbtn.fadeOut();
            nilaireset.firstpass.val('');
            nilaireset.secondpass.val('').slideUp();
        }

        // Resets the password
        function resetPassword(query) {
            nilai.ajax('/tools/resetPassword', 'post', query, function (res) {
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
        nilaireset.helptrigger.on('click', function (e) {
            e.preventDefault();
            nilaireset.helper.fadeToggle();
        });

        // Show second password field
        nilaireset.firstpass.on('keypress change', function () {
            nilaireset.message.fadeOut();
            nilaireset.secondpass.fadeIn();
        });

        // Show Submit Button
        nilaireset.secondpass.on('keypress change', function () {
            nilaireset.submitbtn.fadeIn();
        });

        // Handle Password Form Update
        nilaireset.resetform.on('submit', function (e) {
            e.preventDefault();

            var query,
                pass = nilaireset.firstpass.val(),
                pass2 = nilaireset.secondpass.val(), 
                token = nilai.vars.token;

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