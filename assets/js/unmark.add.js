/*!
    Add Mark
*/

(function ($) {

    // Wait for Page to load
    $(document).ready(function () {

        var current_label           = $('#currLabel'),
            quick_tags              = $('.quick-tag'),
            mark_added              = $('.mark-added'),
            mark_added_notes        = $('.mark-added-notes-area'),
            mark_added_tags         = $('.mark-added-tags-area'),
            ul_label_choices        = $('ul.label-choices');

        // Gets an HTML list of data and prepends the list
        // Run as a callback for the getData function below
        function built_label_list(res) {
            var list            = unmark.label_list(res);
            ul_label_choices.prepend(list);
            
            // enable label updating - adds event listeners
            unmark.marks_addLabel();
        };

        // Function to check the current label for the mark saved
        function check_for_label() {
            var label_id        = mark_added.data('label'),
                label_name      = mark_added.data('label-name');

            current_label.addClass('label-'+label_id).text(label_name);
        };

        // Show Current Label
        check_for_label();

        // Getting Tags for autocomplete _after_ labels
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
            mark_added_tags_value = mark_added_tags.val().split(',');
            for (i=0;i<mark_added_tags_value.length;i++) {
                tagList.push({text: mark_added_tags_value[i]});
            }

            ajax_loading = false;
            initializeSelect(tagList);
            
            // Grab the labels list
            unmark.getData('labels', built_label_list);
        });

        // When typing in notes field, progressively save
        mark_added_notes.on('blur keydown', function (e) {
            if (e.which === 13 || e.type === 'blur') {
                e.preventDefault();
                var text =    $(this).val(),
                    id =      $(this).data('id'),
                    title =   $('.mark-added-info h1').text(); // 1.6
                unmark.saveNotes(id, text, title);
            }
        });

        // Runs after built_label_list and after server returns list of tags
        function initializeSelect(tagList) {
    
            // Initialize tags, provide anon functions for create and change
            mark_added_tags.selectize({
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
                    unmark.saveTags( mark_added_tags.data('mark-id'), input );
                }
            });
        }

        // When a most-used and recently-used tag is clicked, add to tag list
        quick_tags.on('click', function(e) {

            var selectize = mark_added_tags[0].selectize;
                
            selectize.createItem($(this).html().replace('#',''))
            selectize.focus();
        });
        

    });

}(window.jQuery));
