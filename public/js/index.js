

// ANIMATION DU LOADER 


const loader = document.querySelector(".loaderFinal");

if(loader) {
    loader.className += " hidden";
}



// MASQUER LES MESSAGES FLASH APRÈS UN DÉLAI DE 5 SECONDES

setTimeout(function() {
    let successFlash = document.querySelector('.flash-success');
    // let dangerFlash = document.querySelector('.flash-danger');

    if (successFlash) {
        successFlash.style.display = 'none';
    }

    // if (dangerFlash) {
    //     dangerFlash.style.display = 'none';
    // }
}, 4000); // MODIFIER LA VALEUR (EN MILLISECONDES)


