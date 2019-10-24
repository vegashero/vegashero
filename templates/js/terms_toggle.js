
jQuery(document).ready(function($) {
    $(".terms-info").click(function () {
        var title = $(this).next(".title");
        if (!$title.length) {
            $(this).after("<span class='title'>" + $(this).attr('title') + "</span>");
        } else {
            title.remove();
        }
    });
});
