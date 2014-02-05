/*!
    Main scripts for Nilai.co
    Copyright 2014 - Plain - http://plainmade.com
*/

if (nilai === undefined) { var nilai = {}; }

(function ($) {

    // Basic Ajax Function used throughout the app
    nilai.ajax = function (path, method, query, callback, data_type, async) {
        var csrf_token   = nilai.urlEncode(nilai.vars.csrf_token),
            data_type    = (data_type !== undefined) ? data_type : 'json',
            async        = (async !== undefined) ? async : true,
            added_vars   = 'csrf_token=' + csrf_token + '&content_type=' + data_type;
            query        = (nilai.empty(query)) ? added_vars : query + '&' + added_vars;

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

    // Replace special chars
    nilai.replaceSpecial = function(str) {
        if (str !== undefined && str !== null) {
            var regex = null;
            for (var i in nilai.special_chars) {
                regex = new RegExp(i, 'gi');
                str   = str.replace(regex, nilai.special_chars[i]);
            }
        }
        return str;
    };

    // Encode for URL
    nilai.urlEncode = function(str) {
        str = nilai.replaceSpecial(str);
        return encodeURIComponent(str);
    };

    // Nice Check Empty Function
    nilai.empty = function(v) {
        var l = (v !== undefined && v !== null) ? v.length : 0;
        return (v === false || v === '' || v === null || v === 0 || v === undefined || l < 1);
    }; 

    // Show Mark Info in Sidebar
    // Grabs relavaent info and shows the sidebar actions with info
    nilai.show_mark_info = function (mark_clicked) {
        
        var template, output,
            mark_obj_ref    = mark_clicked.data('mark'),
            mark_string     = $('#' + mark_obj_ref).html();
            mark_obj        = jQuery.parseJSON(mark_string),
            mark_id         = mark_obj_ref.replace("mark-data-","");

        // Quick function to populate the tags
        function showTags(res) {
            var list = nilai.label_list(res);
            $('ul.sidebar-label-list').prepend(list);
        };

        // Clean up view
        $('.mark').removeClass('view-inactive').removeClass('view-active');
        $('.mark').not('#mark-' + mark_id).addClass('view-inactive');
        $('#mark-' + mark_id).addClass('view-active');

        // Compile and Render the template
        template = Hogan.compile(nilai.sidebar_template);
        output = template.render(mark_obj);

        nilai.sidebar_mark_info.fadeOut(400, function () {
            if (nilai.sidebar_default.is(':visible')) {
                nilai.sidebar_default.fadeOut(400, function () {
                    nilai.sidebar_mark_info.html(output).fadeIn(400, function () {
                        nilai.tagify_notes($('#notes-' + mark_id));
                        nilai.getData('labels', showTags);
                    });
                });
            } else {
                nilai.sidebar_mark_info.html(output).fadeIn(400, function () {
                    nilai.tagify_notes($('#notes-' + mark_id));
                    nilai.getData('labels', showTags);
                });         
            }
        });

    };

    // Collapse Marks Info Sidebar
    // Hides the marks info and re-displays the default sidebar
    nilai.sidebar_collapse = function () {
        $('.mark').removeClass('view-inactive').removeClass('view-active');
        nilai.sidebar_mark_info.fadeOut(400, function () {
            nilai.sidebar_default.fadeIn(400);
        });
    };

    // Archive & Restore Mark
    nilai.mark_archive = function (archive_link) {
        var id = archive_link.data("id");

        nilai.ajax('/mark/archive/'+id, 'post', '', function(res) {
            if(res.mark.archived_on !== null) {
                $('#mark-'+id).fadeOut();
                nilai.sidebar_collapse();
            } else {
                alert('Sorry, We could not archive this mark at this time.');
            }
        });

    };

    // Archive & Restore Mark
    nilai.mark_restore = function (archive_link) {
        var id = archive_link.data("id");

        nilai.ajax('/mark/restore/'+id, 'post', '', function(res) {
            if(res.mark.archived_on === null) {
                $('#mark-'+id).fadeOut();
                nilai.sidebar_collapse();
            } else {
                alert('Sorry, We could not restore this mark at this time.');
            }
        });

    };

    // Logout Method
    nilai.logout = function () {
        window.location = "/logout";
    };

    // Function for interacting and animating the left navigation
    // This handels both the top level and secondarly level
    nilai.interact_nav = function (e, elem_ckd) {
        // Set variables
        var panel_to_show = elem_ckd.attr('href'),
            panel_name = panel_to_show.replace(/^#/, ''),
            panel_width = parseInt(elem_ckd.attr('rel')),
            panel_animate = panel_width + 65,
            panel_position = parseInt(nilai.nav_panel.css('left'));

        // If all links pannel - allow click default
        if (panel_to_show.match(/\//)) { return true; }

        // Otherwise prevent click default
        e.preventDefault();

        // Add / Remove Class for current navigation
        $('.menu-item').removeClass('active-menu');
        $('.navigation-content').find("[data-menu='" + panel_name + "']").addClass('active-menu');

        // Check for close action on and open panel
        if (panel_to_show === "#panel-menu") {
            if (panel_position > 0) {
                nilai.nav_panel.animate({ left: -285 }, { duration: 200, queue: false });
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
    };

    // Handles editing of notes
    nilai.marks_editNotes = function (editField) {

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
                query = 'notes=' + nilai.urlEncode(text);
                nilai.ajax('/mark/edit/'+id, 'post', query, function(res) {
                    editField.html('Notes <i class="barley-icon-pencil"></i>');
                    editable.attr('contenteditable', false);
                });
                editable.unbind();
                nilai.tagify_notes(editable);
            }
        });
    };
    nilai.marks_clickEdit = function (btn) { btn.parent().prev().trigger('click'); };

    // Method for Adding Notes
    nilai.marks_addNotes = function (btn) {

        var editable = btn.next(), text, query;

        if(editable.is(':visible')) { return editable.slideUp(); }

        editable.unbind();
        editable.slideDown();
        editable.attr('contenteditable', true);
        if(editable.is(':empty')) {
            editable.html('Type note text here...');
        }

        // Define the actions that will save the note.
        // Includes Function to save the note
        editable.on('blur keydown', function (e) { 
            if (e.which === 13 || e.type === 'blur') {
                e.preventDefault();
                text = $(this).text(), id = $(this).data('id');
                query = 'notes=' + nilai.urlEncode(text);
                nilai.ajax('/mark/edit/'+id, 'post', query, function(res) {
                    editable.attr('contenteditable', false);
                    editable.slideUp();
                    editable.prev().text('Edit Note');
                    editable.parent().find('i').removeClass('barley-icon-question-sign').addClass('barley-icon-ok');
                });
                editable.unbind();
            }
        });
    };

    // Method for adding a label
    nilai.marks_addLabel = function (btn) {
        
        var mark, label_id, query, label_name,
            labels_list = btn.next(),
            label_parent = btn.parent();

        if(labels_list.is(':visible')) { return labels_list.slideUp(); }

        labels_list.find('a').unbind();
        labels_list.slideDown();

        labels_list.find('a').on('click', function () {
            mark = labels_list.data('id');
            label_id = $(this).attr('rel');
            label_name = $(this).text();
            query = 'label_id=' + nilai.urlEncode(label_id);
            nilai.ajax('/mark/edit/'+mark, 'post', query, function(res) {
                labels_list.slideUp();
                btn.text(label_name);
                labels_list.find('a').unbind();
                labels_list.parent().find('i').removeClass('barley-icon-question-sign').addClass('barley-icon-ok');
                if (label_parent.hasClass('sidebar-label')) {
                    label_parent.removeClass();
                    label_parent.addClass('sidebar-label').addClass('label-' + label_id);
                }
            });
        });

    };

    // Build a Label List
    nilai.label_list = function (res) {
        var key, labels = res.labels, obj, list = '';
        for (key in labels) {
           obj = labels[key];
           list += '<li class="label-'+ obj['label_id'] +'"><a href="#" rel="'+ obj['label_id'] +'">'+ obj['name'] +'</a></li>';
        }
        return list;
    };

    // Simple Ajax method to get a list of results from API
    nilai.getData = function (what, caller) {
        nilai.ajax('/marks/get/'+what, 'post', '', caller);
    };

    // Reads the passed note field and tagifies it on the fly.
    nilai.tagify_notes = function (note) {

        // Get the note text, replace all tags with a linked tag
        var notetext = note.text();
        notetext = notetext.replace(/#(\S*)/g,'<a href="/marks/tag/$1">#$1</a>');
        notetext = notetext.replace('Add a note or #hashtags ...', '<span class="action" data-action="marks_clickEdit">Add a note or #hashtags ...</span>');

        // Send the HTML to the notes field.
        note.html(notetext);
    };

    // Simple Close Window
    nilai.close_window = function () { window.close(); };

    // Delete a Mark
    nilai.delete_mark = function (btn) {

        // Get Mark Id
        // Check View
        var mark_id = btn.data('id'),
            view = btn.data('view');

        // Request to delete the mark
        nilai.ajax('/mark/delete/'+mark_id, 'post', '', function (res) {
            if (res.mark.active === "0") {
                if (view === "bookmarklet"){
                    nilai.close_window();
                } else {
                    nilai.sidebar_collapse();
                    $('#mark-'+mark_id).fadeOut();
                }
            } else {
                alert('This mark could not be deleted, please try again laster.');
            }
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
        this.body_height = $('body').height(),
        this.special_chars     = { '\\+': '&#43;' };

        // Basic Page Setup
        nilai.main_panel.width(nilai.main_panel_width);
        nilai.main_content.height(nilai.body_height);
        nilai.sidebar_content.height(nilai.body_height);
        $('.nav-panel').height(nilai.body_height);
        $('body').height(nilai.body_height);

        // Hide then quickly fade in Body
        $('body').animate({opacity: 1}, 1000);

        // Vertical Tabs
        // Shows and Tabs the Vertical navigition inside the Navigation Panel
        $('.navigation-content a, .navigation-pane-links a').on('click', function (e) {
            nilai.interact_nav(e, $(this));
        });

        // Hover Action on Marks List
        // Shows the Archive and More Buttons
        $(".mark").hover(function () {
            $(this).addClass('hide-dot');
            $(this).find('.mark-actions').show();
        }, function () {
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
        });

        // Watch Height on each mark action button
        $('.mark').each(function () {
            var height  = $(this).outerHeight(true),
                half    = height / 2;
            $(this).find('.mark-actions a').each(function () {
                $(this).height(half);
            });
        });

    };

    // Get this baby in action
    $(document).ready(function(){ nilai.init(); });

}(window.jQuery));