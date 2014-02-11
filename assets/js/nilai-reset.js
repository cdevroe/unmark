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


        function toggleHelp() {
            nilaireset.helper.fadeToggle();
        }

        // Show/Hide Help
        nilaireset.helptrigger.on('click', toggleHelp);

        // Show second password field
        nilaireset.firstpass.on('keypress change', function () {
            nilaireset.secondpass.fadeIn();
        });

        // Show Submit Button
        nilaireset.secondpass.on('keypress change', function () {
            nilaireset.submitbtn.fadeIn();
        });



    });

}(window.jQuery));