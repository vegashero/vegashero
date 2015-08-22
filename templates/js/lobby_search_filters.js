jQuery(document).ready(function($) {

    var Lobby = function() {
        var self = this;

        this.getQueryData = function(options) {
            return {
                'action': 'lobby_search_filter',
                'page': options.paged ? options.paged : 1,
                'paged': options.paged ? options.paged : 1,
                // 'whatever': ajax_object.we_value      // We pass php values differently!
                'taxonomy': options.taxonomy ? options.taxonomy : '',
                'filterBy': options.filterBy ? options.filterBy : '',
                'site_url': ajax_object.site_url,
                'image_url': ajax_object.image_url
            };
        };

        this.getGames = function(data, callback) {
            jQuery.get(ajax_object.ajax_url, data, function(response) {
                callback(jQuery.parseJSON(response));
            });
        };

        this.getGameMarkup = function(data, post) {
            var markup = '<div class="vh-item ' + post.category + ' ' + post.provider + ' ' +post.operator + '">';
            markup += '<a href="' + data.site_url + '/' + post.post_name + '" class="vh-thumb-link">'
            markup += '<img width="" height="" src="' + data.image_url + '/' + post.provider + '/' + post.post_name + '/cover.jpg" alt="' + post.post_title + '" title="' + post.post_title + '" />';
            markup += '</a>';
            markup += '<div class="vh-game-title">';
            markup += '<a title="' + post.post_title + '" href="' + data.site_url + '/' + post.post_name + '">' + post.post_title + '</a>';
            markup += '</div>';
            markup += '</div>';
            return markup;
        };

        this.getPaginationMarkup = function(res) {
            console.log(res);
            var markup = '<div class="vh-pagination">';
            if(res.pagination.prev) {
                markup += res.pagination.prev;
            }
            if(res.pagination.next) {
                markup += res.pagination.next;
            }
            markup += '</div>';
            return markup;
        };

        this.parseQueryString = function( queryString ) {
            var params = {}, queries, temp, i, l;
            // Split into key/value pairs
            queries = queryString.split("&");
            // Convert the array of strings into an object
            for ( i = 0, l = queries.length; i < l; i++ ) {
                temp = queries[i].split('=');
                params[temp[0]] = temp[1];
            }
            return params;
        };

        this.showLoading = function() {
            var loadingIndicator = 'loading games...';
            $('div#vh-lobby-posts.vh-row-sm').html(loadingIndicator);
        };

        this.loadGames = function(options, callback) {
            self.showLoading();
            if( ! options) {
                var options = {};
            }
            var data = this.getQueryData(options);
            console.log(data);
            this.getGames(data, function(res) {
                console.log(res);
                var markup = ''
                jQuery.each(res.posts, function(key, post) {
                    markup += self.getGameMarkup(data, post);
                });
                var pagination = self.getPaginationMarkup(res);
                $('div#vh-lobby-posts.vh-row-sm').html(markup + pagination);
                if(callback) {
                    callback();
                }
                // filter games
                $('div.vh-filter select').change(function() {
                    self.loadGames({
                        taxonomy: $(this).attr('data-taxonomy'),
                        filterBy: $(this).val()
                    }, function() {
                        this.prop('selectedIndex', 0);
                    }.bind($(this)));
                });
                // pagination
                $('div.vh-pagination').on('click', 'a', function(event) {
                    event.preventDefault();
                    var url = $(this).attr('href');
                    var queryString = url.substring( url.indexOf('?') + 1 );
                    var parsed = self.parseQueryString(queryString);
                    self.loadGames({
                        paged: parsed['paged'],
                        taxonomy: parsed['taxonomy'] ? parsed['taxonomy'] : '',
                        filterBy: parsed['filterBy'] ? parsed['filterBy'] : ''
                    });
                });
            });
        };
    };

    var lobby = new Lobby();
    lobby.loadGames();

});
