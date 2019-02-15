/*!
    Add Mark
*/

(function ($) {

    // Wait for Page to load
    $(document).ready(function () {

        // Gets an HTML list of data and prepends the list
        // Run as a callback for the getData function below
        function built_label_list(res) {
            var list = unmark.label_list(res);
            $('ul.label-choices').prepend(list);
            unmark.marks_addLabel();
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
        check_for_label();

        $('.mark-added-notes-area').on('blur keydown', function (e) {
            if (e.which === 13 || e.type === 'blur') {
                e.preventDefault();
                var text =    $(this).val(),
                    id =      $(this).data('id'),
                    title =   $('.mark-added-info h1').text(); // 1.6
                unmark.saveNotes(id, text, title);
            }
        });

    });

}(window.jQuery));
