$(document).ajaxStart(function() {
    let zIndex = 100;
    if ($('body').hasClass('modal-open')) {
        zIndex = parseInt($('div.modal').css('z-index')) + 1;
    }
    $("#ajax_loading").css({
        'z-index': zIndex
    });
    $("#ajax_loading").fadeIn(0);
});

$(document).ajaxStop(function () {
    $("#ajax_loading").fadeOut(300);
});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
