/*!
    Add Page scripts for Nilai.co
    Copyright 2014 - Plain - http://plainmade.com
*/

(function ($) {

    // Wait for Page to load
    $(document).ready(function () {

        function built_label_list(res) {
            console.log(res);
        };

        // Grab the labels list
        nilai.getData('labels', built_label_list);



    });



}(window.jQuery));



/*<li class="label-2"><a href="#" rel="2">Read</a></li>
<li class="label-3"><a href="#" rel="3">Watch</a></li>
<li class="label-4"><a href="#" rel="4">Listen</a></li>
<li class="label-5"><a href="#" rel="5">Buy</a></li>
<li class="label-6"><a href="#" rel="6">Eat &amp; Drink</a></li>
<li class="label-7"><a href="#" rel="7">Do</a></li>
<li class="label-1"><a href="#" rel="1">Unlabeled</a></li>*/