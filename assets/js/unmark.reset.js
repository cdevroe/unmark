/*!
    Password Reset
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
                    showMessage(false, 'Your password has been changed. Redirecting now...');
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
