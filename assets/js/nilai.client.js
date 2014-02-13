/*!
    User Scripts for Nilai.co
    Copyright 2014 - Plain - http://plainmade.com

    A set of functions used push and pull user info.

*/

(function ($) { 

    // Show Error Message & Spinner
    function showMessage(form, error, message) {
        var eclass   = (error) ? 'error' : '';
        form.parent().find('.response-message').removeClass('error').addClass(eclass).text(message).fadeIn();
    }

    // Show or Hide Spinner
    function showSpinner(form, show) {
        var button = form.find('.login-submit i');
        if (show) {
            button.removeClass('barley-icon-chevron-right').addClass('barley-icon-spinner');
        } else {
            button.removeClass('barley-icon-spinner').addClass('barley-icon-chevron-right');
        }
    }

    // Logout Method
    nilai.logout = function () {
        window.location = "/logout";
    };


    // Change Password Function
    nilai.change_password = function () {
        nilai.overlay(true);
        $('#resetPasswordForm').show().animate({ top: 0 }, 1000);
    };
    nilai.change_email = function () {
        nilai.overlay(true);
        $('#changePasswordForm').show().animate({ top: 0 }, 1000);
    };

    // Submit Password Change
    nilai.send_password_change = function (form) {
        var query,
            new_pass_field = $('#pass1, #pass2'),
            old_pass_field = $('#oldpass'),
            oldpass = $('#oldpass').val(),
            pass1   = $('#pass1').val(),
            pass2   = $('#pass2').val();

        showSpinner(form, true);

        if (pass1 === pass2) {
            query = 'password='+pass1+'&current_password='+oldpass;
            nilai.ajax('/user/update/password', 'post', query, function (res) {
                if (res.success) {
                    showMessage(form, false, 'Your password has been changed.');
                } else {
                    showMessage(form, true, res.message);
                }
                showSpinner(form, false);
                new_pass_field.val('');
                old_pass_field.val('');
            });
        } else { 
            new_pass_field.val('');
            showSpinner(form, false);
            return showMessage(form, true, 'New Passwords do not match'); 
        }
    };

    // Submit Email Change
    nilai.send_email_change = function (form) {
        var query, 
            email_field = $('#emailupdate'),
            email_value = email_field.val();

        showSpinner(form, true);

        if (email_value !== '') {
            query = 'email='+email_value;
            nilai.ajax('/user/update/email', 'post', query, function (res) {
                if (res.success) {
                    showMessage(form, false, 'Your email has been changed.');
                    $('#user-email').empty().text('[ '+email_value+' ]');
                } else {
                    showMessage(form, true, res.message);
                }
                showSpinner(form, false);
                email_field.val('');
            });
        } else { 
            email_field.val('');
            showSpinner(form, false);
            return showMessage(form, true, 'Please enter something!'); 
        }
    };




}(window.jQuery));