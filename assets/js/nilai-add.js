/*!
    Add Page scripts for Nilai.co
    Copyright 2014 - Plain - http://plainmade.com
*/

(function ($) {

    // Wait for Page to load
    $(document).ready(function () {

        function built_label_list(res) {
            console.log(res);
            var key, labels = res.labels, obj, list = '';
            for (key in labels) {
               obj = labels[key];
               list += '<li class="label-'+ obj['label_id'] +'"><a href="#" rel="'+ obj['label_id'] +'">'+ obj['name'] +'</a></li>';
            }
            $('ul.label-choices').prepend(list);
        };

        // Grab the labels list
        nilai.getData('labels', built_label_list);



    });



}(window.jQuery));