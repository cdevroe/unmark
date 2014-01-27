
/*!
    Main scripts for Nilai.co
    Copyright 2014 - Plain - http://plainmade.com
*/


// hoverIntent r7 // 2013.03.11 // jQuery 1.9.1+ // http://cherne.net/brian/resources/jquery.hoverIntent.html
(function(e){e.fn.hoverIntent=function(t,n,r){var i={interval:100,sensitivity:7,timeout:0};if(typeof t==="object"){i=e.extend(i,t)}else if(e.isFunction(n)){i=e.extend(i,{over:t,out:n,selector:r})}else{i=e.extend(i,{over:t,out:t,selector:n})}var s,o,u,a;var f=function(e){s=e.pageX;o=e.pageY};var l=function(t,n){n.hoverIntent_t=clearTimeout(n.hoverIntent_t);if(Math.abs(u-s)+Math.abs(a-o)<i.sensitivity){e(n).off("mousemove.hoverIntent",f);n.hoverIntent_s=1;return i.over.apply(n,[t])}else{u=s;a=o;n.hoverIntent_t=setTimeout(function(){l(t,n)},i.interval)}};var c=function(e,t){t.hoverIntent_t=clearTimeout(t.hoverIntent_t);t.hoverIntent_s=0;return i.out.apply(t,[e])};var h=function(t){var n=jQuery.extend({},t);var r=this;if(r.hoverIntent_t){r.hoverIntent_t=clearTimeout(r.hoverIntent_t)}if(t.type=="mouseenter"){u=n.pageX;a=n.pageY;e(r).on("mousemove.hoverIntent",f);if(r.hoverIntent_s!=1){r.hoverIntent_t=setTimeout(function(){l(n,r)},i.interval)}}else{e(r).off("mousemove.hoverIntent",f);if(r.hoverIntent_s==1){r.hoverIntent_t=setTimeout(function(){c(n,r)},i.timeout)}}};return this.on({"mouseenter.hoverIntent":h,"mouseleave.hoverIntent":h},i.selector)}})(jQuery)



if (nilai === undefined) { var nilai = {}; }

(function ($) {

    // Archive & Restore Mark
    nilai.mark_archive = function (archive_link) {

        var url = archive_link.attr("href");
        
        $.ajax({
            url: url,
            success: function() {
                archive_link.parent().parent().fadeOut();
            }
        });

    };

    // Show Mark Info in Sidebar
    nilai.mark_more = function (more_link) {

    };

    nilai.label_mark = function (btn) {

        var label, urlid;

        if (btn.hasClass('active')) { return; } // Do nothing if clicking on a label that is already chosen

        label = btn.attr("title");
        urlid = $("#urlid").val();

        $.ajax({
            url: "/marks/addlabel?urlid="+urlid+"&label="+label,
            success: function(){}
        });

        $('.addlabel').removeClass('active');
        btn.addClass("active");

        $('#label').val(label);

        if ($('#clearlabelbutton').hasClass('disabled')) { // Make clear label and add smart label button active

            $('#clearlabelbutton').removeClass('disabled');
            $('#smartlabelbutton').removeClass('disabled');

        } else { // De-activate?

            if ($('#smartlabelbutton').hasClass('danger')) { // Just deactivate clearlabel, not smart label
                $('#clearlabelbutton').removeClass('active');
                $('#clearlabelbutton').addClass('disabled');
                return; 
            }

            if (label == '') { // if label is nothing, disable both buttons
                $('#clearlabelbutton').removeClass('active');
                $('#smartlabelbutton').removeClass('danger');
                $('#clearlabelbutton').addClass('disabled');
                $('#smartlabelbutton').addClass('disabled');
            }
        }
    };

    nilai.smart_label = function (btn) {

        var label, domain;

        if (btn.hasClass('disabled')) { return; } // If button is disabled do nothing.

        if (btn.hasClass('btn-danger')) {
            // Remove the smart label
            // Add the smart label
            label = $('#label').val();
            domain = $('#domain').val();

            $.ajax({
                url: "/marks/removesmartlabel?domain="+domain+"&label="+label,
                success: function(){}
            });

            btn.removeClass('btn-danger');
            btn.html('Add Smart Label?');

            if (label == '') btn.addClass('disabled');

            $('#smartlabelmessage').html('');
        } else {
            // Add the smart label
            label = $('#label').val();
            domain = $('#domain').val();

            $.ajax({
                url: "/marks/addsmartlabel?domain="+domain+"&label="+label,
                success: function(){}
            });

            btn.addClass('btn-danger');
            btn.html('Stop using Smart Label?');
            $('#smartlabelmessage').html('<small>All future marks from <strong>'+domain+'</strong> will be labeled <strong>'+label+'</strong> automatically.</small>');
        }
    };


    nilai.add_to_group = function (btn) {

        var urlid, currentgroup, newgroup, note;

        urlid = $("#urlid").val();
        currentgroup = $('#group').val();
        newgroup = btn.attr("data-group");

        if (btn.hasClass('active')) {
            // Remove from group
            $.ajax({
                url: "/marks/addgroup?urlid="+urlid+"&group=",
                success: function(){}
            });
            btn.removeClass('active');
        } else {
            // Add to group
            $('.addgroup').removeClass('active');
            $.ajax({
                url: "/marks/addgroup?urlid="+urlid+"&group="+newgroup,
                success: function(){}
            });
            $('#group').val(newgroup);
            btn.addClass("active");
        }

        note = $('#note').val();
        $.ajax({
            url: "/marks/savenote?urlid="+urlid+"&note="+encodeURIComponent(note),
            success: function(){}
        });
    };

    nilai.show_data = function (btn) {

        var preview, previewData, sidebarpane = $('#sidebar-preview');

        preview = btn.attr("href");
        previewData = $(preview).html();

        // Show/hide
        sidebarpane.html(previewData).slideDown();




    };



    // Main Init Script
    nilai.init = function () {

        // Define some re-usable elements 
        var nav_panel = $('.navigation-pane'), 
            main_panel = $('.main-wrapper'),
            main_content = $('.main-content'),
            main_panel_width = main_panel.width(),
            body_height = $('body').height();

        // Basic Page Setup
        main_panel.width(main_panel_width);
        main_content.height(body_height);
        $('body').height(body_height);


        // Main Panel Navigation
        // Determins when to slide out/in
        $(".menu-activator a").on('click', function (e) {
            if (nav_panel.css('left') === "65px") {
                nav_panel.animate({ left: -258 }, { duration: 200, queue: false });
                main_panel.animate({ left: 65 }, { duration: 200, queue: false });
                $('.nav-panel').hide();
                $('.navigation-pane-links').show();
            } else {
                nav_panel.animate({ left: 65 }, { duration: 200, queue: false });
                main_panel.animate({ left: 323 }, { duration: 200, queue: false });
            }
        });

        // Vertical Tabs
        // Shows and Tabs the Vertical navigition inside the Navigation Panel
        $('.navigation-content a, .navigation-pane-links a').on('click', function (e) {
            var panel_to_show = $(this).attr('href');

            if (panel_to_show === "/") { return true; }

            if (nav_panel.css('left') !== "65px") {
                nav_panel.animate({ left: 65 }, { duration: 200, queue: false });
                main_panel.animate({ left: 323 }, { duration: 200, queue: false });
            }
            if (panel_to_show === "#show-menu") {
                // Do somethign else
                e.preventDefault();
                return false;
            } else {
                e.preventDefault();
                $('.navigation-pane-links').hide();
                $('.nav-panel').not(panel_to_show).hide();
                $(panel_to_show).show();       
            }
        });

        // Hover Action on Marks List
        // Shows the Archive and More Buttons
        $(".mark").hoverIntent(function () {
            $(this).find('.mark-corner').toggle();
            $(this).find('.mark-actions').toggle();
        }, function () {
            $(this).find('.mark-corner').toggle();
            $(this).find('.mark-actions').toggle();
        });

        // Archive a Mark
        // Sends ajax request to archive a mark.
        $('a.mark-archive').on('click', function (e) {
            e.preventDefault();
            nilai.mark_archive($(this));
        });

        // View Mark Details
        // Shows info about mark and actions
        $('a.mark-more').on('click', function (e) {
            e.preventDefault();
            nilai.mark_more($(this));
        });


        // Global Buton / Action Link Run
        $('button, a.action').on('click', function (e) {

            e.preventDefault();

            console.log($(this).data('action'));

        });




    };


    // Get this baby in action
    $(document).ready(function(){ nilai.init(); });

}(window.jQuery));