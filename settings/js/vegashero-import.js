'use strict';

jQuery(document).ready(function($) {

    let importButtons = $('button.vh-import');

    function disableButtons(buttonArray) {
        $.each(buttonArray, function(buttonIndex, button) {
            button.setAttribute('disabled', true);
        });
    }

    function enableButtons(buttonArray) {
        $.each(buttonArray, function(buttonIndex, button) {
            button.removeAttribute('disabled');
        });
    }

    function removeAdminNotices() {
        $('div.vh-admin-notice').remove();
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

        div.addEventListener('click', function(event) {
            $(this).remove();
        });
    }

    function createAdminNotice(code, data, message) {
        if(code !== 'success') {
            triggerAdminNotice("Unable to import games, please try again", "error");
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
        triggerAdminNotice("Import started. Please do not leave this page during the import.", "warning");
        let self = $(this)[0];
        disableButtons(importButtons);
        let btnText = self.textContent;
        self.textContent = 'importing';
        $.getJSON(self.dataset.api)
            .done(function(data, status, xhr) {
                let response = data;
                createAdminNotice(response.code, response.data, response.message); 
            })
            .fail(function(data, status, xhr) {
                if( ! data.responseJSON) {
                    triggerAdminNotice("Networking error. Please try again later.", "error");
                } else {
                    let response = data.responseJSON;
                    createAdminNotice(response.code, response.data, response.message);
                }
            })
            .always(function() {
                self.textContent = btnText;
                enableButtons(importButtons);
            });

    });

});
