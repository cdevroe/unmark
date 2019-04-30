/*!
    Marks Scripts
*/

(function ($) {

    var _labels = 0;

    // Show Mark Info in Sidebar
    // Grabs relavaent info and shows the sidebar actions with info
    unmark.show_mark_info = function (mark_clicked) {

        var template, output,
            mark_obj_ref    = mark_clicked.data('mark'),
            mark_string     = $('#' + mark_obj_ref).html();
            //console.log(mark_string);
            var mark_obj        = jQuery.parseJSON(mark_string);

            var mark_id         = mark_obj_ref.replace("mark-data-",""),
            mark_notehold   = $('#mark-'+mark_id).find('.note-placeholder').text();
            mark_nofade     = mark_clicked.data('nofade');

            // Reformat tags to a csv string for mustache template
            var mark_tags_string = '';
            var mark_tags_count = Object.keys(mark_obj['tags']).length;
            var iterator = 1;

            for ( var tag in mark_obj['tags'] ) {
                if ( tag === undefined ) {
                    continue;
                }
                if ( iterator == mark_tags_count ){
                    mark_tags_string += tag.toString();
                } else {
                    mark_tags_string += tag.toString() + ',';
                }
                iterator++;
            }

            mark_obj['tags_string'] = mark_tags_string;

        // 1.6
        // If the mark is clicked on is currently being edited,
        // do nothing
        var editable_mark_title = $('#mark-'+mark_id+' h2');
        if (editable_mark_title.hasClass('editable')) return;

        // However, if it isn't the current mark it should 'kill all -9' the rest of the editing stuff
        $('[id^=mark-] h2').attr('contenteditable',false).removeClass('editable');

        // Quick function to populate the tags
        function populateLabels() {
            _labels = arguments[0] || _labels;
            (! isNaN(_labels)) ? unmark.getData('labels', populateLabels) : $('ul.sidebar-label-list').prepend(unmark.label_list(_labels));

            unmark.marks_addLabel();
        };

        // Clean up view

      //  if (!mark_nofade) {
        if (Modernizr.mq('only screen and (min-width: 768px)')) {
            $('.mark').removeClass('view-inactive').removeClass('view-active');
            $('.mark').not('#mark-' + mark_id).addClass('view-inactive');
            $('#mark-' + mark_id).addClass('view-active');
        }
            $('.sidebar-content').addClass('active');
        //}

        /*$('.mark').removeClass('view-inactive').removeClass('view-active');
        $('.mark').not('#mark-' + mark_id).addClass('view-inactive');
        $('#mark-' + mark_id).addClass('view-active');*/

        // Check for note placeholder and update if there.
        if (mark_notehold !== ''){ mark_obj['notes'] = mark_notehold; }

        // Render the Template
        template = Hogan.compile(unmark.template.sidebar);
        output = template.render(mark_obj);

        // Show Mobile Sidebar
        if (Modernizr.mq('only screen and (max-width: 480px)')) { $('#mobile-sidebar-show').trigger('click'); }

        // Update Sidebar contents for this bookmark
        unmark.sidebar_mark_info.html(output);
        
        populateLabels();

        unmark.sidebar_mark_info.fadeIn(400, function () {
            var input_title         = $('#input-title'),
                input_tags          = $('#input-tags'),
                input_notes         = $('#input-notes');

            intervalSaveTitle = setInterval(function(){
                if ( input_title.hasClass('contentsChanged') ) {
                    unmark.saveTitle( mark_id, input_title.val() );
                    input_title.removeClass('contentsChanged');
                }
            },1000);

            intervalSaveNotes = setInterval(function(){
                if ( input_notes.hasClass('contentsChanged') ) {
                    unmark.saveNotes( mark_id, input_notes.val() );
                    input_notes.removeClass('contentsChanged');
                }
            },1000);

            input_title.on('keyup', function(e){
                if ( !input_title.hasClass('contentsChanged') ) {
                    input_title.addClass('contentsChanged');
                }
            });

            input_notes.on('keyup', function(e){
                if ( !input_notes.hasClass('contentsChanged') ) {
                    input_notes.addClass('contentsChanged');
                }
            });

            // Initialize tags
            input_tags.selectize({
                plugins: ['remove_button', 'restore_on_backspace'],
                delimiter: ',',
                persist: false,
                create: function(input) {
                    return {
                        value: input,
                        text: input
                    }
                },
                onChange: function(input) {
                    //console.log( 'tags: ' + input );
                    unmark.saveTags( mark_id, input);
                    unmark.update_tag_count();
                }
            });


        });
    };

    // Updates the label count
    unmark.update_label_count = function () {
        var label_list = $('ul.label-list');
        function updateLabelCount(res) {
            var i, labels = res.labels, count;
            for (i in labels) {
                count = labels[i].total_active_marks;
                // if (count === "1") {
                //     count = count + " mark";
                // } else if (count === "0") {
                //     count = "no marks";
                // } else {
                //     count = count + " marks";
                // }
                label_list.find('.label-'+labels[i].label_id + ' span').text(count);
            }
        }
        unmark.getData('labels', updateLabelCount);
        // Removed 1.9.2 unmark.updateCounts();
    };

    unmark.update_tag_count = function () {
        //console.log('updating tag count');
        var tag_list = $('ul.tag-list');
        function updateTagCount(res) {
            //console.log(res);
            var i, tags = res.tags.popular, count;
            for (i in tags) {
                console.log(tags[i].name + ' - ' + tags[i].total);
                count = tags[i].total;
                // if (count === "1") {
                //     count = count + " mark";
                // } else if (count === "0") {
                //     count = "no marks";
                // } else {
                //     count = count + " marks";
                // }
                tag_list.find('.tag-'+tags[i].tag_id + ' span').text(count);
            }
        }
        unmark.getData('tags', updateTagCount);
        // Removed 1.9.2 unmark.updateCounts();
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

    // Handles editing of Mark information (title, notes)
    unmark.marks_editMarkInfo = function (editField) {

        var editable_notes = editField.next(), notes, query;
        var editable_mark_title = $('#mark-'+$(editable_notes).data('id')+' h2'); // 1.6 The title of the current mark to make editable
        var id = $(editable_notes).data('id');

        // Private function to save notes
        function saveMarkInfo(title, notes, tags, id) {

            // 1.6
            // Cannot submit an empty title
            if (title === '') {
                return;
            }

            // 1.6
            // Note was empty, set accordingly
            if (notes === '') {
                //setNoteHeading(3);
            }

            query = 'title=' + unmark.urlEncode(title) + '&notes=' + unmark.urlEncode(notes) + '&tags='+unmark.urlEncode(tags);
            unmark.ajax('/mark/edit/'+id, 'post', query, function(res) {
                $('#mark-'+id).find('.note-placeholder').text(notes);
            });
        }

        // Private function to update note title
        function setNoteHeading(num) {
            switch (num) {
                case 1:
                    heading = 'Notes (click to edit)<i class="icon-edit"></i>';
                break;
                case 2:
                    heading = 'Editing Mark Info <i class="icon-heading_close"></i>';
                break;
                case 3:
                    heading = 'Edit Note/Mark Info<i class="icon-edit"></i>';
                break;
            }
            editField.html(heading);
        }

        editable_notes.unbind();
        editable_mark_title.unbind();

        // 1.6
        // Strip the Mark Title of the A element. Easier to edit
        // We will add the A back after editing is turned off

        // Make Title and Notes editable

        editable_mark_title.attr('contenteditable', true).addClass('editable');
        editable_mark_title.find('a').contents().unwrap();

        editable_notes.attr('contenteditable', true).addClass('editable');
        editable_notes.find('a').contents().unwrap();

        // Focus notes field for easy editing
        editable_notes.focus();

        // 1.6
        // Change Heading, add quitEdit action (see below)
        // This will make it so that people have to click the X
        // to stop editing
        setNoteHeading(2);
        editField.unbind();
        editField.attr('data-action','marks_quitEdit');
        editField.data('action','marks_quitEdit');

        // Define the actions that will save the note.
        // Includes Function to save the note
        // Fires on blur of either title or notes
        function editableActions(e) {
            e.preventDefault();
            if (e.which === 13 || e.type === 'blur') {

                // Check to see if there is any reason
                // to send data to API
                if (editable_notes.hasClass('contentsChanged') || editable_mark_title.hasClass('contentsChanged')) {

                    // Save changes
                    saveMarkInfo(editable_mark_title.text(), editable_notes.text(), id);

                    // Remove classes
                    editable_notes.removeClass('contentsChanged');
                    editable_mark_title.removeClass('contentsChanged');
                }
            }
        }

        // If the contents change, add a class
        editable_notes.on('keydown',function(e){
            $(this).addClass('contentsChanged');
        });
        editable_mark_title.on('keydown',function(e){
            $(this).addClass('contentsChanged');
        });

        // If we leave either field, fire function
        editable_notes.on('blur', editableActions);
        editable_mark_title.on('blur', editableActions);
    };

    // Method for Adding Notes
    unmark.marks_addNotes = function (btn) {
        var editable = btn.next();
        btn.hide(); // Hide Button

        // 1.6
        // Make the title of the mark also editable. Nah mean?
        var editable_mark_title = $('.mark-added-info h1');
        editable_mark_title.attr('contenteditable',true).addClass('editable');

        editable.fadeIn(); // Show Editable Area
        editable.focus(); // Set Focus
    };

    // save title only, can't be blank
    unmark.saveTitle = function (id, title) {
        if ( title == '' ) return;
        var query = 'title=' + unmark.urlEncode(title);
        unmark.ajax('/mark/edit/'+id, 'post', query);
    };

    // Save notes, can be blank
    unmark.saveNotes = function (id, note) {
        var query = 'notes=' + unmark.urlEncode(note);
        unmark.ajax('/mark/edit/'+id, 'post', query);
    };

    // Save tags, can be blank
    unmark.saveTags = function (id, tags) {

        if ( tags == '' ) { // Remove all tags
            tags = 'unmark:removeAllTags';
        }
        
        var query = 'tags=' + unmark.urlEncode(tags);
        unmark.ajax('/mark/edit/'+id, 'post', query);
    };

    // Method for adding a label
    unmark.marks_addLabel = function () {
        var mark, label_id, query, label_name, body_class, pattern, btn,
            labels_list =     $('.sidebar-label-list'),
            label_chosen =    $('#label-chosen'),
            label_parent =    $('.sidebar-label'),
            bookmarklet =     false; // If we're in the bookmarklet

        // In the sidebar, there is a different class for this list.
        // In the bookmarklet popup, it is .label-choices.
        // So this check was added in 1.8
        // Should likely be cleaned up.
        if ( labels_list.length < 1 ) {
          labels_list =     $('.label-choices');
          label_parent =    $('.mark-added-label');
          // Very likely in the bookmarklet added 1.8
          bookmarklet =     true;
        }

        labels_list.find('a').unbind();

        labels_list.find('a').on('click', function (e) {
            e.preventDefault();
            mark =            labels_list.data('id');
            label_id =        $(this).attr('rel');
            label_name =      $(this).text();
            body_class =      $('body').attr('class');
            pattern =         new RegExp('label');
            query =           'label_id=' + label_id;
            btn =             $('.action[data-id="'+mark+'"][data-action="marks_addLabel"]');
            unmark.ajax('/mark/edit/'+mark, 'post', query, function(res) {

                label_chosen.text(label_name);

                if ( bookmarklet ) { // If in bookmarklet, added 1.8
                  $('#currLabel').text(label_name);
                  unmark.swapClass($('#currLabel'),'label-*','label-'+label_id);
                  unmark.swapClass(label_parent, 'label-*', 'label-'+label_id);
                } else {
                  btn.text(label_name);
                  unmark.swapClass(btn, 'label-*', 'label-'+label_id);
                  unmark.swapClass(label_parent, 'label-*', 'label-'+label_id);
                  unmark.swapClass($('#mark-'+mark), 'label-*', 'label-'+label_id);
                }

                unmark.update_label_count(); // Update the count under labels menu

                unmark.update_mark_info(res, mark); // Update the notes and everything else too.

                if ((pattern.test(body_class))  && (body_class !== 'label-'+label_id)) { // If on current label and label change, remove mark from label
                    $('#mark-'+mark).fadeOut();
                    unmark.sidebar_collapse();
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

    // 1.6
    // Exit edit mode for editing mark info
    unmark.marks_quitEdit = function (editField) {

        // If the Edit field is currently in "Edit" mode, then
        // We can close. If not, don't.
        if ( editField.html() == 'Editing Mark Info <i class="icon-heading_close"></i>' ) {

            var editable_notes = editField.next(), notes, query;
            var id = $(editable_notes).data('id');
            var editable_mark_title = $('#mark-'+id+' h2');
            var mark_url = $('#mark-'+id+' .mark-link a').attr('href');

            // Turn off editability
            editable_notes.attr('contenteditable', false).removeClass('editable');
            editable_mark_title.attr('contenteditable', false).removeClass('editable');

            // Return Mark title back to being wrapped by URL
            editable_mark_title.html('<a target="_blank" rel="noopener noreferrer" href="'+mark_url+'">'+editable_mark_title.text()+'</a>');

            // Taggify the notes (means also wrapping up any links within)
            unmark.tagify_notes(editable_notes);

            // Set up for next edit
            editField.unbind();
            editable_notes.unbind();
            editable_mark_title.unbind();

            // Return previous action of marks_editMarkInfo
            editField.html('Notes (click to edit)<i class="icon-edit"></i>');
            editField.attr('data-action','marks_editMarkInfo');
            editField.data('action','marks_editMarkInfo');

            setTimeout( function() { editField.addClass('action'); }, 500);
        } else {
            return;
        }

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
            note.prev().html('Click To Add A Note or Edit Mark <i class="icon-edit"></i>');
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
