$(window).on('load', function () {

    // Page Preloader

    $('#preloader').delay(1500).fadeOut('slow', function () {

        $(this).remove();

    });
});