
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
    a.setAttribute( 'href', 'javascript:void(0)' );
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
        // Check if the Fullscreen API is supported 
        if (document.fullscreenEnabled) { 
            // Request full screen 
            console.log('Fullscreen IS supported on this device'); 
            const el = document.querySelector('#vh_iframe_wrapper');
            if (el.requestFullscreen) {
                await el.requestFullscreen();
                document.querySelector('#vh_iframe_wrapper').classList.add('vh-iframe-fs-mode');
                handleEnteredFullscreen();
            }
        } else { 
            // Fullscreen API is not supported (iOS Safari mobile)
            console.log('Fullscreen is NOT supported on this device'); 
            document.querySelector('#vh_iframe_wrapper').classList.add('vh-iframe-fs-iosmobile');
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
        // Check if the Fullscreen API is supported 
        if (document.fullscreenEnabled) {
            document.exitFullscreen();
            document.querySelector('#vh_iframe_wrapper').classList.remove('vh-iframe-fs-mode');
            handleExitedFullscreen();
        } else { 
           document.querySelector('#vh_iframe_wrapper').classList.remove('vh-iframe-fs-iosmobile');
           handleExitedFullscreen();
        }
    });
}

