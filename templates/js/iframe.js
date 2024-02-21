
/**
 * Removes game iframe from DOM and replaces it with a button.
 * When button clicked, reappends iframe to DOM.
 */
jQuery(document).ready(function($) {

    const container = $('div#vh_iframe_wrapper');
    const frame = container.find('.singlegame-iframe');
    const wrapper = $(`<div class="embed-bg-wrapper" style="background-image:url(${ frame.data('backgroundUrl') });"></div>`);
    const overlay = $(`<div class="embed-overlay"></div>`);
    const button = $(`<button class="play-demo-btn">${ frame.data('buttonText') }</button>`);
    const ageGate = $(`<div class="age-gate-text">${ frame.data('ageGateText') }</div>`);

    frame.remove();
    overlay.append(button, ageGate );
    container.append(wrapper, overlay);

    $('.play-demo-btn').on('click', function() {
        $('.embed-overlay').remove();
        $('.embed-bg-wrapper').remove();
        frame.attr('src', frame.data('srcUrl') );
        frame.css('background-color', 'black');
        container.append( frame );
    });

});

