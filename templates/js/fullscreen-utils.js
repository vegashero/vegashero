
function getEnterFullscreenClassName() {
    return 'dashicons-fullscreen-alt';
}

function getExitFullscreenClassName() {
    return 'dashicons-fullscreen-exit-alt';
}

function getIconLink( className ) {
    const span = document.createElement( 'span' );
    span.classList.add( 'dashicons', className );
    const a = document.createElement( 'a' );
    a.setAttribute( 'href', '#' );
    a.setAttribute( 'id', getFullscreenHrefId() );
    a.appendChild( span );
    return a;
}

function getFullscreenHrefId() {
    return 'vh_fullscreen_href_id';
}

function getEnterFullscreenLink() { 
    return getIconLink( 
        getEnterFullscreenClassName() 
    );
}

function getExitFullscreenLink() { 
    return getIconLink( 
        getExitFullscreenClassName(), 
        'exit' 
    );
}

function registerEnterFullscreenClickHandler( ) {
    document.querySelector(`a span.${ getEnterFullscreenClassName() }`).addEventListener('click', async function(e) {
        const el = document.querySelector('#vh_iframe_wrapper');
        if (el.requestFullscreen) {
            await el.requestFullscreen();
            handleEnteredFullscreen();
        }
    });
}

function handleEnteredFullscreen() {
    document.querySelector( `#${ getFullscreenHrefId() }`).replaceWith( getExitFullscreenLink() ) ;
    registerExitFullscreenClickHandler();
}

function handleExitedFullscreen() {
    document.querySelector( `#${ getFullscreenHrefId() }`).replaceWith( getEnterFullscreenLink() ) ;
    registerEnterFullscreenClickHandler();
}

function registerExitFullscreenClickHandler() {
    document.querySelector(`a span.${ getExitFullscreenClassName() }`).addEventListener('click', async function(e) {
        document.exitFullscreen();
        handleExitedFullscreen();
    });
}

