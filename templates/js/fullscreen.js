
jQuery(document).ready(function($) {
    const container = $('#vh_iframe_wrapper');
    container.prepend( getEnterFullscreenLink() );
    registerEnterFullscreenClickHandler();
    addEventListener("fullscreenchange", (e) => {
        if ( document.fullscreenElement) {
            handleEnteredFullscreen();
        } else {
            handleExitedFullscreen();
        }
    });
});

