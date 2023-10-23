// Cette fonction s'assure que le menu est caché au chargement de la page
window.onload = () => {
  const navContainer = document.getElementById("nav-container");
  navContainer.style.display = "none";
};

document.addEventListener("DOMContentLoaded", (event) => {
  document
    .getElementById("burger-menu-button")
    .addEventListener("click", () => {
      const navContainer = document.getElementById("nav-container");

      if (navContainer.style.display === "block") {
        navContainer.style.display = "none";
      } else {
        navContainer.style.display = "block";
      }
    });
});

