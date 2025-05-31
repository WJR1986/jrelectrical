// ui.js

document.addEventListener("DOMContentLoaded", function () {
  AOS.init({ duration: 1000, once: true });

  const navbarToggler = document.querySelector(".navbar-toggler");
  const navbarCollapse = document.getElementById("navbarNav");

  if (navbarToggler && navbarCollapse) {
    navbarCollapse.classList.remove("show");
  }
});
