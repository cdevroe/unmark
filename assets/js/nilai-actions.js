/*!
    Action Scripts for Nilai.co
    Copyright 2014 - Plain - http://plainmade.com

    A set of functions used to show interactions throughout Nilai.

    Also includes the INIT file.

*/

(function ($) { 

    // Rebuild the dom after pjax call
    nilai.updateDom = function () {

        var label_class = $('div.marks').data('label-class'),
            body = $('body');

        // Change Body Class for Label Colors
        body.removeClass().addClass(label_class);

        // Bind the new mark action buttons
        this.update_mark_action_btns();

        // Update Body Height ... just in case
        nilai.page_setup($('body').height());

    };

    // Collapse Marks Info Sidebar
    // Hides the marks info and re-displays the default sidebar
    nilai.sidebar_collapse = function () {
        $('.mark').removeClass('view-inactive').removeClass('view-active');
        nilai.sidebar_mark_info.fadeOut(400, function () {
            nilai.sidebar_default.fadeIn(400);
        });
    };

    // Hides the left navigation
    nilai.hideNavigation = function () {
        nilai.nav_panel.animate({ left: -285 }, { duration: 200, queue: false });
        nilai.main_panel.animate({ left: 65 }, { duration: 200, queue: false });
        $('.nav-panel').hide();
        $('.menu-item').removeClass('active-menu');
        $('.navigation-pane-links').show();
    };

    // Function for interacting and animating the left navigation
    // This handels both the top level and secondarly level
    nilai.interact_nav = function (e, elem_ckd) {
        // Set variables
        var panel_to_show = elem_ckd.attr('href'),
            panel_name = panel_to_show.replace(/^#/, ''),
            panel_width = parseInt(elem_ckd.attr('rel')),
            panel_animate = panel_width + 65,
            elem_parent = elem_ckd.parent(),
            panel_position = parseInt(nilai.nav_panel.css('left'));

        // If all links pannel - allow click default
        if (panel_to_show.match(/\//)) { return true; }

        // Otherwise prevent click default
        e.preventDefault();

        // If tap/click on open menu, hide menu
        if (elem_parent.hasClass('active-menu')) {
            $('.menu-item').removeClass('active-menu');
            return nilai.hideNavigation();
        }

        // Add / Remove Class for current navigation
        $('.menu-item').removeClass('active-menu');
        $('.navigation-content').find("[data-menu='" + panel_name + "']").addClass('active-menu');

        // Check for close action on and open panel
        if (panel_to_show === "#panel-menu") {
            if (panel_position > 0) {
                return nilai.hideNavigation();
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
    };

    // Simple Ajax method to get a list of results from API
    nilai.getData = function (what, caller) {
        nilai.ajax('/marks/get/'+what, 'post', '', caller);
    };

    // Simple Close Window
    nilai.close_window = function () { window.close(); };

    // Simple function for hiding Elements the user wants gone
    // TO DO : Hook this up to a cookie so they are gone for good
    nilai.dismiss_this = function (btn) {
        btn.parent().parent().fadeOut();
    }; 

    // Page Set Up
    nilai.page_setup = function (height) {
        nilai.main_content.height(height);
        nilai.sidebar_content.height(height);
        $('.nav-panel').height(height);
        $('body').height(height);
    };

    // For Fun
    nilai.awesome = function () {
        return alert('Awesome Enabled! (this does nothing)');
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
        this.body_height = $('body').height(),
        this.special_chars     = { '\\+': '&#43;' };

        // Basic Page Setup
        this.main_panel.width(nilai.main_panel_width);
        nilai.page_setup(nilai.body_height);

        // Animate the body fading in
        $('body').animate({opacity: 1}, 1000);

        // Vertical Tabs
        // Shows and Tabs the Vertical navigition inside the Navigation Panel
        $('.navigation-content a, .navigation-pane-links a').on('click', function (e) {
            nilai.interact_nav(e, $(this));
        });

        // Update Mark Btns
        nilai.update_mark_action_btns();

        // Hover Action on Marks List
        // Shows the Archive and More Buttons
        $(document).on('mouseenter', '.mark', function () {
            $(this).addClass('hide-dot');
            $(this).find('.mark-actions').show();
        });
        $(document).on('mouseleave', '.mark', function () {
            $(this).removeClass('hide-dot');
            $(this).find('.mark-actions').hide();
        });

        // Global Buton / Action Link Run
        // Create a Function from a string
        $(document).on('click', 'button[data-action], .action', function (e) {
            e.preventDefault();
            var action = $(this).data('action'), funct; // Get Data Action Attribute
            funct = eval('nilai.' + action); // Convert it to an executable function
            funct($(this)); // Run it with passed in object
            nilai.hideNavigation(); // Hide Main Navigation
        });

        // Slide Toggle the Sidebar Info Blocks
        $(document).on('click', '.sidebar-info-panel h4.prev-coll', function (e) {
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

        // Click / Tap on a Mark opens the more info and shows the buttons
        $(document).on('click', '.mark', function (e){
            var node = e.target.nodeName, more_link = $(this).find('a.mark-info');
            if (node !== "A" && node !== "I") {
                e.preventDefault();
                more_link.trigger('click');
            }
            nilai.hideNavigation(); // Hide Main Navigation
        });

        // Watch for internal link click and run PJAX
        // To Do, remove outside links from elem array
        if ( $('#nilai').length > 0 ) {
            $(document).pjax("a[href*='/']", nilai.main_content);
            $(document).on('submit', '#search-form', function(e) {
                $.pjax.submit(e, nilai.main_content);
            });
            $(document).on('pjax:complete', function() {
                nilai.main_content.find('.marks').hide().fadeIn();
                nilai.updateDom();
            });
        }

    };

    // Get this baby in action
    $(document).ready(function(){ nilai.init(); });

}(window.jQuery));