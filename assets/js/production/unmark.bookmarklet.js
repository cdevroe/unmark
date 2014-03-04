/*! DEVELOPMENT VERSION */ 
/*!
    Main scripts for Unmark.it
    Copyright 2014 - Plain - http://plainmade.com

    A set of helper functions that can be called and used throughout the app

*/

if (unmark === undefined) { var unmark = {}; }

(function ($) {

    // Basic Ajax Function used throughout the app
    unmark.ajax = function (path, method, query, callback, data_type, async) {
        var csrf_token   = unmark.urlEncode(unmark.vars.csrf_token),
            data_type    = (data_type !== undefined) ? data_type : 'json',
            async        = (async !== undefined) ? async : true,
            added_vars   = 'csrf_token=' + csrf_token + '&content_type=' + data_type;
            query        = (unmark.empty(query)) ? added_vars : query + '&' + added_vars;

        $.ajax({
            'dataType': data_type,
            'cache': false,
            'url': path,
            'type': method.toUpperCase(),
            'data': query,
            'async': async,
            'success': function (res) {
                if ($.isFunction(callback)) {
                    callback(res);
                }
            },
            'error': function(xhr, status, error) {
                var json = {
                    'error': error,
                    'status': status,
                    'request': xhr
                };
                if ($.isFunction(callback)) {
                    callback(json);
                }
            }
        });

    };

    // Simple Swap Class Method that uses regex
    unmark.swapClass = function (elem, removals, additions) {
        var self = elem;

        // Check for simple replacement        
        if ( removals.indexOf( '*' ) === -1 ) {
            self.removeClass( removals );
            return !additions ? self : self.addClass( additions );
        }
     
        // If regex is passed in create pattern and search/replace
        var patt = new RegExp( '\\s' + 
                removals.
                    replace( /\*/g, '[A-Za-z0-9-_]+' ).
                    split( ' ' ).
                    join( '\\s|\\s' ) + 
                '\\s', 'g' );
     
        // Run the replace with regex pattern
        self.each( function (i, it) {
            var cn = ' ' + it.className + ' ';
            while ( patt.test(cn) ) {
                cn = cn.replace(patt, ' ');
            }
            it.className = $.trim(cn);
        });
     
        // Return new swap
        return !additions ? self : self.addClass(additions);
    };

    // Replace special chars
    unmark.replaceSpecial = function(str) {
        if (str !== undefined && str !== null) {
            var regex = null;
            for (var i in unmark.special_chars) {
                regex = new RegExp(i, 'gi');
                str   = str.replace(regex, unmark.special_chars[i]);
            }
        }
        return str;
    };

    // Encode for URL
    unmark.urlEncode = function(str) {
        str = unmark.replaceSpecial(str);
        return encodeURIComponent(str);
    };

    // Nice Check Empty Function
    unmark.empty = function(v) {
        var l = (v !== undefined && v !== null) ? v.length : 0;
        return (v === false || v === '' || v === null || v === 0 || v === undefined || l < 1);
    };

    // Function to Create/Update Cookies
    unmark.createCookie = function (name,value,days) {
        if (days) {
            var date = new Date();
            date.setTime(date.getTime()+(days*24*60*60*1000));
            var expires = "; expires="+date.toGMTString();
        }
        else var expires = "";
        document.cookie = name+"="+value+expires+"; path=/";
    };

    // Function to Read Cookie
    unmark.readCookie = function (name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for(var i=0;i < ca.length;i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1,c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
        }
        return null;
    };

    // Prefity Link
    unmark.prettyLink = function (link) {
        link = link.replace(/https?:\/\/(www.)?/, '');
        if(link.substr(-1) === '/') {
            link = link.substr(0, link.length - 1);
        }
        return link;
    };
    
    /**
	 * Extends given function by calling the original function and then executing
	 * another piece of code after original invocation
	 * @returns New called function result (if not null) or original function result otherwise
	 */
	unmark.extendFunction = function(functionName, newFunction) {
		this[functionName] = (function(_obj, _super, _new) {
			return function() {
				var _origResult = _super.apply(_obj, arguments);
				var _newResult = _new.apply(_obj, arguments);
				return _newResult !== null ? _newResult : _origResult;
			};
		})(this, this[functionName], newFunction);
	};


}(window.jQuery));
/*!
    Action Scripts for Unmark.it
    Copyright 2014 - Plain - http://plainmade.com

    A set of functions used to show interactions throughout Unmark.

*/

(function ($) {

    // Rebuild the dom after pjax call
    unmark.updateDom = function () {

        var label_class = $('div.marks').data('label-class'),
            body = $('body');

        // Change Body Class for Label Colors
        body.removeClass().addClass(label_class);

        // Bind the new mark action buttons
        this.update_mark_action_btns();

        // Update Body Height ... just in case
        unmark.page_setup($('body').height());

    };

    // Collapse Marks Info Sidebar
    // Hides the marks info and re-displays the default sidebar
    unmark.sidebar_collapse = function () {
        $('.mark').removeClass('view-inactive').removeClass('view-active');
        unmark.sidebar_expand(true);
        unmark.sidebar_mark_info.fadeOut(400, function () {
            unmark.sidebar_default.fadeIn(400);
        });
    };

    // Expands or Compresses the Info Sidebar
    unmark.sidebar_expand = function (compress) {

        var expBtn = unmark.sidebar_content.find('a[data-action="sidebar_expand"] i');

        if (compress === true) {
            return unmark.sidebar_content.animate({ width: '42.17749%' }, 800, function () {
                expBtn.removeClass('icon-heading_collapse').addClass('icon-heading_expand');
            });
        }

        if (expBtn.hasClass('icon-heading_collapse')) {
            unmark.sidebar_content.animate({ width: '42.17749%' }, 800, function () {
                expBtn.removeClass('icon-heading_collapse').addClass('icon-heading_expand');
            });
        } else {
            unmark.sidebar_content.animate({ width: '75%' }, 800, function () {
                expBtn.removeClass('icon-heading_expand').addClass('icon-heading_collapse');
            });
        }

    };

    // Hides the left navigation
    unmark.hideNavigation = function () {
        unmark.nav_panel.animate({ left: -285 }, { duration: 200, queue: false });
        unmark.main_panel.animate({ left: 65 }, { duration: 200, queue: false });
        $('.nav-panel').hide();
        $('.menu-item').removeClass('active-menu');
        $('.navigation-pane-links').show();
        $('.menu-activator i').removeClass('icon-menu_close').addClass('icon-menu_open');
    };

    // Function for interacting and animating the left navigation
    // This handels both the top level and secondarly level
    unmark.interact_nav = function (e, elem_ckd) {
        // Set variables
        var panel_to_show = elem_ckd.attr('href'),
            panel_name = panel_to_show.replace(/^#/, ''),
            panel_width = parseInt(elem_ckd.attr('rel')),
            panel_animate = panel_width + 65,
            elem_parent = elem_ckd.parent(),
            panel_position = parseInt(unmark.nav_panel.css('left'));

        // For Any Nav Click, Return Sidebar
        unmark.sidebar_collapse();

        // If all links pannel - allow click default
        if (panel_to_show.match(/\//)) {
            unmark.hideNavigation();
            return true;
        }

        // Otherwise prevent click default
        e.preventDefault();

        // If tap/click on open menu, hide menu
        if (elem_parent.hasClass('active-menu')) {
            $('.menu-item').removeClass('active-menu');
            return unmark.hideNavigation();
        }

        // Add / Remove Class for current navigation
        $('.menu-item').removeClass('active-menu');
        $('.navigation-content').find("[data-menu='" + panel_name + "']").addClass('active-menu');

        // Check for close action on and open panel
        if (panel_to_show === "#panel-menu") {
            if (panel_position > 0) {
                return unmark.hideNavigation();
            }
        }

        $('.menu-activator i').removeClass('icon-menu_open').addClass('icon-menu_close');

        // Check which panel to show
        unmark.nav_panel.animate({ left: 65 }, { duration: 200, queue: false });
        unmark.main_panel.animate({ left: panel_animate }, { duration: 200, queue: false });

        unmark.nav_panel.animate({ width: panel_width, }, 200);
        unmark.nav_panel.find('.nav-panel').animate({ width: panel_width, }, 200);

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
    unmark.scrollPaginate = function (cont) {
        var url, page, i, template, output = '', next_page, mark_count,
            next_page = window.unmark_current_page + 1,
            total_pages = window.unmark_total_pages;

        if (cont.scrollTop() + cont.innerHeight() >= cont[0].scrollHeight) {

            if (next_page <= total_pages) {

                template = Hogan.compile(unmark.template.marks);
                url = window.location.pathname;
                unmark.ajax(url+'/'+next_page, 'post', '', function (res) {
                    if (res.marks) {
                        mark_count = Object.keys(res.marks).length;
                        for (i = 1; i < mark_count; i++) {
                            res.marks[i]['prettyurl'] = unmark.prettyLink(res.marks[i]['url']);
                            output += template.render(res.marks[i]);
                        }
                        unmark.main_content.find('.marks_list').append(output);
                        window.unmark_current_page = next_page;
                        unmark.update_mark_action_btns();
                    }
                });
            }
        }
    };

    // Update the count in the sidebar and graph upon mark archive/unarchive
    unmark.updateCounts = function () {
        unmark.getData('stats', function (res) {

            var archived = res.stats.archived,
                saved = res.stats.saved,
                marks = res.stats.marks;

            // First update sidebar count
            $('.na-today').text(archived.today);
            $('.ns-year').text(marks['ages ago']);

            // Now the graph
            unmark.createGraph(archived['4 days ago'], archived['3 days ago'], archived['2 days ago'], archived['yesterday'], archived['today'], saved['4 days ago'], saved['3 days ago'], saved['2 days ago'], saved['yesterday'], saved['today']);

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
        if (show) {
            unmark.mainpanels.addClass('blurme');
            var overlay = $('<div id="unmark-overlay"><a href="#" id="unmarkModalClose"><i class="icon-big_close"></i></a></div>');
            overlay.appendTo(document.body);
        } else {
            $('.hiddenform').hide().css('top', '-300px');
            unmark.mainpanels.removeClass('blurme');
            $('#unmark-overlay').remove();
            $('#helperforms input').val('');
        }
    };

}(window.jQuery));

/*!
    Marks Scripts for Unmark.it
    Copyright 2014 - Plain - http://plainmade.com

    A set of functions used to pull and push data for Marks

*/

(function ($) {

    // Show Mark Info in Sidebar
    // Grabs relavaent info and shows the sidebar actions with info
    unmark.show_mark_info = function (mark_clicked) {

        var template, output,
            mark_obj_ref    = mark_clicked.data('mark'),
            mark_string     = $('#' + mark_obj_ref).html(),
            mark_obj        = jQuery.parseJSON(mark_string),
            mark_id         = mark_obj_ref.replace("mark-data-",""),
            mark_notehold   = $('#mark-'+mark_id).find('.note-placeholder').text();

        // Quick function to populate the tags
        function populateLabels(res) {
            var list = unmark.label_list(res);
            $('ul.sidebar-label-list').prepend(list);
        };

        // Clean up view
        $('.mark').removeClass('view-inactive').removeClass('view-active');
        $('.mark').not('#mark-' + mark_id).addClass('view-inactive');
        $('#mark-' + mark_id).addClass('view-active');

        // Compile and Render the template

        // Check for note placeholder and update if there.
        if (mark_notehold !== ''){ mark_obj['notes'] = mark_notehold; }

        // Render the Template
        template = Hogan.compile(unmark.template.sidebar);
        output = template.render(mark_obj);

        // Run the view interaction
        unmark.sidebar_mark_info.fadeOut(400, function () {
            if (unmark.sidebar_default.is(':visible')) {
                unmark.sidebar_default.fadeOut(400, function () {
                    unmark.sidebar_mark_info.html(output).fadeIn(400, function () {
                        unmark.tagify_notes($('#notes-' + mark_id));
                        unmark.getData('labels', populateLabels);
                        $("section.sidebar-info-preview").fitVids();
                    });
                });
            } else {
                unmark.sidebar_mark_info.html(output).fadeIn(400, function () {
                    unmark.tagify_notes($('#notes-' + mark_id));
                    unmark.getData('labels', populateLabels);
                    $("section.sidebar-info-preview").fitVids();
                });
            }
        });

    };

    // Updates the label count
    unmark.update_label_count = function () {

        var label_list = $('ul.label-list');

        function updateLabelCount(res) {
            var i, labels = res.labels, count;
            for (i in labels) {
                count = labels[i].total_active_marks;
                if (count === "1") {
                    count = count + " link";
                } else if (count === "0") {
                    count = "no links";
                } else {
                    count = count + " links";
                }
                label_list.find('.label-'+labels[i].label_id + ' span').text(count);
            }
        }

        unmark.getData('labels', updateLabelCount);

        unmark.updateCounts();

    };

    // Build Mark JSON
    unmark.get_mark_info = function (mark_id) {
        var mark_data;
        unmark.ajax('/mark/info/'+mark_id, 'post', '', function(res) {
            mark_data = res.mark;
            mark_data = JSON.stringify(mark_data);

            // Once Data is retrieved, update the mark JSON
            $('#mark-data-'+mark_id).html(mark_data);
        });
    };

    // Archive & Restore Mark
    unmark.mark_archive = function (archive_link) {
        var id = archive_link.data("id");

        unmark.ajax('/mark/archive/'+id, 'post', '', function(res) {
            if(res.mark.archived_on !== null) {
                $('#mark-'+id).fadeOut();
                unmark.sidebar_collapse();
                unmark.update_label_count();
            } else {
                alert('Sorry, We could not archive this mark at this time.');
            }
        });
    };


    // Archive & Restore Mark
    unmark.mark_restore = function (archive_link) {
        var id = archive_link.data("id");

        unmark.ajax('/mark/restore/'+id, 'post', '', function(res) {
            if(res.mark.archived_on === null) {
                $('#mark-'+id).fadeOut();
                unmark.sidebar_collapse();
                unmark.update_label_count();
            } else {
                alert('Sorry, We could not restore this mark at this time.');
            }
        });
    };

    // Archive all marks over a year old
    unmark.archive_all = function () {
        unmark.ajax('/marks/archive/old', 'post', '', function (res) {
            if (res.archived === true) {
                window.location = "/marks";
            } else {
                alert('Sorry, We could not archive the links at this time. Please try again.')
            }
        });
    };

    // Handles editing of notes
    unmark.marks_editNotes = function (editField) {

        var editable = editField.next(), text, query;

        // Clean up the field, check for empty etc
        editable.unbind();
        editField.html('EDIT NOTES');
        editable.attr('contenteditable', true);
        editable.find('span.action').remove();
        if(editable.is(':empty')) {
            editable.html('Click here to edit');
        }

        // Define the actions that will save the note.
        // Includes Function to save the note
        editable.on('blur keydown', function (e) {
            if (e.which === 13 || e.type === 'blur') {
                e.preventDefault();
                text = $(this).text(), id = $(this).data('id');
                if (text === 'Click here to edit') {
                    $(this).empty().html('<span class="action" data-action="marks_clickEdit">Add a note or #hashtags ...</span>');
                } else {
                    query = 'notes=' + unmark.urlEncode(text);
                    unmark.ajax('/mark/edit/'+id, 'post', query, function(res) {
                        editField.html('Notes <i class="icon-edit"></i>');
                        editable.attr('contenteditable', false);
                        $('#mark-'+id).find('.note-placeholder').text(editable.text());
                    });
                    editable.unbind();
                    unmark.tagify_notes(editable);
                }
            }
        });
    };
    unmark.marks_clickEdit = function (btn) { btn.parent().prev().trigger('click'); };

    // Method for Adding Notes
    unmark.marks_addNotes = function (btn) {
        var editable = btn.next();
        btn.hide(); // Hide Button
        editable.fadeIn(); // Show Editable Area
        editable.focus(); // Set Focus
    };

    // Save me some notes!
    unmark.saveNotes = function (id, note) {
        var query = 'notes=' + unmark.urlEncode(note);
        unmark.ajax('/mark/edit/'+id, 'post', query);
    };

    // Method for adding a label
    unmark.marks_addLabel = function (btn) {

        var mark, label_id, query, label_name, body_class, pattern,
            labels_list = btn.next(),
            label_parent = btn.parent();

        if(labels_list.is(':visible')) { return labels_list.fadeOut(); }

        labels_list.find('a').unbind();
        labels_list.fadeIn();

        labels_list.find('a').on('click', function (e) {
            e.preventDefault();
            mark = labels_list.data('id');
            label_id = $(this).attr('rel');
            label_name = $(this).text();
            body_class = $('body').attr('class');
            pattern = new RegExp('label');
            query = 'label_id=' + label_id;
            unmark.ajax('/mark/edit/'+mark, 'post', query, function(res) {
                labels_list.fadeOut();
                btn.text(label_name);
                unmark.swapClass(btn, 'label-*', 'label-'+label_id);
                labels_list.find('a').unbind();
                unmark.update_label_count(); // Update the count under labels menu
                if (label_parent.hasClass('sidebar-label')) {
                    unmark.swapClass(label_parent, 'label-*', 'label-'+label_id);
                    unmark.swapClass($('#mark-'+mark), 'label-*', 'label-'+label_id);
                    unmark.get_mark_info(mark);
                    if ((pattern.test(body_class))  && (body_class !== 'label-'+label_id)) { // If on current label and label change, remove mark from label
                        $('#mark-'+mark).fadeOut();
                        unmark.sidebar_collapse();
                    }
                }
            });
        });

    };

    // Build a Label List
    unmark.label_list = function (res) {
        var key, labels = res.labels, obj, list = '';
        for (key in labels) {
           obj = labels[key];
           list += '<li class="label-'+ obj['label_id'] +'"><a href="#" rel="'+ obj['label_id'] +'"><span>'+ obj['name'] +'</span></a></li>';
        }
        return list;
    };

    // Reads the passed note field and tagifies it on the fly.
    unmark.tagify_notes = function (note) {

        // Get the note text, replace all tags with a linked tag
        var notetext = note.text();

        if (notetext === '') {
            notetext = '<span class="action" data-action="marks_clickEdit">Add a note or #hashtags ...</span>';
        } else {
            notetext = notetext.replace(/#(\S*)/g,'<a href="/marks/tag/$1">#$1</a>');
        }

        // Send the HTML to the notes field.
        note.html(notetext);
    };

    // Delete a Mark
    unmark.delete_mark = function (btn) {

        // Get Mark Id
        // Check View
        var mark_id = btn.data('id'),
            view = btn.data('view');

        // Request to delete the mark
        unmark.ajax('/mark/delete/'+mark_id, 'post', '', function (res) {
            if (res.mark.active === "0") {
                if (view === "bookmarklet"){
                    unmark.close_window(true);
                } else {
                    unmark.sidebar_collapse();
                    $('#mark-'+mark_id).fadeOut();
                }
            } else {
                alert('This mark could not be deleted, please try again laster.');
            }
        });
    };

    // Watch Height on each mark action button
    unmark.update_mark_action_btns = function () {
        $('.mark').each(function () {
            var height  = $(this).outerHeight(true),
                half    = height / 2;
            $(this).find('.mark-actions a').each(function () {
                $(this).height(half);
            });
        });
    };






}(window.jQuery));

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
            var label_id = $('.mark-added').data('label'),
                label_name = $('.mark-added').data('label-name');
            $('#currLabel').addClass('label-'+label_id).text(label_name);
        };


        // Grab the labels list
        unmark.getData('labels', built_label_list);

        // Show Current Label
        $(document).ready(function() {
            check_for_label();

            $('.mark-added-notes-area').on('blur keydown', function (e) {
                if (e.which === 13 || e.type === 'blur') {
                    e.preventDefault();
                    var text = $(this).val(),
                        id = $(this).data('id');
                    unmark.saveNotes(id, text);
                }
            });

        });


    });

}(window.jQuery));

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

        // Basic Page Setup
        unmark.main_panel.width(unmark.main_panel_width);
        unmark.page_setup(unmark.body_height);

        // Resize Helper for Dev Tools
        // Also for weirdo's who want to resize their browser
        $(window).on('resize',function () {
            unmark.page_setup($(window).outerHeight(true));
        });

        // Set any window variables
        window.unmark_current_page = 1;

        // Animate the body fading in
        $('body').animate({opacity: 1}, 1000);

        // Vertical Tabs
        // Shows and Tabs the Vertical navigition inside the Navigation Panel
        $('.navigation-content a, .navigation-pane-links a').on('click', function (e) {
            unmark.interact_nav(e, $(this));
        });

        // Update Mark Btns
        unmark.update_mark_action_btns();

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
            funct = eval('unmark.' + action); // Convert it to an executable function
            funct($(this)); // Run it with passed in object
            unmark.hideNavigation(); // Hide Main Navigation
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

    };

    // Get this baby in action
    $(document).ready(function(){ unmark.init(); });

}(window.jQuery));
