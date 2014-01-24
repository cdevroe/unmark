
/*!
    Main scripts for Nilai.co
    Copyright 2014 - Plain - http://plainmade.com
*/


// hoverIntent r7 // 2013.03.11 // jQuery 1.9.1+ // http://cherne.net/brian/resources/jquery.hoverIntent.html
(function(e){e.fn.hoverIntent=function(t,n,r){var i={interval:100,sensitivity:7,timeout:0};if(typeof t==="object"){i=e.extend(i,t)}else if(e.isFunction(n)){i=e.extend(i,{over:t,out:n,selector:r})}else{i=e.extend(i,{over:t,out:t,selector:n})}var s,o,u,a;var f=function(e){s=e.pageX;o=e.pageY};var l=function(t,n){n.hoverIntent_t=clearTimeout(n.hoverIntent_t);if(Math.abs(u-s)+Math.abs(a-o)<i.sensitivity){e(n).off("mousemove.hoverIntent",f);n.hoverIntent_s=1;return i.over.apply(n,[t])}else{u=s;a=o;n.hoverIntent_t=setTimeout(function(){l(t,n)},i.interval)}};var c=function(e,t){t.hoverIntent_t=clearTimeout(t.hoverIntent_t);t.hoverIntent_s=0;return i.out.apply(t,[e])};var h=function(t){var n=jQuery.extend({},t);var r=this;if(r.hoverIntent_t){r.hoverIntent_t=clearTimeout(r.hoverIntent_t)}if(t.type=="mouseenter"){u=n.pageX;a=n.pageY;e(r).on("mousemove.hoverIntent",f);if(r.hoverIntent_s!=1){r.hoverIntent_t=setTimeout(function(){l(n,r)},i.interval)}}else{e(r).off("mousemove.hoverIntent",f);if(r.hoverIntent_s==1){r.hoverIntent_t=setTimeout(function(){c(n,r)},i.timeout)}}};return this.on({"mouseenter.hoverIntent":h,"mouseleave.hoverIntent":h},i.selector)}})(jQuery)



if (nilai === undefined) { var nilai = {}; }

(function ($) {

    // Archive & Restore Mark
    nilai.archive_mark = function (mark) {

        var url, markid, preview, note;

        url = mark.attr("href");
        
        markid = mark.attr("data-mark");
        preview = $('#preview-'+markid);
        note = $('#note-'+markid);
        
        if (preview && !$(preview).is(':hidden')) { preview.toggle(); }
        
        if (note && !$(note).is(':hidden')) { note.toggle(); }
        
        $.ajax({
          url: url,
          success: function(){}
        });
        
        mark.parent().parent().parent().hide(800);
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

        // Show / Hide Top Navigation
        $('.navigation-content').hide();
        $('.menu-activator a').on('click', function (e) {
            e.preventDefault();
            $('.navigation-content').toggle();
        });


        // Show/Hide Navigation Items
        $('ul.navigation-left li').hoverIntent(function () {
            $(this).find('ul.dropdown').slideDown();
        }, function () {
            $(this).find('ul.dropdown').slideUp();
        });
        $('ul.navigation-left a.nilai_main_link').on('click', function (e) { e.preventDefault(); });

        // Archive Link
        $('a.archivemark').on('click', function (e) {
            e.preventDefault();
            nilai.archive_mark($(this));
        });

        // Label Mark
        $('.addlabel').click(function (e) {
            e.preventDefault();
            nilai.label_mark($(this));
        });

        // Add to Group
        $('.addgroup').click(function (e) {
            e.preventDefault();
            nilai.add_to_group($(this));
        });

        // Smart Label
        $('#smartlabelbutton').click(function (e) {
            e.preventDefault();
            nilai.smart_label($(this));
        });

        // Preview Data
        $('.preview-button').click(function (e) {
            e.preventDefault();
            nilai.show_data($(this));
        });

    };


    // Get this baby in action
    //$(document).ready(function(){ nilai.init(); });

}(window.jQuery));




$(document).ready(function(){ 

    var nav_panel = $('.navigation-pane'), main_panel = $('.main-wrapper');


    // Main Panel Navigation
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

    // Hover Action on Marks List
    $(".mark").hoverIntent(function () {
        $(this).find('.mark-corner').toggle();
        $(this).find('.mark-actions').toggle();
    }, function () {
        $(this).find('.mark-corner').toggle();
        $(this).find('.mark-actions').toggle();
    });

    // Vertical Tabs
    $('.navigation-content a, .navigation-pane-links a').on('click', function (e) {
        
        var panel_to_show = $(this).attr('href');

        if (panel_to_show === "/") {
            return true;
        } else if (panel_to_show === "#show-menu") {
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

});