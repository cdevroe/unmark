/*!
    Action Scripts
    A set of functions used to show interactions.
*/

(function ($) {

    // Rebuild the dom after pjax call
    unmark.updateDom = function () {

        var label_class = $('div.marks').data('label-class'),
            body = $('body');

        // Change Body Class for Label Colors
        body.removeClass().addClass(label_class);

        // Update Body Height ... just in case
        unmark.page_setup($('body').height());

    };

    // Collapse Marks Info Sidebar
    // Hides the marks info and re-displays the default sidebar
    unmark.sidebar_collapse = function () {
        $('.mark').removeClass('view-inactive').removeClass('view-active');
        if (Modernizr.mq('only screen and (max-width: 768px)')) {
          $('.sidebar-content').removeClass('active');
          $('#unmark-wrapper').removeClass('sidebar-active');
          $('.mobile-header').removeClass('shift-left');
        } else {
          $('.sidebar-content').removeClass('active');
        }
    };

    // Expands or Compresses the Info Sidebar
    unmark.sidebar_expand = function (compress) {

        var expBtn = unmark.sidebar_content.find('a[data-action="sidebar_expand"] i');

        if (compress === true) {
            return unmark.sidebar_content.animate({ width: '340px' }, 300, function () {
                expBtn.removeClass('icon-heading_collapse').addClass('icon-heading_expand');
                unmark.sidebar_content.removeClass('wide');
            });
        }

        if (expBtn.hasClass('icon-heading_collapse')) {
            unmark.sidebar_content.animate({ width: '340px' }, 300, function () {
                expBtn.removeClass('icon-heading_collapse').addClass('icon-heading_expand');
                unmark.sidebar_content.removeClass('wide');
            });
        } else {
            unmark.sidebar_content.animate({ width: '340px' }, 300, function () {
                expBtn.removeClass('icon-heading_expand').addClass('icon-heading_collapse');
                unmark.sidebar_content.addClass('wide');
            });
        }

    };

    // Function for interacting and animating the left navigation
    // This handles both the top level and secondary level
    unmark.interact_nav = function (e, elem_ckd) {
        e.preventDefault();
        unmark.sidebar_collapse(); // close bookmark info panel
        var panel_to_show                   = (elem_ckd.data('panel')) ? elem_ckd.data('panel') : '', // kept in data attribute
            panel_name                      = (panel_to_show !== '') ? panel_to_show.replace(/^#/, '') : '', // just removes #
            is_label_menu                   = (panel_name.indexOf('label') !== -1) ? true : false, // checks name of panel to see if this is label menu
            is_label_filter                 = (elem_ckd.attr('href').indexOf('label') !== -1) ? true : false, // checks href to see if this is label filter
            is_tag_menu                     = (panel_name.indexOf('tags') !== -1) ? true : false, // checks name of panel to see if it is tags menu
            is_tag_filter                   = (elem_ckd.attr('href').indexOf('tag') !== -1) ? true : false, // checks href to see if this is an actual hashtag
            is_archive_menu                 = (panel_name.indexOf('archive') !== -1) ? true : false, // checks panel name to see if this is the archive menu
            hide_mobile_sub_menu            = false; // on mobile, hide the submenu, default false

        // This means one of the labels was clicked.
        if ( is_label_menu || is_label_filter ) {
            panel_name              = 'panel-label';
            panel_to_show           = '#'+panel_name;
            hide_mobile_sub_menu    = true; // hiding submenu
        }

        // For the tag menu, or actual hashtags
        if ( is_tag_menu || is_tag_filter ) {
            panel_name              = 'panel-tags';
            panel_to_show           = '#'+panel_name;
            if ( !is_tag_menu && is_tag_filter ) { // tag filter was clicked
                hide_mobile_sub_menu = true;
            } else if ( is_tag_menu && !is_tag_filter ) { // tag menu was clicked, but _Not_ tag filter
                hide_mobile_sub_menu = false;
            }
        }

        if ( is_archive_menu ) {
            hide_mobile_sub_menu = true;
        }

        // Add / Remove Class for current navigation
        $('.menu-item').removeClass('active-menu');
        $('.navigation-content').find("[data-menu='" + panel_name + "']").addClass('active-menu');

        // For all panels (except settings) run pjax manually.
        if ( panel_to_show !== "#panel-settings" ) {
            $.pjax({ url: elem_ckd.attr('href'), container: '.main-content', timeout:6000 });
        }

        // Hides all panels except the one we're navigation to
        $('.nav-panel').not(panel_to_show).hide();
        $(panel_to_show).show();

        // hide mobile menu if these conditions are met:
        //      clicked on any main navigation item other than hashtags
        //      click on an actual hashtag
        //      click on an actual label
        if (Modernizr.mq('only screen and (max-width: 767px)') && hide_mobile_sub_menu ) {
            unmark.mobile_nav(true);
        }

        return false;
    };

    // Pagination on Scroll
    unmark.scrollPaginate = function (cont) {
        var url, i, template, output = '', next_page, mark_count,
            ajax_loading = false,
            next_page = window.unmark_current_page + 1,
            total_pages = window.unmark_total_pages;

        if ( pages_already_loaded[next_page] == true ) { return; }

        if (cont.scrollTop() + cont.innerHeight() >= cont[0].scrollHeight && ajax_loading !== true) { // Only load if the amount scrolled is greater than the view area for the marks, also if the ajax isn't already firing

            if (next_page <= total_pages && pages_already_loaded[next_page] !== true && ajax_loading !== true) { // only fire if this page hasn't already been retrieved
                ajax_loading = true;

                template = Hogan.compile(unmark.template.marks);
                url = window.location.pathname;
                pages_already_loaded[next_page] = true; // jot down that this page has already been loaded
                unmark.ajax(url+'/'+next_page, 'post', '', function (res) {
                    if (res.marks) {
                        mark_count = Object.keys(res.marks).length;
                        for (i = 1; i < mark_count; i++) {
                            res.marks[i]['prettyurl'] = unmark.prettyLink(res.marks[i]['url']);
                            output += template.render(res.marks[i]);
                        }
                        unmark.main_content.find('.marks_list').append(output);
                        window.unmark_current_page = next_page;
                        ajax_loading = false;
                    }
                });
            }
        }
    };

    // Update the count in the sidebar and graph upon mark archive/unarchive
    // Not in use as of 1.9.2 (unless we bring back stats)
    unmark.updateCounts = function () {
        unmark.getData('stats', function (res) {

            var archived = res.stats.archived,
                saved = res.stats.saved,
                marks = res.stats.marks;

            // First update sidebar count
            $('.na-today').text(archived.today);
            $('.ns-year').text(marks['ages ago']);

        });
    };

    // Simple Ajax method to get a list of results from API
    unmark.getData = function (what, caller) {
        unmark.ajax('/marks/get/'+what, 'post', '', caller);
    };

    // Simple Close Windows
    unmark.close_window = function (nosave) {
        if (nosave) { return window.close(); } // Don't save anything, just close it.
        var notes = $('.mark-added-note').find('textarea').val(),
            id = $('.mark-added-note').find('textarea').data('id');
        unmark.saveNotes(id, notes);
        window.close();
    };

    // Simple function for hiding Elements the user wants gone
    // TO DO : Hook this up to a cookie so they are gone for good
    unmark.dismiss_this = function (btn) {
        btn.parent().parent().fadeOut();
    };

    // Page Set Up
    unmark.page_setup = function (height) {
        unmark.main_content.height(height);
        unmark.sidebar_content.height(height);
        $('.nav-panel').height(height);
        $('body').height(height);
    };

    // Show or Hide the Overlay
    unmark.overlay = function (show) {
        if (show === true) {
            unmark.mainpanels.addClass('blurme');
            var overlay = $('<div id="unmark-overlay"></div>');
            overlay.appendTo(document.body);
            $('#unmark-overlay').fadeIn(200);
        } else {
            //$('#unmark-overlay').removeClass('active');
            //$('.hiddenform').hide();
            unmark.mainpanels.removeClass('blurme');
            $('#unmark-overlay').fadeOut(400);
            $('.hiddenform').fadeOut(300);
            setTimeout(function() {
              $('#unmark-overlay').remove();
              $('.hiddenform').hide();
            }, 500);
            $('#helperforms input').val('');
        }
    };

}(window.jQuery));
