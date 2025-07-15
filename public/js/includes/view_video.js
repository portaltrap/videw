(function($) {
    "use strict";

    $(document).ready(function() {
        const url = window.location.href;

        // Share Event Handlers
        function fbShare() { window.open('https://www.facebook.com/sharer/sharer.php?u='        + url, 'window', 'toolbar=no, menubar=no, resizable=yes, width=680, height=535'); }
        function twShare() { window.open('https://twitter.com/intent/tweet?url='                + url, 'window', 'toolbar=no, menubar=no, resizable=yes, width=680, height=535'); }
        function ldShare() { window.open('https://www.linkedin.com/sharing/share-offsite/?url=' + url, 'window', 'toolbar=no, menubar=no, resizable=yes, width=680, height=535'); }
        function vkShare() { window.open('http://vk.com/share.php?url='                         + url, 'window', 'toolbar=no, menubar=no, resizable=yes, width=680, height=535'); }
        function rdShare() { window.open('https://reddit.com/submit?url='                       + url, 'window', 'toolbar=no, menubar=no, resizable=yes, width=680, height=535'); }
        function wpShare() { window.open('https://wa.me/?text=' + encodeURI('Check out this image: ' + url), 'window', 'toolbar=no, menubar=no, resizable=yes, width=680, height=535'); }
        
        // Share Events
        $('#fb-share').on('click', fbShare);
        $('#tw-share').on('click', twShare);
        $('#ld-share').on('click', ldShare);
        $('#vk-share').on('click', vkShare);
        $('#rd-share').on('click', rdShare);
        $('#wp-share').on('click', wpShare);

        // Utility Function to Copy Text to Clipboard
        function copy_to_clipboard(str) {
            const el = document.createElement('textarea');
            el.value = str;
            el.setAttribute('readonly', '');
            el.style.position = 'absolute';
            el.style.left = '-9999px';
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
        };
    
        // Copy Buttons
        const direct_copy = $('#direct-copy');
        const html_copy   = $('#html-copy');
        const bbcode_copy = $('#bbcode-copy');

        // Copy Event Handler
        function copy() { copy_to_clipboard($(this).data('copy')); }

        // Register Events on Copy Buttons
        direct_copy.on('click', copy);
        html_copy  .on('click', copy);
        bbcode_copy.on('click', copy);
    
        // Open Image in a Lightbox.
        const trigger = $('#trigger-lightbox');
        const src     = $('#magnific-image').attr('src');

        trigger.magnificPopup({
            type: 'image',
            items: {
                src: src
            }
        });
    })
})(jQuery);