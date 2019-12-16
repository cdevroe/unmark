/*!
    Add Mark
*/

(function ($) {

    // Wait for Page to load
    $(document).ready(function () {

        var current_label       = $('#currLabel'),
            mark_added          = $('.mark-added'),
            mark_added_notes    = $('.mark-added-notes-area'),
            mark_added_tags     = $('.mark-added-tags-area'),
            ul_label_choices    = $('ul.label-choices');

        // Gets an HTML list of data and prepends the list
        // Run as a callback for the getData function below
        function built_label_list(res) {
            var list            = unmark.label_list(res);
            ul_label_choices.prepend(list);
            unmark.marks_addLabel();
        };

        // Function to check the current label for the mark saved
        function check_for_label() {
            var label_id        = mark_added.data('label'),
                label_name      = mark_added.data('label-name');

            current_label.addClass('label-'+label_id).text(label_name);
        };

        // Grab the labels list
        unmark.getData('labels', built_label_list);

        // Show Current Label
        check_for_label();

        mark_added_notes.on('blur keydown', function (e) {
            if (e.which === 13 || e.type === 'blur') {
                e.preventDefault();
                var text =    $(this).val(),
                    id =      $(this).data('id'),
                    title =   $('.mark-added-info h1').text(); // 1.6
                unmark.saveNotes(id, text, title);
            }
        });

        // Initialize tags
        mark_added_tags.selectize({
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
                unmark.saveTags( mark_added_tags.data('mark-id'), input );
            }
        });

    });

}(window.jQuery));
