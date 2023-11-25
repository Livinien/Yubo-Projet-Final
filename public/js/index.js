

// ANIMATION DU LOADER 

const loader = document.querySelector(".loaderFinal");

if(loader) {
    loader.className += " hidden";
}



// SCROLL INIFINI DANS L'APPARITION DES POSTES SUR LA PAGE D'ACCUEIL

// const container = document.querySelector('.posts');


// let currentPage = 0; 

// function loadPosts() {
//     console.log(currentPage);
//     let limitPost = dataPost.slice(currentPage, currentPage + 5)
//     console.log(limitPost);
//     // Traitement des posts reçus
//     limitPost.forEach(post => {
//         // Créer un élément de post pour afficher le contenu et l'image
//         const postContainer = document.createElement('div');
//         postContainer.className = 'post mt-5 col-sm-6 mb-3 mb-sm-0 mx-auto';


//         const authorImage = document.createElement('img');
//         authorImage.src = `${post.author.picture}`;
//         authorImage.className = 'post-avatar rounded-circle';

//         const firstname = document.createElement('p');
//         firstname.textContent = post.author;
//         console.log(post.author);
//         firstname.className = 'name-user fw-bold m-3';

//         const contentParagraph = document.createElement('p');
//         contentParagraph.textContent = post.content;
//         contentParagraph.className = 'content';
        
//         const imgPost = document.createElement('img');
//         imgPost.src = `/uploads/${post.imageName}`;
//         imgPost.className = 'post-image w-100';

//         const ratingContainer = document.createElement('div');
//         ratingContainer.className = 'd-flex justify-content-center mt-3';

//         // Créez l'élément 'div' pour le conteneur des icônes de notation
//         const ratingIconsContainer = document.createElement('div');
//         ratingIconsContainer.className = 'rating d-flex';

//         // Créez l'élément 'i' pour l'icône de pouce vers le haut
//         const thumbsUpIcon = document.createElement('i');
//         thumbsUpIcon.className = `fa-regular fa-thumbs-up icon mx-2 ${post.rating > 0 ? 'rating-up' : ''}`;

//         // Créez l'élément 'p' pour afficher la notation
//         const ratingValue = document.createElement('p');
//         ratingValue.className = post.rating > 0 ? 'rating-up ms-1' : post.rating < 0 ? 'rating-down' : '';
//         ratingValue.textContent = post.rating;

//         // Créez l'élément 'i' pour l'icône de pouce vers le bas
//         const thumbsDownIcon = document.createElement('i');
//         thumbsDownIcon.className = `fa-regular fa-thumbs-down icon ms-4 mx-2 ${post.rating < 0 ? 'rating-down' : ''}`;

//         // Ajoutez les éléments au conteneur des icônes de notation
//         ratingIconsContainer.appendChild(thumbsUpIcon);
//         ratingIconsContainer.appendChild(ratingValue);
//         ratingIconsContainer.appendChild(thumbsDownIcon);

//         // Créez l'élément 'div' pour le conteneur des icônes de réponse
//         const responseIconsContainer = document.createElement('div');
//         responseIconsContainer.className = 'nbrOfResponse d-flex align-item-center ms-4';

//         // Créez l'élément 'i' pour l'icône de commentaire
//         const commentIcon = document.createElement('i');
//         commentIcon.className = 'fa-regular fa-comment icon';

//         // Créez l'élément 'p' pour afficher le nombre de réponses
//         const responseCount = document.createElement('p');
//         responseCount.className = 'ms-1';
//         responseCount.textContent = post.nbrOfResponse;

//         // Ajoutez les éléments au conteneur des icônes de réponse
//         responseIconsContainer.appendChild(commentIcon);
//         responseIconsContainer.appendChild(responseCount);

//         // Ajoutez les conteneurs d'icônes au conteneur principal
//         ratingContainer.appendChild(ratingIconsContainer);
//         ratingContainer.appendChild(responseIconsContainer);

//         // Ajoutez le conteneur principal au conteneur du post
//         postContainer.appendChild(firstname);
//         postContainer.appendChild(contentParagraph);
//         postContainer.appendChild(imgPost);
//         postContainer.appendChild(ratingContainer);
//         // postContainer.appendChild(authorImage);
       

//         // Ajouter le post au conteneur principal
//         container.appendChild(postContainer);
//     });

//     // Incrémentez la page pour la prochaine requête
//     currentPage = currentPage + 5;
// }

// loadPosts();  // Chargez les premiers posts lors du chargement initial

// window.addEventListener('scroll', () => {
//     if (window.scrollY + window.innerHeight >= document.documentElement.scrollHeight) {
//         loadPosts();
//     }
// });



// CRÉATION DU SCROLL POUR LE DÉFILEMENT DES POSTES

const container = document.querySelector('.posts');


window.addEventListener('scroll', () => { 
    const { scrollTop, scrollHeight, clientHeight } = document.documentElement;

    if(scrollTop + clientHeight === scrollHeight) {
        addPosts(10)
    }
});


const index = 0; // Remplacez cela par la valeur réelle de l'index que vous souhaitez utiliser
const post = [
    { id: 1, authorId: 1, authorFirstName: "John", authorPicture: "/path/to/john.jpg" },
    // ... Ajoutez d'autres données de poste ici
];


addPosts(post.length, post);

addPosts(index, post);

function addPosts(index, post) {
    const container = document.querySelector('.posts');

    for (let i = 0; i < index; i++) {
        
        const post = document.createElement("div");
        post.classList.add("post", "mt-5", "col-sm-6", "mb-3", "mb-sm-0", "mx-auto");

        const postContent = document.createElement("div");
        postContent.classList.add("d-flex", "justify-content-between", "align-items-center");

        
        // Créer et ajouter le contenu de contentParagraph
        const contentParagraph = document.createElement('p');
        contentParagraph.className = 'content';
        contentParagraph.textContent = post.content;
        
        // Créer et ajouter l'image de postContent
        const postImage = document.createElement("img");
        postImage.className = "post-image w-100"
        postImage.src = `/uploads/${post.imageName}`;
        postImage.alt = "Image du poste";
        

        const authorInfo = document.createElement("div");
        authorInfo.classList.add("d-flex");

        const authorLink = document.createElement("a");
        authorLink.classList.add("text-decoration-none");
        // authorLink.href = `/user/${postsData[i].authorId}`;

        const authorImage = document.createElement("img");
        authorImage.classList.add("post-avatar", "rounded-circle");
        // authorImage.src = postsData[i].authorPicture;
        authorImage.alt = "Photo de l'utilisateur";

        const authorName = document.createElement("div");
        authorName.classList.add("d-flex");

        const authorNameParagraph = document.createElement("p");
        authorNameParagraph.classList.add("name-user", "fw-bold", "m-3");
        // authorNameParagraph.textContent = postsData[i].authorFirstName;


        const editSection = document.createElement("div");
        editSection.classList.add("btn-group");

        const editButton = document.createElement("button");
        editButton.classList.add("edit-post", "btn", "btn-light", "btn-sm", "dropdown-toggle");
        editButton.type = "button";
        editButton.setAttribute("data-bs-toggle", "dropdown");
        editButton.setAttribute("aria-expanded", "false");

        const editIcon = document.createElement("i");
        editIcon.classList.add("fa-solid", "fa-ellipsis", "bullets-points", "fw-bold", "mt-2");

        const dropdownMenu = document.createElement("ul");
        dropdownMenu.classList.add("dropdown-menu");

        const editItem = document.createElement("li");
        const editLink = document.createElement("a");
        editLink.classList.add("dropdown-item", "text-purple", "fw-bold");
        // editLink.href = `/edit_post/${postsData[i].id}`;
        editLink.textContent = "Modifier";

        const deleteItem = document.createElement("li");
        const deleteLink = document.createElement("a");
        deleteLink.classList.add("dropdown-item", "text-danger", "fw-bold");
        deleteLink.href = `#`;
        deleteLink.setAttribute("data-bs-toggle", "modal");
        // deleteLink.setAttribute("data-bs-target", `#exampleModal2${postsData[i].id}`);
        deleteLink.textContent = "Supprimer";

        postContent.appendChild(postImage);
        postContent.appendChild(contentParagraph);


        editItem.appendChild(editLink);
        deleteItem.appendChild(deleteLink);
        dropdownMenu.appendChild(editItem);
        dropdownMenu.appendChild(deleteItem);

        editButton.appendChild(editIcon);
        editSection.appendChild(editButton);
        editSection.appendChild(dropdownMenu);

        postContent.appendChild(authorInfo);
        postContent.appendChild(editSection);
        post.appendChild(postContent);
        

        // Ajouter le post au conteneur principal
        container.appendChild(post);
        container.appendChild(contentParagraph);
    }
}
