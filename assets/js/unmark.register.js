/*!
    Register/Signup
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
