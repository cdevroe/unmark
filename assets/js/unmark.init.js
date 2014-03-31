/*!
    Action Scripts for Unmark.it
    Copyright 2014 - Plain - http://plainmade.com

    A set of functions used to show interactions throughout Unmark.

    Also includes the INIT file.

*/

(function ($) {

    // Main Init Script
    unmark.init = function () {

        // Define some re-usable elements
        this.nav_panel = $('.navigation-pane'),
        this.main_panel = $('.main-wrapper'),
        this.main_content = $('.main-content'),
        this.sidebar_content = $('.sidebar-content'),
        this.main_panel_width = unmark.main_panel.width(),
        this.sidebar_default = $('.sidebar-default'),
        this.sidebar_mark_info = $('.sidebar-mark-info'),
        this.body_height = $(window).outerHeight(true),
        this.special_chars     = { '\\+': '&#43;' };
        this.mainpanels = $('#unmark-wrapper');

        // Check Query String
        var load = unmark.readQuery('load');
        if (load !== false) {
            unmark.overlay(true);
            $('#'+load).show().animate({ top: 0 }, 1000);
        }


        // Set any window variables
        window.unmark_current_page = 1;

        // Animate the body fading in
        if (Modernizr.mq('only screen and (min-width: 480px)')) {
            $('body').animate({opacity: 1}, 1000);
        }

        // Vertical Tabs
        // Shows and Tabs the Vertical navigition inside the Navigation Panel
        $('.navigation-content a, .navigation-pane-links a').on('click', function (e) {
            unmark.interact_nav(e, $(this));
        });

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
            e.stopPropagation();
            var action = $(this).data('action'), funct; // Get Data Action Attribute
            funct = eval('unmark.' + action); // Convert it to an executable function
            funct($(this)); // Run it with passed in object
            //unmark.hideNavigation(); // Hide Main Navigation
        });

        // Slide Toggle the Sidebar Info Blocks
        $(document).on('click', '.sidebar-info-panel h4.prev-coll', function (e) {
            e.preventDefault();
            var section = $(this).next('section'), arrow = $(this).find('i');
            if (section.is(':visible')) {
                arrow.removeClass('icon-up');
                arrow.addClass('icon-down');
                section.slideUp();
            } else {
                arrow.removeClass('icon-down');
                arrow.addClass('icon-up');
                section.slideDown();
            }
        });

        // Click / Tap on a Mark opens the more info and shows the buttons
        $(document).on('click', '.mark', function (e){
            var node = e.target.className, more_link = $(this).find('a.mark-info');
            // Check for Archive... don't show info for this, otherwise show the info
            if (node !== "icon-check" && node !== "action mark-archive") {
                unmark.show_mark_info(more_link);
            }
            // Hide Nav
            unmark.hideNavigation();
        });

        // Watch for internal link click and run PJAX
        // To Do, remove outside links from elem array
        if ( $('#unmark').length > 0 ) {
            $(document).pjax("a[href*='/']", unmark.main_content);
            $(document).on('submit', '#search-form', function(e) {
                $.pjax.submit(e, unmark.main_content);
            });
            $(document).on('pjax:complete', function() {
                if (Modernizr.mq('only screen and (max-width: 480px)')) { unmark.mobile_nav(true); }
                window.unmark_current_page = 1;
                unmark.main_content.scrollTop(0);
                unmark.main_content.find('.marks').hide().fadeIn();
                unmark.updateDom();
            });
        }

        // Hooks up all functions for client/customer hidden forms
        $('form.ajaxsbmt').on('submit', function (e) {
            e.preventDefault();
            var form = $(this),
                formid = form.attr('id');
            funct = eval('unmark.' + formid);
            funct(form, e);
        });

        $('#helperforms input.field-input').on('keydown change', function () {
            $(this).parent().parent().find('.response-message').hide();
        });

        // Close Overlay
        $(document).on('click', '#unmarkModalClose', function (e) {
            e.preventDefault();
            return unmark.overlay(false);
        });

        // Label Hovers
        $(document).on('mouseenter', '.label-choices li, .sidebar-label-list li', function (e) {
            var label = $(this),
                label_name = label.find('span').text(),
                label_class = label.attr('class');
            $('#label-chosen').show().text(label_name).removeClass().addClass(label_class);
        });
        $(document).on('mouseleave', '.label-choices li, .sidebar-label-list li', function (e) {
            $('#label-chosen').show().hide();
        });

        // Set up Scrolling
        unmark.main_content.on('scroll', function (e){
            unmark.scrollPaginate($(this));
        });

        // Import Form Init
        $('.importer').change(function (e) {
            return $('#importForm').submit();
        });
        $('.importerHTML').change(function (e) {
            return $('#importFormHTML').submit();
        });

    };

    // Get this baby in action
    $(document).ready(function(){ unmark.init(); });

}(window.jQuery));
