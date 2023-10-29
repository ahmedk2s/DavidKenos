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