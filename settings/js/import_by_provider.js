
jQuery(document).ready(function($) {

    $('a.vh-provider-import').click(function(event) {
        var self = $(this)[0];
        console.log(self.dataset.provider);
    });

});
