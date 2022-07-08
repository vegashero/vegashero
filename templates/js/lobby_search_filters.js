
/**
 * thumb error handling - fallback img
 */
function imgError(image) {
    image.onerror = '';
    image.src = '//cdn.vegasgod.com/undefined/cover.jpg';
    return true;
}

var vhLobbyImage = {
    imageLoaded: function(postId) {
        var element = document.querySelector('li#img'+postId);
        if(element) {
            element.style.display = 'block'
        }
    }
};

jQuery(document).ready(function($) {

    var Lobby = function() {
        var self = this;
        this.searchInput = $('input#vh-search');
        this.getQueryData = function(options) {
            //console.log('getting query data');
            return {
                'action': 'lobby_search_filter',
                'page': options.paged ? options.paged : 1,
                'paged': options.paged ? options.paged : 1,
                // 'whatever': ajax_object.we_value      // We pass php values differently!
                'taxonomy': options.taxonomy ? options.taxonomy : '',
                'filterBy': options.filterBy ? options.filterBy : '',
                'site_url': ajax_object.site_url,
                'image_url': ajax_object.image_url,
                'playnow_btn_value': ajax_object.playnow_btn_value,
                'lobby_img_format': ajax_object.lobby_img_format,
                'vh_custom_post_type_url_slug': ajax_object.vh_custom_post_type_url_slug
            };
        };

        this.getGames = function(data, callback) {
            //console.log('getting games');
            const jqxhr = jQuery.get(ajax_object.ajax_url, data, function(response) {
                callback(jQuery.parseJSON(response));
            });
            jqxhr.always(function() {
                jQuery( document.body ).trigger( 'post-load' );
            });
        };

        this.getGameMarkup = function(data, post) {
            var markup = '<li id="img' + post.ID + '" style="display:none" class="vh-item ' + post.category + ' ' + post.provider + ' ' +post.operator + '">';
            markup += '<a href="' + data.site_url + '/' + data.vh_custom_post_type_url_slug + '/' + post.post_name + '/' + '" class="vh-thumb-link">';
            markup += '<div class="vh-overlay">';
            if(post.thumbnail) {
                markup += '<img width="376" height="250" src="' + post.thumbnail + '" alt="' + post.post_title + '" title="' + post.post_title + '" onerror="imgError(this);" onload="vhLobbyImage.imageLoaded('+post.ID+')" />';
            } else if(post.imgpath) {
                markup += '<img width="376" height="250" src="' + post.imgpath + '" alt="' + post.post_title + '" title="' + post.post_title + '" onerror="imgError(this);" onload="vhLobbyImage.imageLoaded('+post.ID+')" />';
            } else {
                markup += '<img width="376" height="250" src="' + data.image_url + '/' + post.provider + '/' + post.post_name + '/' + data.lobby_img_format + '" alt="' + post.post_title + '" title="' + post.post_title + '" onerror="imgError(this);" onload="vhLobbyImage.imageLoaded('+post.ID+')" />';
            }
            markup += '<span class="play-now">' + data.playnow_btn_value + '</span>';
            markup += '</div>';
            markup += '</a>';
            markup += '<div class="vh-game-title">' + post.post_title + '</div>';
            markup += '</li>';
            return markup;
        };

        this.getPaginationMarkup = function(res) {
            var markup = '<div class="vh-pagination">';
            if( res.pagination.prev && 'href' in res.pagination.prev ) {
              markup += `<a class="prev nofollow" href="${res.pagination.prev.href}">${res.pagination.prev.text}</a>`;
            }
            if( res.pagination.next && 'href' in res.pagination.next ) {
              markup += `<a class="next nofollow" href="${res.pagination.next.href}">${res.pagination.next.text}</a>`;
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
            var loadingIndicator = '<span class="cssload-spin-box"></span>';
            $('ul#vh-lobby-posts.vh-row-sm').html(loadingIndicator);
        };

        this.resetFilters = function() {
            $.each($('div.vh-filter select option'), function(i, option) {
                option.removeAttribute('selected');
            });
            $.each($('div.vh-filter select'), function(i, select) {
                select.querySelector('option').selected = true;
            });
        };

        this.resetFilter = function(select) {
            select.querySelectorAll('option').forEach(function(option) {
                option.removeAttribute('selected');
            });
            select.querySelector('option').selected = true;
        };

        this.resetNextFilters = function(select) {
            while(select.nextElementSibling) {
                if(select.nextElementSibling.tagName.toLowerCase() === 'select') {
                    lobby.resetFilter(select.nextElementSibling);
                }
                select = select.nextElementSibling;
            }
        };

        this.resetPreviousFilters = function(select) {
            while(select.previousElementSibling) {
                if(select.previousElementSibling.tagName.toLowerCase() === 'select') {
                    lobby.resetFilter(select.previousElementSibling);
                }
                select = select.previousElementSibling;
            }
        };

        this.resetOtherFilters = function(select) {
            this.resetNextFilters(select);
            this.resetPreviousFilters(select);
        };

        this.resetSearch = function() {
            this.searchInput.val('');
        };
        
        this.loadGames = function(options, callback) {
            //console.log('loading games');
            self.showLoading();
            if( ! options) {
                var options = {};
            }
            var data = this.getQueryData(options);
            //console.log('query data', data);
            this.getGames(data, function(res) {
                //console.log('response', res);
                var markup = ''
                jQuery.each(res.posts, function(key, post) {
                    markup += self.getGameMarkup(data, post);
                });
                var pagination = self.getPaginationMarkup(res);
                $('ul#vh-lobby-posts.vh-row-sm').html(markup + pagination);
                if(callback) {
                    callback();
                }

                // pagination
                $('div.vh-pagination a').bind('click', function(event) {
                    event.preventDefault();
                    var url = event.target.getAttribute('href');
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
    // filter games
    $('div.vh-filter select').bind("change", function(e) {
        //console.log('change event');
        lobby.resetOtherFilters(e.target);
        lobby.resetSearch();
        lobby.loadGames({
            taxonomy: e.target.dataset.taxonomy,
            filterBy: e.target.value
        });
    });

    // search games
    $('input#vh-search').bind('input', $.debounce(500, function(e) {
        //console.log(quicksearch);
        lobby.resetFilters();
        lobby.loadGames({
            taxonomy: '',
            filterBy: e.target.value
        });
    }));

});
