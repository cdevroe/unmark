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
        function showTags(res) {
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
                        unmark.getData('labels', showTags);
                    });
                });
            } else {
                unmark.sidebar_mark_info.html(output).fadeIn(400, function () {
                    unmark.tagify_notes($('#notes-' + mark_id));
                    unmark.getData('labels', showTags);
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
                query = 'notes=' + unmark.urlEncode(text);
                unmark.ajax('/mark/edit/'+id, 'post', query, function(res) {
                    editable.attr('contenteditable', false);
                    editable.slideUp();
                    editable.prev().text('Edit Note');
                });
                editable.unbind();
            }
        });
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
            query = 'label_id=' + unmark.urlEncode(label_id);
            unmark.ajax('/mark/edit/'+mark, 'post', query, function(res) {
                labels_list.fadeOut();
                btn.text(label_name);
                unmark.swapClass(btn, 'label-*', 'label-'+label_id);
                labels_list.find('a').unbind();
                if (label_parent.hasClass('sidebar-label')) {
                    unmark.swapClass(label_parent, 'label-*', 'label-'+label_id);
                    unmark.swapClass($('#mark-'+mark), 'label-*', 'label-'+label_id);
                    unmark.get_mark_info(mark);
                    unmark.update_label_count(); // Update the count under labels menu
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
                    unmark.close_window();
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