"use strict";
let scrollElements;
let numberElements;
function elementInView(element, dividend = 1) {
    const elementTop = element.getBoundingClientRect().top;
    return (elementTop <= (window.innerHeight || document.documentElement.clientHeight) / dividend);
}
;
function elementOutofView(element) {
    const elementTop = element.getBoundingClientRect().top;
    return (elementTop > (window.innerHeight || document.documentElement.clientHeight));
}
;
const displayScrollElement = (element) => { element.classList.add("scrolled"); };
const hideScrollElement = (element) => { element.classList.remove("scrolled"); };
function handleScrollAnimation() {
    scrollElements.forEach((element) => {
        if (elementInView(element, 1.25)) {
            displayScrollElement(element);
        }
        else if (element.classList.contains("scroll-refresh") && elementOutofView(element)) {
            hideScrollElement(element);
        }
    });
}
function format_number(value, size) {
    let init = value.toString();
    while (init.length < size) {
        init = "0" + init;
    }
    return init;
}
const spin_delta_time = 0.02;
function spinNumber(element) {
    let value = 0;
    let current = 0;
    let size = 1;
    element.classList.forEach(key => {
        if (key.startsWith("v-")) {
            value = Number.parseInt(key.substring(2));
        }
        else if (key.startsWith("s-")) {
            size = Number.parseInt(key.substring(2));
        }
    });
    function update() {
        let a = element.classList.contains("spin-refresh") && elementInView(element) && current != value;
        let b = !element.classList.contains("spin-refresh") && current != value;
        if (a || b) {
            current += (value - current) * spin_delta_time * 2;
            element.innerHTML = format_number(Math.ceil(current), size);
        }
        else {
            stop();
        }
    }
    element.innerHTML = "0";
    element.classList.add("spinning");
    const inter = setInterval(update, spin_delta_time * 1000);
    function stop() {
        clearInterval(inter);
        if (element.classList.contains("spin-refresh")) {
            element.classList.remove("spinning");
        }
    }
}
function handleNumber() {
    numberElements.forEach((element) => {
        if (elementInView(element) && !element.classList.contains("spinning")) {
            spinNumber(element);
        }
    });
}
function handle() {
    handleScrollAnimation();
    handleNumber();
}
function init_slidder() {
    scrollElements = document.querySelectorAll(".js-scroll");
    numberElements = document.querySelectorAll(".spin-number");
    window.addEventListener("scroll", () => { handle(); });
    window.addEventListener("load", () => { handle(); });
    handle();
}
init_slidder();
