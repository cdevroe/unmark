/*!
    User Scripts
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
            button.removeClass('icon-go').addClass('icon-spinner');
        } else {
            button.removeClass('icon-spinner').addClass('icon-go');
        }
    }

    // Logout Method
    unmark.logout = function () {
        window.location = "/logout";
    };


    unmark.change_password = function () {
        unmark.overlay(true);
        $('#resetPasswordForm').fadeIn(400);
    };
    unmark.change_email = function () {
        unmark.overlay(true);
        $('#changePasswordForm').fadeIn(400);
    };
    unmark.import_export = function () {
        unmark.overlay(true);
        $('#importExportForm').fadeIn(400);
    };
    unmark.delete_user = function () {
        unmark.overlay(true);
        $('#deleteUserForm').fadeIn(400);
    };

    // Submit Password Change
    unmark.send_password_change = function (form) {
        var query,
            new_pass_field = $('#pass1, #pass2'),
            old_pass_field = $('#oldpass'),
            oldpass = $('#oldpass').val(),
            pass1   = $('#pass1').val(),
            pass2   = $('#pass2').val();

        showSpinner(form, true);

        if (pass1 === pass2) {
            query = 'password='+unmark.urlEncode(pass1)+'&current_password='+unmark.urlEncode(oldpass);
            unmark.ajax('/user/update/password', 'post', query, function (res) {
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

    // Submit Delete User Account Request
    unmark.send_delete_user_request = function (form) {
        var query,
            pass_field = $('#pass'),
            pass = $('#pass').val();

        showSpinner(form, true);

        if (pass.length > 0) {
            query = 'password='+unmark.urlEncode(pass);
            unmark.ajax('/user/delete', 'post', query, function (res) {
                if (res.success) {
                    showMessage(form, false, 'Your account has been deleted. You are logged out.');
                } else {
                    showMessage(form, true, res.message);
                }
                showSpinner(form, false);
                setTimeout( function() { // old syntax for uglifying
                    location.href = '/';
                }, 3000);
                
            });
        } else {
            pass_field.val('');
            showSpinner(form, false);
            return showMessage(form, true, 'Please enter your password.');
        }
    };

    // Submit Email Change
    unmark.send_email_change = function (form) {
        var query,
            email_field = $('#emailupdate'),
            email_value = email_field.val();

        showSpinner(form, true);

        if (email_value !== '') {
            query = 'email='+unmark.urlEncode(email_value);
            unmark.ajax('/user/update/email', 'post', query, function (res) {
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

    // Export Data to Unmark
    unmark.export_data = function () {
        return window.location.href = "/export";
    };

    // Export Data to HTML
    unmark.export_data_html = function () {
        return window.location.href = "/export/html";
    };

    // Import Data
    unmark.import_data = function () {
        return $('#importerUnmark').trigger('click');
    };
    unmark.import_data_html = function () {
        return $('#importerHTML').trigger('click');
    };
    unmark.import_data_readability = function () {
        return $('#importerReadability').trigger('click');
    };


}(window.jQuery));
