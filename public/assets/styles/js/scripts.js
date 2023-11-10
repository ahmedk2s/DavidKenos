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


