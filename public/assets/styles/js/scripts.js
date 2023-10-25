document.addEventListener("DOMContentLoaded", function () {
  
  const currentUrl = window.location.pathname;

  
  const sideLinks = document.querySelectorAll(".links-container .link");

 
  sideLinks.forEach(function (link) {
    const linkPath = link.getAttribute("href");
    if (currentUrl === linkPath) {
      link.parentElement.classList.add("active");
    }
  });
});
