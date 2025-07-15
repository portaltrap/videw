(function($) {

    $(document).ready(function() {
        const timer    = $('#timer');
        const show_img = $('#show-img');
        const popup    = $('#img-popup');
        const hidden   = $('#hidden-img'); 
        const wait     = $('#img-wait'); 

        if(timer) {
            let time = parseInt(timer.text());

            let interval = setInterval(function() {
                time -= 1;
                if(time < 1) {
                    wait.addClass('d-none');
                    show_img.removeClass('d-none');
                    show_img.attr('disabled', false);
    
                    clearInterval(interval);
                }
    
                timer.text(time);
            }, 1000);
        }

        show_img.on('click', function() {
            popup.addClass('d-none');
            hidden.removeClass('d-none');
        });
    });

})(jQuery);