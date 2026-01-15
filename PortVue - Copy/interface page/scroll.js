window.onscroll = function() {
    changeNavbarColor();
};

function changeNavbarColor() {
    var navbar = document.getElementById("n");
    var navbarLinks = document.querySelectorAll(".nav_icons");

    if (document.body.scrollTop > 30 || document.documentElement.scrollTop > 30) {
      navbar.classList.add("scrolled");
      navbarLinks.forEach(link => link.classList.add("scrolled"));
    }
    else {
      navbar.classList.remove("scrolled");
      navbarLinks.forEach(link => link.classList.remove("scrolled"));
    }
};