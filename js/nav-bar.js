"use strict";
let navbar;
let up_button_element;
function init_scroll(navbar, up_button_element = null) {
    if (document.body.scrollTop > 80 || document.documentElement.scrollTop > 80) {
        navbar.classList.add("navbar-bg-grey");
        navbar.classList.add("shadow");
        if (up_button_element) {
            up_button_element.style.display = "block";
        }
    }
    else {
        navbar.classList.remove("navbar-bg-grey");
        navbar.classList.remove("shadow");
        if (up_button_element) {
            up_button_element.style.display = "none";
        }
    }
    let current = "";
    let sections = document.querySelectorAll("section");
    sections.forEach((section) => {
        const sectionTop = section.offsetTop;
        if (pageYOffset >= sectionTop - 60) {
            let init = section.getAttribute("id");
            current = init == null ? "" : init;
        }
    });
    let navLi = document.querySelectorAll("nav .container .collapse ul li");
    navLi.forEach((li) => {
        li.classList.remove("active");
        if (li.classList.contains(current)) {
            li.classList.add("active");
        }
    });
}
function init_nav_bar() {
    navbar = document.getElementById("navbar");
    up_button_element = document.getElementById("up-btn");
    if (navbar) {
        window.addEventListener("scroll", function () {
            if (navbar) {
                init_scroll(navbar, up_button_element);
            }
        });
        init_scroll(navbar, up_button_element);
    }
}
init_nav_bar();
