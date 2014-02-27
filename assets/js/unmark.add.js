/*!
    Add Page scripts for Unmark.it
    Copyright 2014 - Plain - http://plainmade.com
*/

(function ($) {

    // Wait for Page to load
    $(document).ready(function () {

        // Gets an HTML list of data and prepends the list
        // Run as a callback for the getData function below
        function built_label_list(res) {
            var list = unmark.label_list(res);
            $('ul.label-choices').prepend(list);
        };

        // Function to check the current label for the mark saved
        function check_for_label() {
            
        };


        // Grab the labels list
        unmark.getData('labels', built_label_list);



    
    });

}(window.jQuery));