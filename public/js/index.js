

// ANIMATION DU LOADER 

const loader = document.querySelector(".loaderFinal");

window.addEventListener('load', function() {
    loader.className += " hidden"
})



// VIDER LE CHAMP DU TEXTAREA APRÈS AVOIR SOUMIS LE FORMULAIRE

// Récupérez le champ textarea en utilisant son ID
let textarea = document.getElementById('post_content');

// Vérifiez si le champ textarea existe sur la page
if (textarea) {
    textarea.value = ''; // Effacez le contenu du champ textarea
}
