'use strict';

jQuery(document).ready(function($) {

    let AdminNotice = {
        removeAll: function(className) {
            $('div.' + className).remove();
        },
        triggerLoading: function(message, type='success') {
            this.removeAll('vh-admin-notice');
            let div = document.createElement('div');
            div.className = 'vh-admin-notice notice notice-' + type + ' is-dismissible';
            let p = document.createElement('p');
            p.textContent = message;
            let spinner = document.createElement('div');
            spinner.className = 'spinner is-active';
            p.appendChild(spinner);
            div.appendChild(p);
            $('#wpbody-content').prepend(div);

            $("html, body").animate({ scrollTop: 0 }, "slow");
        },
        trigger: function(message, type='success', className='vh-admin-notice') {
            this.removeAll(className);
            let div = document.createElement('div');
            div.className = className + ' notice notice-' + type + ' is-dismissible';
            let p = document.createElement('p');
            p.textContent = message;
            let button = document.createElement('button');
            button.setAttribute('type', 'button');
            button.className = 'notice-dismiss';
            p.appendChild(button);
            div.appendChild(p);
            $('#wpbody-content').prepend(div);

            $("html, body").animate({ scrollTop: 0 }, "slow");
            button.addEventListener('click', function(event) {
                div.remove();
            });
        },
        create: function(code, data, message) {
            if(code !== 'success') {
                this.trigger(code + ": " + message, "error");
            } else {
                let gameProvider = decodeURIComponent(data.game_source).replace(/\b\w/g, function(l){ return l.toUpperCase() });
                let importCount = data.successful_imports;
                let newGameCount = data.new_games_imported;
                let updatedGameCount = data.existing_games_updated;
                if( ! newGameCount) {
                    this.trigger('Updated ' + updatedGameCount + ' games for ' + gameProvider + ". " + randomMotivator(), "info");
                } else {
                    this.trigger('Imported ' + newGameCount + ' games for ' + gameProvider + ". " + randomMotivator(), "success");
                }
            }
        }
    };

    let Motivator = {
        getRandomInteger: function(min, max) {
            return Math.floor(Math.random() * (max - min) ) + min;
        },
        randomMotivator: function() {
            let motivator = [
                "You hit the target!",
                "Cracking job!",
                "Poetry in motion!",
                "First class!",
                "Just what the doctor ordered!",
                "Impressive",
                "Brilliant!",
                "Nice job!",
                "Kerching!",
                "Well done!",
                "Keep at it!",
                "You're on your way!",
                "Fantastic work!",
                "Nicely done!",
                "Great effort!"
            ];
            return motivator[getRandomInteger(0, motivator.length)]
        }
    }

    let GameImporter = {

        importButtons: $('button.vh-import'),

        disableButtons: function(buttonArray) {
            $.each(buttonArray, function(buttonIndex, button) {
                button.className = 'button vh-import';
                button.textContent = 'Please wait';
                button.setAttribute('disabled', true);
            })
        },

        enableButtons: function() {
            $.each(this.importButtons, function(buttonIndex, button) {
                button.className = 'button button-primary vh-import';
                button.textContent = 'Import games';
                button.removeAttribute('disabled');
            });
        },

        /**
         * Fetch all games from remote Vegas God server
         * @param {String} endpoint
         * @return {Promise<Array, Error>} 
         */
        fetch: function(endpoint) {
            return $.getJSON(endpoint);
        },

        reset: function() {
            this.hideSpinner();
            this.enableButtons();
            AdminNotice.removeAll('vh-admin-alert');
        },

        /**
         * Import all games into Wordpress
         * @param {Number} totalGames
         * @param {Array<Object>} gamesToImport - games to be imported
         * @param {String} importUrl
         * @param {Number} gamesImported - number of games imported
         * @param {Number} batchSize
         * @param {Promise<Object, Error>
         */
        batchImport: function(totalGames, gamesToImport, importUrl, gamesImported=0, batchSize=20) {
            let batch = gamesToImport.splice(0, batchSize);
            if(batch.length) {
                this.import(batch, importUrl)
                    .done(function(response) {
                        gamesImported += parseInt(response['data']['successful_imports']);
                        //console.log("Imported " + gamesImported + "/" + totalGames + " games");
                        //console.log(response);
                        $(GameImporter.button).siblings(".spinner").html(`Importing ${gamesImported}/${totalGames} games. Please wait...`);
                        AdminNotice.triggerLoading("Importing " + gamesImported + "/" + totalGames + " games. Please wait...", "info");
                        if(gamesToImport.length) {
                            this.batchImport(totalGames, gamesToImport, importUrl, gamesImported);
                        } else {
                            AdminNotice.trigger("SUCCESS: Finished importing " + gamesImported + "/" + totalGames + " games", "success");
                            GameImporter.reset();
                        }
                    }.bind(this))
                    .fail(function(xhr, status, error) {
                        this.handleError(xhr, status, error);
                        this.hideSpinner();
                        this.enableButtons();
                        AdminNotice.removeAll('vh-admin-alert');
                    }.bind(this));
            }
        },

        handleError: function(xhr, status, error) {
            if( ! xhr.responseJSON) {
                if( ! xhr.responseText) {
                    AdminNotice.trigger("Networking error. Please try again later.", "error");
                } else {
                    AdminNotice.trigger("Error parsing response. Please ensure pretty permalinks are enabled and WP_DEBUG=false in your wp-config.php file", "error");
                }
            } else {
                let response = xhr.responseJSON;
                AdminNotice.create(response.code, response.data, response.message);
            }
        },

        /**
         * Import a batch of games into Wordpress
         * @param {Array<Object>} games 
         * @param {String} url
         * @return {Promise<Object, Error>}
         */
        import: function(games, url) {
            return $.ajax({
                method: "POST",
                contentType: "application/json",
                dataType: "json",
                url: url,
                data: JSON.stringify(games)
            });
        },

        showSpinner: function() {
            let button = this.button;
            let buttonWrapper = button.parentElement;
            let checkboxes = buttonWrapper.querySelectorAll('.vh_game_type_checkbox');
            checkboxes.forEach(checkbox => checkbox.style.display = 'none');
            this.spinner = document.createElement('span');
            this.spinner.className = 'spinner is-active';
            //button.style.display = 'none';
            buttonWrapper.appendChild(this.spinner);
        },

        hideSpinner: function() {
            let button = this.button;
            let buttonWrapper = button.parentElement;
            let checkboxes = buttonWrapper.querySelectorAll('.vh_game_type_checkbox');
            checkboxes.forEach(checkbox => checkbox.style.display = 'block');
            this.spinner.remove();
            //button.style.display = 'block';
        },

        getQueryParams: function(url) {
            let parsedUrl = this.parseUrl(url);
            //console.log(parsedUrl);
            //console.log(parsedUrl.search);
        },

        parseUrl: function(url) {
            let parser = document.createElement('a');
            parser.href = url;
            return parser;
        },

        buildURL: function(url, html5, flash) {
            if(html5 && flash) {
                return url;
            }
            if(html5) {
                return `${url}&type=html5`;
            }
            if(flash) {
                return `${url}&type=flash`;
            }
        }

    }

    GameImporter.importButtons.click(function(event) {
        event.preventDefault();
        GameImporter.button = this;
        let html5 = $(this).parent('div.btn-area').find('input[name="vh-import-html5"]').prop('checked');
        let flash = $(this).parent('div.btn-area').find('input[name="vh-import-flash"]').prop('checked');
        let url = GameImporter.buildURL(this.dataset.fetch, html5, flash);
        AdminNotice.trigger("IMPORTANT: Please do not close this window while import is in progress", "warning", "vh-admin-alert");
        let notice = "Fetching games. Please wait...";
        AdminNotice.triggerLoading(notice, "info");
        GameImporter.showSpinner();
        $(GameImporter.button).siblings(".spinner").html(notice);
        GameImporter.disableButtons(GameImporter.importButtons);
        //GameImporter.getQueryParams(self.dataset.fetch);
        GameImporter.fetch(url)
            .done(function(games) {
                if( ! games.length) {
                    AdminNotice.trigger("No games to import.", "warning");
                    GameImporter.reset();
                    return;
                }
                let activeGames = games.filter(game => parseInt(game.status)); 
                let totalGames = activeGames.length;
                $(GameImporter.button).siblings(".spinner").html(`Finished fetching ${totalGames} games.`);
                //AdminNotice.triggerLoading(`Finished fetching ${totalGames} games.`, "success");
                GameImporter.batchImport(totalGames, activeGames, GameImporter.button.dataset.import, 0);
            })
            .fail(function(xhr, status, error) {
                console.log('fetch failed');
                GameImporter.handleError(xhr, status, error)
                GameImporter.hideSpinner();
                GameImporter.enableButtons();
                AdminNotice.removeAll('vh-admin-alert');
            });
    });

});
