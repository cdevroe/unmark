/*!
    Marks Scripts
*/

(function ($) {

    var _labels = 0;

    // Show Mark Info in Sidebar
    unmark.show_mark_info = function (mark_clicked) {

        var template, output,
            mark_obj_ref    = mark_clicked.data('mark'),
            mark_string     = $('#' + mark_obj_ref).html(),  // a string of metadata
            mark_obj        = jQuery.parseJSON(mark_string),
            mark_id         = mark_obj_ref.replace("mark-data-",""),
            mark_notehold   = $('#mark-'+mark_id).find('.note-placeholder').text(),
            mark_nofade     = mark_clicked.data('nofade');

        // Replace mark title with user's provided title if it exists
        var mark_title          = (mark_obj.mark_title != null) ? mark_obj.mark_title : mark_obj.title;
        mark_obj.mark_title     = mark_title;

        // Reformat tags to a csv string for mustache template
        var mark_tags_string        = '';
        var mark_tags_count         = Object.keys(mark_obj['tags']).length;
        var iterator                = 1;

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

        // Quick function to populate the tags
        function populateLabels() {
            _labels = arguments[0] || _labels;
            (! isNaN(_labels)) ? unmark.getData('labels', populateLabels) : $('ul.sidebar-label-list').prepend(unmark.label_list(_labels));

            // Enabled event listeners for adding labels
            unmark.marks_addLabel();


        };

        // Clean up view

        if (Modernizr.mq('only screen and (min-width: 768px)')) {
            $('.mark').removeClass('view-inactive').removeClass('view-active');
            $('.mark').not('#mark-' + mark_id).addClass('view-inactive');
            $('#mark-' + mark_id).addClass('view-active');
        }
        $('.sidebar-content').addClass('active');

        // Check for note placeholder and update if there.
        if (mark_notehold !== ''){ mark_obj['notes'] = mark_notehold; }

        // Render the Template
        template = Hogan.compile(unmark.template.sidebar);
        output = template.render(mark_obj);

        // Show Mobile Sidebar
        if (Modernizr.mq('only screen and (max-width: 767px)')) { $('#mobile-sidebar-show').trigger('click'); }

        // Update Sidebar contents for this bookmark
        unmark.sidebar_mark_info.html(output);

        populateLabels();

        unmark.sidebar_mark_info.fadeIn(400, function () {
            var input_title         = $('#input-title'),
                input_tags          = $('#input-tags'),
                input_notes         = $('#input-notes');

            intervalSaveTitle = setInterval(function(){
                if ( input_title.hasClass('contentsChanged') ) {

                    // Save new Title
                    unmark.saveTitle( mark_id, input_title.val() );

                    // Update visible Title in list to new Title
                    $('#mark-'+mark_id+' h2 a').html( input_title.val() );

                    // Remove the class on the input to stop updates
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

            // Getting Tags for autocomplete
            unmark.ajax('/tags/getAutocomplete', 'get', '', function (res) {

                tagList = [];

                if (res.error) { // Put error from API in console, but still load empty list
                    console.log(res.error);
                }

                if (res.tags !== false) { // If it _is_ false, it likely means user has no tags yet

                    // Create a list of autocomplete tags
                    for(i=0;i<Object.keys(res.tags).length;i++){
                        tagList.push({text: res.tags[i].name});
                    }
                }

                // Restore the value of the tags already assigned
                mark_added_tags_value = mark_tags_string.split(',');
                for (i=0;i<mark_added_tags_value.length;i++) {
                    tagList.push({text: mark_added_tags_value[i]});
                }

                ajax_loading = false;
                initializeTagInput(tagList);
            });

            function initializeTagInput(tagList) {
                input_tags.selectize({
                    plugins: ['remove_button', 'restore_on_backspace'],
                    delimiter: ',',
                    openOnFocus: false,
                    persist: false,
                    createOnBlur: true,
                    closeAfterSelect: true,
                    labelField: 'text',
                    valueField: 'text',
                    searchField: 'text',
                    options: tagList,
                    create: function(input) {
                        return {
                            value: input,
                            text: input
                        }
                    },
                    onChange: function(input) {
                        unmark.saveTags( mark_id, input );
                        setTimeout(unmark.update_tag_count,1000); // Delayed slightly, otherwise 404 (unsure why)
                    }
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
                // Removed in 2.0
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
    };

    unmark.update_tag_count = function () {
        var tag_list = $('ul.tag-list');
        function updateTagCount(res) {
            var i, tags = res.tags.popular, list = '';
            for (i in tags) {
                list += '<li class="tag-' + tags[i].tag_id + '"><a href="/marks/tag/' + tags[i].slug + '">#' + tags[i].name + '</a><span>' + tags[i].total + '</span></li>';
            }
            tag_list.html(list);
        }
        unmark.getData('tags', updateTagCount);
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
        //removed 2.0 var editable_mark_title = $('#mark-'+$(editable_notes).data('id')+' h2'); // 1.6 The title of the current mark to make editable
        var id = $(editable_notes).data('id');

        // Private function to save notes
        function saveMarkInfo(title, notes, tags, id) {

            // Cannot submit an empty title
            if (title === '') {
                return;
            }

            // Note was empty, set accordingly
            if (notes === '') {
                //setNoteHeading(3);
            }

            query = 'title=' + unmark.urlEncode(title) + '&notes=' + unmark.urlEncode(notes) + '&tags='+unmark.urlEncode(tags);
            unmark.ajax('/mark/edit/'+id, 'post', query, function(res) {
                $('#mark-'+id).find('.note-placeholder').text(notes);
            });
        }

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
                    //remove 2.0 editable_mark_title.removeClass('contentsChanged');
                }
            }
        }

        // If the contents change, add a class
        editable_notes.on('keydown',function(e){
            $(this).addClass('contentsChanged');
        });

        // If we leave either field, fire function
        editable_notes.on('blur', editableActions);
    };

    // save title only, can't be blank
    unmark.saveTitle = function (id, title) {
        if ( title == '' ) return;
        var query = 'title=' + unmark.urlEncode(title);
        unmark.ajax('/mark/edit/'+id, 'post', query, function(res) {
            unmark.update_mark_info(res, id); // Update the notes and everything else too.
        });
    };

    // Save notes, can be blank
    unmark.saveNotes = function (id, note) {
        var query = 'notes=' + unmark.urlEncode(note);
        unmark.ajax('/mark/edit/'+id, 'post', query, function(res) {
            unmark.update_mark_info(res, id); // Update the notes and everything else too.
        });
    };

    // Save tags, can be blank
    unmark.saveTags = function (id, tags) {

        if ( tags == '' ) { // Remove all tags
            tags = 'unmark:removeAllTags';
        }

        var query = 'tags=' + unmark.urlEncode(tags);
        unmark.ajax('/mark/edit/'+id, 'post', query, function(res) {
            unmark.update_mark_info(res, id); // Update the notes and everything else too.
        });
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
