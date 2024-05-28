'use strict';

window.addEventListener( 'load', e => {
	const copyables = document.querySelectorAll( '.ymfo-copyable' );
	copyables.forEach( copyable => {
		copyable.addEventListener( 'click', e => {
			navigator.clipboard.writeText( copyable.innerText );

			copyable.classList.add( 'ymfo-copied' );

			setTimeout( () => {
				copyable.classList.remove( 'ymfo-copied' );
			}, 1000 );
		});
	});
});