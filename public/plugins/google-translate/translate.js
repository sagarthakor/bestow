function googleTranslateElementInit() {
    new google.translate.TranslateElement({
        pageLanguage: 'en', includedLanguages: 'en,hi,mr,gu,pa,bn,ta,ur',
        layout: google.translate.TranslateElement.InlineLayout.SIMPLE
    }, 'google_translate_element');
}

$('document').ready(function () {
    $('#google_translate_element').on("click", function () {

        $("iframe").contents().find(".goog-te-menu2").css({
            'width': '95px !important'
        });

        // Change font family and color
        $("iframe").contents().find(".goog-te-menu2-item div, .goog-te-menu2-item:link div, .goog-te-menu2-item:visited div, .goog-te-menu2-item:active div") //, .goog-te-menu2 *
            .css({
                'color': '#544F4B',
                'background-color': '#ffffff',
                'font-family': '"Open Sans",Helvetica,Arial,sans-serif'
            });

        // Change hover effects  #e3e3ff = white
        $("iframe").contents().find(".goog-te-menu2-item div").hover(function () {
            $(this).css('background-color', '#17548d').find('span.text').css('color', '#e3e3ff');
        }, function () {
            $(this).css('background-color', '#ffffff').find('span.text').css('color', '#544F4B');
        });

        // Change Google's default blue border
        $("iframe").contents().find('.goog-te-menu2').css('border', '1px solid #17548d');
        $("iframe").contents().find('.goog-te-menu2').css('min-width', '95px');
        $("iframe").contents().find('.goog-te-menu2 table').css('width', '100%');
        $("iframe").contents().find('.goog-te-menu2').css('background-color', '#ffffff');

        // Change the iframe's box shadow
        $(".goog-te-menu-frame").css({
            '-moz-box-shadow': '0 3px 8px 2px #666666',
            '-webkit-box-shadow': '0 3px 8px 2px #666',
            'box-shadow': '0 3px 8px 2px #666'
        });
    });
});