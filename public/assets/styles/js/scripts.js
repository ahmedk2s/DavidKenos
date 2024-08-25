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


document.addEventListener("DOMContentLoaded", function() {
  // Gérez le clic sur le lien de like
  document.querySelectorAll(".like-link").forEach(function(likeLink) {
      likeLink.addEventListener("click", function(event) {
          event.preventDefault(); // Empêche le comportement par défaut du lien

          var postId = likeLink.getAttribute("data-post-id");
          var nbLikesElement = likeLink.querySelector("span");
          var currentNbLikes = parseInt(likeLink.getAttribute("data-nb"), 10);

          // Effectuez une requête Ajax pour ajouter ou supprimer le like
          var xhr = new XMLHttpRequest();
          xhr.open("POST", "/Like/post/" + postId, true);
          xhr.setRequestHeader("Content-Type", "application/json");

          xhr.onreadystatechange = function() {
              if (xhr.readyState === 4) {
                  if (xhr.status === 200) {
                      var response = JSON.parse(xhr.responseText);
                      console.log(response);

                      // Mettez à jour le nombre de likes sans recharger la page
                      nbLikesElement.innerText = response.nbLike + ' J\'aime';

                      // Mettez à jour l'attribut data-nb pour le prochain clic
                      likeLink.setAttribute("data-nb", response.nbLike);
                  } else {
                      console.error("Erreur lors de la requête. Statut:", xhr.status);
                  }
              }
          };

          xhr.send();
      });
  });
});




document.addEventListener('DOMContentLoaded', function () {
  let chocolateShopLinks = document.querySelectorAll('.chocolate-shop-link');

  chocolateShopLinks.forEach(function (link) {
      link.addEventListener('click', function (event) {
          event.preventDefault();
          let chocolateShopId = link.dataset.chocolaterieId;

          // Filtrer les actualités par chocolaterie
          let blockNewsList = document.querySelectorAll('.block-news');
          blockNewsList.forEach(function (blockNews) {
              if (blockNews.dataset.chocolaterieId == chocolateShopId) {
                  blockNews.style.display = 'block';
              } else {
                  blockNews.style.display = 'none';
              }
          });
      });
  });
});

// JavaScript pour gérer le clic sur les liens
document.addEventListener('DOMContentLoaded', function () {
  var chocolateShopLinks = document.querySelectorAll('.chocolate-shop-link');

  chocolateShopLinks.forEach(function (link) {
      link.addEventListener('click', function () {
          // Retirez la classe 'selected' de tous les liens
          chocolateShopLinks.forEach(function (otherLink) {
              otherLink.classList.remove('selected');
          });

          // Ajoutez la classe 'selected' au lien cliqué
          link.classList.add('selected');
      });
  });
});




