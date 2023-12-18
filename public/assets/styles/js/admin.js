document.addEventListener("DOMContentLoaded", () => {
  const navContainer = document.getElementById("nav-container");

  document
    .getElementById("burger-menu-button")
    .addEventListener("click", () => {
      if (navContainer.style.display === "block") {
        navContainer.style.display = "none";
      } else {
        navContainer.style.display = "block";
      }
    });
});
