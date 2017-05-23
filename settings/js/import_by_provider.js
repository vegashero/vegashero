
jQuery(document).ready(function($) {

    $('a.vh-provider-import').click(function(event) {
        var self = $(this)[0];
        console.log(self.dataset.api);
        $.getJSON(self.dataset.api, function(data, status, xhr) {
            console.log(status);
            console.log(data);
        });

    });

});
