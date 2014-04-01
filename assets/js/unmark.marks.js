/*!
    Marks Scripts for Unmark.it
    Copyright 2014 - Plain - http://plainmade.com

    A set of functions used to pull and push data for Marks

*/

(function ($) {

    var _labels = 0;

    // Show Mark Info in Sidebar
    // Grabs relavaent info and shows the sidebar actions with info
    unmark.show_mark_info = function (mark_clicked) {

        var template, output,
            mark_obj_ref    = mark_clicked.data('mark'),
            mark_string     = $('#' + mark_obj_ref).html(),
            mark_obj        = jQuery.parseJSON(mark_string),
            mark_id         = mark_obj_ref.replace("mark-data-",""),
            mark_notehold   = $('#mark-'+mark_id).find('.note-placeholder').text();
            mark_nofade     = mark_clicked.data('nofade');

        // Quick function to populate the tags
        function populateLabels() {
            _labels = arguments[0] || _labels;
            (! isNaN(_labels)) ? unmark.getData('labels', populateLabels) : $('ul.sidebar-label-list').prepend(unmark.label_list(_labels));
        };

        // Clean up view
        if (!mark_nofade) {
            $('.mark').removeClass('view-inactive').removeClass('view-active');
            $('.mark').not('#mark-' + mark_id).addClass('view-inactive');
            $('#mark-' + mark_id).addClass('view-active');
        }

        // Check for note placeholder and update if there.
        if (mark_notehold !== ''){ mark_obj['notes'] = mark_notehold; }

        // Render the Template
        template = Hogan.compile(unmark.template.sidebar);
        output = template.render(mark_obj);

        // Show Mobile Sidebar
        if (Modernizr.mq('only screen and (max-width: 480px)')) { $('#mobile-sidebar-show').trigger('click'); }

        // Run the view interaction
        unmark.sidebar_mark_info.fadeOut(400, function () {
            if (unmark.sidebar_default.is(':visible')) {
                unmark.sidebar_default.fadeOut(400, function () {
                    unmark.sidebar_mark_info.html(output).fadeIn(400, function () {
                        unmark.tagify_notes($('#notes-' + mark_id));
                        populateLabels();
                        $("section.sidebar-info-preview").fitVids();
                    });
                });
            } else {
                unmark.sidebar_mark_info.html(output);
                unmark.tagify_notes($('#notes-' + mark_id));
                populateLabels();
                unmark.sidebar_mark_info.fadeIn(400, function () {
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
                    count = count + " mark";
                } else if (count === "0") {
                    count = "no marks";
                } else {
                    count = count + " marks";
                }
                label_list.find('.label-'+labels[i].label_id + ' span').text(count);
            }
        }
        unmark.getData('labels', updateLabelCount);
        unmark.updateCounts();
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

        // Private function to save notes
        function saveNotes(text, id) {
            query = 'notes=' + unmark.urlEncode(text);
            unmark.ajax('/mark/edit/'+id, 'post', query, function(res) {
                setNoteTitle(1);
                $('#mark-'+id).find('.note-placeholder').text(text);
            });
        }

        // Private function to update note title
        function setNoteTitle(num) {
            switch (num) {
                case 1:
                    heading = 'Notes <i class="icon-edit"></i>';
                break;
                case 2:
                    heading = 'EDITING NOTES <i class="icon-heading_close"></i>';
                break;
                case 3:
                    heading = 'ADD A NOTE <i class="icon-edit"></i>';
                break;
            }
            editField.html(heading);
        }

        // Clean up the field, check for empty etc
        editable.unbind();
        setNoteTitle(2);
        editField.removeClass('action');
        editable.attr('contenteditable', true).addClass('editable');

        // Set Focus and Clean up Tags
        editable.find('a').contents().unwrap();
        editable.focus();

        // Define the actions that will save the note.
        // Includes Function to save the note
        editable.on('blur keydown', function (e) {
            if (e.which === 13 || e.type === 'blur') {
                e.preventDefault();
                editable.attr('contenteditable', false).removeClass('editable');
                text = $(this).text(), id = $(this).data('id');
                if (text === '') {
                    setNoteTitle(3);
                } else {
                    saveNotes(text, id);
                }
                // Set up for next edit
                editable.unbind();
                unmark.tagify_notes(editable);
                setTimeout( function() { editField.addClass('action'); }, 500);
            }
        });
    };

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
                    unmark.update_mark_info(res, mark);
                    if ((pattern.test(body_class))  && (body_class !== 'label-'+label_id)) { // If on current label and label change, remove mark from label
                        $('#mark-'+mark).fadeOut();
                        unmark.sidebar_collapse();
                    }
                }
            });
        });

    };

    // Update Mark JSON Data after Successfull label change
    unmark.update_mark_info = function (res, mark_id) {
        var mark_data = res.mark;
        mark_data = JSON.stringify(mark_data);
        $('#mark-data-'+mark_id).html(mark_data);
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
    // 4.1.14 - Also Linkify's the notes field ... matches http(s)
    unmark.tagify_notes = function (note) {

        // Get the note text, replace all tags with a linked tag
        var notetext = note.text();

        if (notetext !== '') {
            // First Linkify Notes
            notetext = notetext.replace(/(https?:\/\/[^\]\s]+)(?: ([^\]]*))?/g, "<a target='_blank' href='$1'>$1</a>");
            // Then Tagify It
            notetext = notetext.replace(/#(\S*)/g,'<a href="/marks/tag/$1">#$1</a>');
        } else {
            note.prev().html('Click To Add A Note <i class="icon-edit"></i>');
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

}(window.jQuery));
