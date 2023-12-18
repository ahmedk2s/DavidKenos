document.addEventListener("DOMContentLoaded", function () {
  const currentUrl = window.location.pathname;
  const sideLinks = document.querySelectorAll(".links-container .link");

  sideLinks.forEach(function (link) {
    const linkPath = link.getAttribute("href");
    if (currentUrl === linkPath) {
      link.parentElement.classList.add("active");
    }
  });

  let alert = document.getElementById("success-alert");
  let closeButton = alert.querySelector(".close");

  closeButton.addEventListener("click", function () {
    alert.style.display = "none";
  });

  // setTimeout(function () {
  //   alert.style.display = "none";
  // }, 5000);
});

// document.addEventListener("DOMContentLoaded", function () {
//   let icons = document.getElementById("icons");
//   let links = document.getElementById("links");

//   icons.addEventListener("click", function () {
//     links.classList.toggle("activ");
//     console.log(links.classList.contains("activ"));
//   });
// });

document.addEventListener("DOMContentLoaded", function () {
  let icons = document.getElementById("icons");
  let links = document.getElementById("links");
  let isActive = false;

  icons.addEventListener("click", function () {
    if (isActive) {
      links.classList.remove("activ");
    } else {
      links.classList.add("activ");
    }

    isActive = !isActive; // Inversez l'état de isActive pour le prochain clic
    console.log(links.classList.contains("activ"));
  });
});

document.addEventListener("DOMContentLoaded", function () {
  // Sélectionnez tous les liens avec la classe 'category-link'
  let categoryLinks = document.querySelectorAll(".container-categories a");

  // Fonction pour mettre à jour la catégorie sélectionnée
  function updateSelectedCategory(categoryName) {
    // Mettez à jour le contenu du span avec l'ID 'selected-category'
    document.getElementById("selected-category").textContent = categoryName;

    // Sauvegardez la catégorie sélectionnée dans le stockage local
    localStorage.setItem("selectedCategory", categoryName);
  }

  // Ajoutez un gestionnaire d'événement de clic à chaque lien
  categoryLinks.forEach(function (link) {
    link.addEventListener("click", function (event) {
      // Récupérez le texte de la catégorie cliquée
      let categoryName = link.textContent;

      // Appelez la fonction pour mettre à jour la catégorie sélectionnée
      updateSelectedCategory(categoryName);
    });
  });

  // Récupérez la catégorie sélectionnée depuis le stockage local lors du chargement de la page
  let savedCategory = localStorage.getItem("selectedCategory");
  if (savedCategory) {
    // Si une catégorie a été précédemment sélectionnée, mettez à jour le contenu du span
    updateSelectedCategory(savedCategory);
  }
});




