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

    // Submit Password Change
    nilai.send_password_change = function (form) {
        var query,
            newpass = $('#pass1, #pass2'),
            oldpass = $('#oldpass'),
            pass1   = $('#pass1'),
            pass2   = $('#pass2');

        showSpinner(form, true);

        if (pass1 === pass2) {
            query = 'password='+pass1;
        } else { 
            newpass.val('');
            showSpinner(form, false);
            return showMessage(form, true, 'New Passwords do not match'); 
        }
    };




}(window.jQuery));