/*!
    Action Scripts for Nilai.co
    Copyright 2014 - Plain - http://plainmade.com

    A set of functions used to show interactions throughout Nilai.

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

    // Pagination on Scroll
    nilai.scrollPaginate = function (cont) {
        var url, page, i, template, output = '', next_page,
            mark_count = nilai.vars.per_page;
        if (cont.scrollTop() + cont.innerHeight() >= cont[0].scrollHeight) {
            template = Hogan.compile(nilai.template.marks);
            url = window.location.pathname;
            next_page = parseInt(nilai.readCookie('nilai_page')) + 1;
            console.log(next_page);
            nilai.ajax(url+next_page, 'post', '', function (res) {
                for (i = 1; i < mark_count; i++) {
                    output += template.render(res.marks[i]);
                }
                nilai.main_content.find('.marks_list').append(output);
                nilai.createCookie('nilai_page', next_page, 7);
            });
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

    // Show or Hide the Overlay
    nilai.overlay = function (show) {
        if (show) {
            nilai.mainpanels.addClass('blurme');
            var overlay = $('<div id="nilai-overlay"><a href="#" id="nilaiModalClose"><i class="barley-icon-remove"></i></a></div>');
            overlay.appendTo(document.body);
        } else {
            $('#resetPasswordForm').hide().css('top', '-300px');
            $('#changePasswordForm').hide().css('top', '-300px');
            nilai.mainpanels.removeClass('blurme');
            $('#nilai-overlay').remove();
        }
    };

    // For Fun
    nilai.awesome = function () {
        return alert('Awesome Enabled! (this does nothing)');
    };

}(window.jQuery));