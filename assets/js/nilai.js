
/*!
    Main scripts for Nilai.co
    Copyright 2014 - Plain - http://plainmade.com
*/


// hoverIntent r7 // 2013.03.11 // jQuery 1.9.1+ // http://cherne.net/brian/resources/jquery.hoverIntent.html
(function(e){e.fn.hoverIntent=function(t,n,r){var i={interval:100,sensitivity:7,timeout:0};if(typeof t==="object"){i=e.extend(i,t)}else if(e.isFunction(n)){i=e.extend(i,{over:t,out:n,selector:r})}else{i=e.extend(i,{over:t,out:t,selector:n})}var s,o,u,a;var f=function(e){s=e.pageX;o=e.pageY};var l=function(t,n){n.hoverIntent_t=clearTimeout(n.hoverIntent_t);if(Math.abs(u-s)+Math.abs(a-o)<i.sensitivity){e(n).off("mousemove.hoverIntent",f);n.hoverIntent_s=1;return i.over.apply(n,[t])}else{u=s;a=o;n.hoverIntent_t=setTimeout(function(){l(t,n)},i.interval)}};var c=function(e,t){t.hoverIntent_t=clearTimeout(t.hoverIntent_t);t.hoverIntent_s=0;return i.out.apply(t,[e])};var h=function(t){var n=jQuery.extend({},t);var r=this;if(r.hoverIntent_t){r.hoverIntent_t=clearTimeout(r.hoverIntent_t)}if(t.type=="mouseenter"){u=n.pageX;a=n.pageY;e(r).on("mousemove.hoverIntent",f);if(r.hoverIntent_s!=1){r.hoverIntent_t=setTimeout(function(){l(n,r)},i.interval)}}else{e(r).off("mousemove.hoverIntent",f);if(r.hoverIntent_s==1){r.hoverIntent_t=setTimeout(function(){c(n,r)},i.timeout)}}};return this.on({"mouseenter.hoverIntent":h,"mouseleave.hoverIntent":h},i.selector)}})(jQuery)

if (nilai === undefined) { var nilai = {}; }

(function ($) {

    // Show Mark Info in Sidebar
    // Grabs relavaent info and shows the sidebar actions with info
    nilai.show_mark_info = function (mark_clicked) {
        
        var template, output,
            mark_obj_ref = mark_clicked.data('mark'),
            mark_string = $('#' + mark_obj_ref).html();
            mark_obj = jQuery.parseJSON(mark_string);

            console.log(mark_obj);

        // Compile and Render the template
        template = Hogan.compile(sidebar_template);
        output = template.render(mark_obj);

        nilai.sidebar_mark_info.fadeOut(400, function () {
            if (nilai.sidebar_default.is(':visible')) {
                nilai.sidebar_default.fadeOut(400, function () {
                    nilai.sidebar_mark_info.html(output).fadeIn(400);
                });
            } else {
                nilai.sidebar_mark_info.html(output).fadeIn(400);         
            }
        });
    };


    // Collapse Marks Info Sidebar
    // Hides the marks info and re-displays the default sidebar
    nilai.sidebar_collapse = function () {
        nilai.sidebar_mark_info.fadeOut(400, function () {
            nilai.sidebar_default.fadeIn(400);
        });
    };

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

    // Set A Label
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

    // Main Init Script
    nilai.init = function () {

        // Define some re-usable elements 
        this.nav_panel = $('.navigation-pane'), 
        this.main_panel = $('.main-wrapper'),
        this.main_content = $('.main-content'),
        this.sidebar_content = $('.sidebar-content'),
        this.main_panel_width = nilai.main_panel.width(),
        this.sidebar_default = $('.sidebar-default'),
        this.sidebar_mark_info = $('.sidebar-mark-info'),
        this.body_height = $('body').height();

        // Basic Page Setup
        nilai.main_panel.width(nilai.main_panel_width);
        nilai.main_content.height(nilai.body_height);
        nilai.sidebar_content.height(nilai.body_height);
        $('body').height(nilai.body_height);

        // Vertical Tabs
        // Shows and Tabs the Vertical navigition inside the Navigation Panel
        $('.navigation-content a, .navigation-pane-links a').on('click', function (e) {
            
            // Set variables
            var panel_to_show = $(this).attr('href'),
                panel_name = panel_to_show.replace(/^#/, ''),
                panel_width = parseInt($(this).attr('rel')),
                panel_animate = panel_width + 65,
                panel_position = parseInt(nilai.nav_panel.css('left'));

            // If all links pannel - allow click default
            if (panel_to_show === "/") { return true; }

            // Otherwise prevent click default
            e.preventDefault();

            // Add / Remove Class for current navigation
            $('.menu-item').removeClass('active-menu');
            $('.navigation-content').find("[data-menu='" + panel_name + "']").addClass('active-menu');

            // Check for close action on and open panel
            if (panel_to_show === "#panel-menu") {
                if (panel_position > 0) {
                    nilai.nav_panel.animate({ left: -258 }, { duration: 200, queue: false });
                    nilai.main_panel.animate({ left: 65 }, { duration: 200, queue: false });
                    $('.nav-panel').hide();
                    $('.navigation-pane-links').show();
                    return;
                }
            }

            // Check which panel to show
            nilai.nav_panel.animate({ left: 65 }, { duration: 200, queue: false });
            nilai.main_panel.animate({ left: panel_animate }, { duration: 200, queue: false });

            nilai.nav_panel.animate({ width: panel_width, }, 200);
            nilai.nav_panel.find('.nav-panel').animate({ width: panel_width, }, 200);

            if (panel_to_show === "#panel-menu"){
                $('.navigation-pane-links').show();
                $('.nav-panel').hide();                
            } else {
                $('.navigation-pane-links').hide();
                $('.nav-panel').not(panel_to_show).hide();
                $(panel_to_show).show();
            }
        });

        // Hover Action on Marks List
        // Shows the Archive and More Buttons
        $(".mark").hoverIntent(function () {
            $(this).addClass('hide-dot');
            $(this).find('.mark-actions').show();
        }, function () {
            $(this).removeClass('hide-dot');
            $(this).find('.mark-actions').hide();
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
        // Create a Function from a string
        $(document).on('click', 'button, a.action', function (e) {
            e.preventDefault();
            var action = $(this).data('action'), funct; // Get Data Action Attribute
            funct = eval('nilai.' + action); // Convert it to an executable function
            funct($(this)); // Run it with passed in object
        });

        // Slide Toggle the Sidebar Info Blocks
        $(document).on('click', '.sidebar-info-panel h4', function (e) {
            e.preventDefault();
            var section = $(this).next('section'), arrow = $(this).find('i');
            if (section.is(':visible')) {
                arrow.removeClass('barley-icon-chevron-up');
                arrow.addClass('barley-icon-chevron-down');
                section.slideUp();
            } else {
                arrow.removeClass('barley-icon-chevron-down');
                arrow.addClass('barley-icon-chevron-up');
                section.slideDown();                
            }
        });

    };

    // Get this baby in action
    $(document).ready(function(){ nilai.init(); });

}(window.jQuery));