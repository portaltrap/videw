(function($) {
    "use strict";

    $(document).ready(function() {
        const update_btn     = $('#update-btn');
        const update_dom     = $('#update-dom');
        const current_action = $('#current-action');
        const progress_bar   = $('#progress-bar');

        function setProgress(prog) {
            progress_bar.attr('aria-valuenow', prog);
            progress_bar.css('width', prog + '%');
            progress_bar.html(prog + '%');
        }

        function setAction(text) {
            current_action.html('<i class="fas fa-circle-notch mr-1 fa-spin"></i> ' + text);
        }

        function updateProcess(e) {
            e.preventDefault();
            update_dom.removeClass('d-none');
            setAction('Initializing Update...')
            setProgress(10);

            $.get(base + updates + '/ajax_extract_package', function(res) {
                res = JSON.parse(res);
                if(res.success) {
                    setAction('Extracting Package...');
                    setProgress(40);
                    $.get(base + updates + '/ajax_import_database', function(res) {
                        res = JSON.parse(res);
                        setAction('Database Settings...');
                        setProgress(80);
                        if(res.success) {
                            $.get(base + updates + '/ajax_finalize_settings', function(res) {
                                setAction('Update Complete!');
                                setProgress(100);
                                window.location.reload();
                            })
                        }
                    });
                }
            });
        }

        update_btn.on('click', updateProcess);
    })
})(jQuery);