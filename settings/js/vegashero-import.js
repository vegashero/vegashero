'use strict';

jQuery(document).ready(function($) {

    let importButtons = $('button.vh-import');

    function disableButtons(buttonArray) {
        $.each(buttonArray, function(buttonIndex, button) {
            button.className = 'button vh-import';
            button.textContent = 'Please wait';
            button.setAttribute('disabled', true);
        });
    }

    function enableButtons(buttonArray) {
        $.each(buttonArray, function(buttonIndex, button) {
            button.className = 'button button-primary vh-import';
            button.textContent = 'Import games';
            button.removeAttribute('disabled');
        });
    }

    function removeAdminNotices() {
        $('div.vh-admin-notice').remove();
    }

    function triggerLoadingAdminNotice(message, type='success') {
        removeAdminNotices();
        let div = document.createElement('div');
        div.className = 'vh-admin-notice notice notice-' + type + ' is-dismissible';
        let p = document.createElement('p');
        p.textContent = message;
        let spinner = document.createElement('div');
        spinner.className = 'spinner is-active';
        spinner.style.margin = 0;
        p.appendChild(spinner);
        div.appendChild(p);
        $('#wpbody-content').prepend(div);

        $("html, body").animate({ scrollTop: 0 }, "slow");
    }

    function triggerAdminNotice(message, type='success') {
        removeAdminNotices();
        let div = document.createElement('div');
        div.className = 'vh-admin-notice notice notice-' + type + ' is-dismissible';
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
    }

    function createAdminNotice(code, data, message) {
        if(code !== 'success') {
            console.error(code);
            console.error(data);
            console.error(message);
            triggerAdminNotice(code + ": " + message, "error");
        } else {
            let gameProvider = decodeURIComponent(data.game_source).replace(/\b\w/g, function(l){ return l.toUpperCase() });
            let importCount = data.successful_imports;
            let newGameCount = data.new_games_imported;
            let updatedGameCount = data.existing_games_updated;
            if( ! newGameCount) {
                triggerAdminNotice('Updated ' + updatedGameCount + ' games for ' + gameProvider + ". " + randomMotivator(), "info");
            } else {
                triggerAdminNotice('Imported ' + newGameCount + ' games for ' + gameProvider + ". " + randomMotivator(), "success");
            }
        }
    }

    function getRandomInteger(min, max) {
        return Math.floor(Math.random() * (max - min) ) + min;
    }

    function randomMotivator() {
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

    importButtons.click(function(event) {
        event.preventDefault();
        triggerLoadingAdminNotice("Please wait. Your import has started. Do not leave this page while your import is in progress.", "warning");
        let self = $(this)[0];
        let button = this;
        let buttonWrapper = button.parentElement;
        let spinner = document.createElement('span');
        spinner.className = 'spinner is-active';
        spinner.style.float = 'left';
        spinner.style.margin = '0 0 0 38px';
        //buttonWrapper.removeChild(button);
        button.style.visibility = 'hidden';
        buttonWrapper.appendChild(spinner);
        //button.replaceWith(spinner);

        disableButtons(importButtons);
        //let btnText = self.textContent;
        //self.textContent = 'importing';
        $.getJSON(self.dataset.api)
            .done(function(json) {
                console.log(json);
                createAdminNotice(json.code, json.data, json.message); 
            })
            .fail(function(xhr, status, error) {
                console.log('xhr object');
                console.log(xhr);
                console.log('status');
                console.log(status);
                console.log('error');
                console.log(error);
                if( ! xhr.responseJSON) {
                    if( ! xhr.responseText) {
                        triggerAdminNotice("Networking error. Please try again later.", "error");
                    } else {
                        triggerAdminNotice("Error parsing response. Please ensure WP_DEBUG=false in your wp-config.php file", "error");
                    }
                } else {
                    let response = xhr.responseJSON;
                    createAdminNotice(response.code, response.data, response.message);
                }
            })
            .always(function() {
                //self.textContent = btnText;
                spinner.remove();
                button.style.visibility = 'visible';
                enableButtons(importButtons);
            });

    });

});
