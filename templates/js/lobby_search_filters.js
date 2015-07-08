jQuery(document).ready(function($) {
    $('div.vh-filter select').change(function() {
        var data = {
            'action': 'lobby_search_filter',
            // 'whatever': ajax_object.we_value      // We pass php values differently!
            'taxonomy': $(this).attr('data-taxonomy'),
            'filter': $(this).val(),
            'site_url': ajax_object.site_url,
            'image_url': ajax_object.image_url
        };
        // We can also pass the url value separately from ajaxurl for front end AJAX implementations
        jQuery.get(ajax_object.ajax_url, data, function(response) {
            var res = jQuery.parseJSON(response);
            var markup = '';
            jQuery.each(res.posts, function(key, post) {
                markup += '<div class="vh-item">';
                markup += '<a href="' + data.site_url + '/' + post.post_name + '" class="vh-thumb-link">'
                markup += '<img width="" height="" src="' + data.image_url + '/' + post.provider + '/' + post.post_name + '/cover.jpg" alt="' + post.post_title + '" title="' + post.post_title + '" />';
                markup += '</a>';
                markup += '<div class="vh-game-title">';
                markup += '<a title="' + post.post_title + '" href="' + data.site_url + '/' + post.post_name + '">' + post.post_title + '</a>';
                markup += '</div>';
                markup += '</div>';

            });

            var pagination = '<div class="vh-pagination">';
            pagination += 'current page is ' + res.page + '<br><br>';
            pagination += '<a class="prev">previous</a>';
            pagination += '<a class="prev">next</a>';
            pagination += '</div>';

            $('div#vh-lobby-posts.vh-row-sm').fadeOut('300', function() {
                $(this).html(markup + pagination);
                $(this).fadeIn('300', function() {
                    $("[data-taxonomy~='"+data.taxonomy+"'] option:eq(0)").attr('selected', 'selected');
                });
            });
        });
    });
});
