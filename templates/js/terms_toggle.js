jQuery(".terms-info").click(function () {
    var $title = jQuery(this).next(".title");
    if (!$title.length) {
        jQuery(this).after("<span class='title'>" + jQuery(this).attr('title') + "</span>");
    } else {
        $title.remove();
    }
});