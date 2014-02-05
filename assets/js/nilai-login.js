/*!
    Main scripts for Nilai.co
    Copyright 2014 - Plain - http://plainmade.com
*/

(function ($) {

    $(document).ready(function () {

        // Toggle the Login Form
        function toggle_login_form(hide) {
            if (hide === true) {
                $('.loginWrapper').animate({ top: '-325px' }, 500, function () {
                    $('.nilai-spinner').fadeIn();
                });
            } else {
                $('.nilai-spinner').fadeOut(400, function () {
                    $('.loginWrapper').animate({ top: '0' }, 500);
                });
            }
        }

        // Successfull Login - Show Checkmark and redirect to login
        function login_success() {
            $('.nilai-spinner').fadeOut(400, function () {
                $('.nilai-success').fadeIn(400, function () {
                    setTimeout(function(){ window.location.href = "/marks" }, 800);
                });
            });   
        }

        // Process the login
        // Decide what do do on sucess or failure
        function process_login(query) {
            nilai.ajax('/login', 'post', query, function (res) {
                if (typeof res.lookup_type != 'undefined') {
                    login_success();
                } else {
                    alert('Incorrect Login');
                    toggle_login_form(false);
                }
            });           
        }

        // Login Submit Action
        $('#nilaiLogin').on('submit', function (e) {

            e.preventDefault(); // prevent page submit
            toggle_login_form(true); // Hide the Login Form

            // Set and get variables
            var email = $('#email').val(),
                pass  = $('#password').val(),
                query = 'email='+email+'&password='+pass;

            // Check the login
            setTimeout(function(){ process_login(query) }, 1500);

        });

    });

}(window.jQuery));