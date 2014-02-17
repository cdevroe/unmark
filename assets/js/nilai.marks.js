/*!
    Marks Scripts for Nilai.co
    Copyright 2014 - Plain - http://plainmade.com

    A set of functions used to pull and push data for Marks

*/

(function ($) { 

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
        template = Hogan.compile(nilai.template.sidebar);
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

    // Updates the label count
    nilai.update_label_count = function () {

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

        nilai.getData('labels', updateLabelCount);

    };

    // Build Mark JSON
    nilai.get_mark_info = function (mark_id) {
        var mark_data;
        nilai.ajax('/mark/info/'+mark_id, 'post', '', function(res) {
            mark_data = res.mark;
            mark_data = JSON.stringify(mark_data);

            // Once Data is retrieved, update the mark JSON
            $('#mark-data-'+mark_id).html(mark_data);
        });
    };

    // Archive & Restore Mark
    nilai.mark_archive = function (archive_link) {
        var id = archive_link.data("id");

        nilai.ajax('/mark/archive/'+id, 'post', '', function(res) {
            if(res.mark.archived_on !== null) {
                $('#mark-'+id).fadeOut();
                nilai.sidebar_collapse();
                nilai.update_label_count();
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

    // Archive all marks over a year old
    nilai.archive_all = function () {
        nilai.ajax('/marks/archive/old', 'post', '', function (res) {
            if (res.archived === true) {
                window.location = "/marks";
            } else {
                alert('Sorry, We could not archive the links at this time. Please try again.')
            }
        });
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
            query = 'label_id=' + nilai.urlEncode(label_id);
            nilai.ajax('/mark/edit/'+mark, 'post', query, function(res) {
                labels_list.fadeOut();
                btn.text(label_name);
                labels_list.find('a').unbind();
                labels_list.parent().find('i').removeClass('barley-icon-question-sign').addClass('barley-icon-ok');
                if (label_parent.hasClass('sidebar-label')) {
                    nilai.swapClass(label_parent, 'label-*', 'label-'+label_id);
                    nilai.swapClass($('#mark-'+mark), 'label-*', 'label-'+label_id);
                    nilai.get_mark_info(mark);
                    nilai.update_label_count(); // Update the count under labels menu
                    if ((pattern.test(body_class))  && (body_class !== 'label-'+label_id)) { // If on current label and label change, remove mark from label
                        $('#mark-'+mark).fadeOut();
                        nilai.sidebar_collapse();
                    }
                }
            });
        });

    };

    // Build a Label List
    nilai.label_list = function (res) {
        var key, labels = res.labels, obj, list = '';
        for (key in labels) {
           obj = labels[key];
           list += '<li class="label-'+ obj['label_id'] +'"><a href="#" rel="'+ obj['label_id'] +'"><span>'+ obj['name'] +'</span></a></li>';
        }
        return list;
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

    // Watch Height on each mark action button
    nilai.update_mark_action_btns = function () {
        $('.mark').each(function () {
            var height  = $(this).outerHeight(true),
                half    = height / 2;
            $(this).find('.mark-actions a').each(function () {
                $(this).height(half);
            });
        });
    };






}(window.jQuery));