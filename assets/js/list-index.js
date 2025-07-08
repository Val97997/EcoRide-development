const searchForm = document.getElementById("search-form");
let iconSwitch = document.getElementById("switch-search-icon");

let arrivalInput = document.getElementById("arrival_location");
let departureInput = document.getElementById("departure_location");
let dateInput = document.getElementById("departure_date");
let maxPriceInput = document.getElementById("max");
let durationInput = document.getElementById("duration");
const revealBtn = document.getElementById("more-search-options");
const switchBtn = document.getElementById("switch-fields");

let flashMessages = document.querySelectorAll("ul");
let k = 5;
flashMessages.forEach((message) => {
    message.appendChild(
        document.createElement("i", {class: "bi bi-exclamation-triangle-fill",})
    );
    message.style.right = k + "%";
    k += 20;
});


dateInput.addEventListener("keyup", function () {
    if (this.value.length > 0 && departureInput.value.length > 0 && arrivalInput.value.length > 0) {
        iconSwitch.classList.remove("fa-magnifying-glass-arrow-right");
        iconSwitch.classList.add("fa-circle-check");
    } else {
        iconSwitch.classList.remove("fa-circle-check");
        iconSwitch.classList.add("fa-magnifying-glass-arrow-right");
    }
});

departureInput.addEventListener("keyup", function () {
    if (dateInput.value.length > 0 && this.value.length > 0 && arrivalInput.value.length > 0) {
        iconSwitch.classList.remove("fa-magnifying-glass-arrow-right");
        iconSwitch.classList.add("fa-circle-check");
    } else {
        iconSwitch.classList.remove("fa-circle-check");
        iconSwitch.classList.add("fa-magnifying-glass-arrow-right");
    }
});

arrivalInput.addEventListener("keyup", function () {
    if (dateInput.value.length > 0 && departureInput.value.length > 0 && this.value.length > 0) {
        iconSwitch.classList.remove("fa-magnifying-glass-arrow-right");
        iconSwitch.classList.add("fa-circle-check");
    } else {
        iconSwitch.classList.remove("fa-circle-check");
        iconSwitch.classList.add("fa-magnifying-glass-arrow-right");
    }
});

revealBtn.addEventListener("click", function () {
    const moreOptions = document.getElementById("extended-search-fields");
    moreOptions.classList.toggle("hidden");
    moreOptions.classList.toggle("reveal-translate");
    this.classList.toggle("rotate-arrow");
});

// prevent use of wrong key inputs :
const validKeys = [ 
    'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm',
    'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
    'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
    'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
    'backspace', 'Tab', 'Enter', 'Shift', 'Control', 'Alt', 'Meta',
    'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Escape', 'Backspace',
    '\'', 'é', 'è', 'ê', 'ë', 'ç', 'à', 'â', 'ä', 'ô', 'ö', 'ù', 'û',
    'ü', 'î', 'ï', 'ô',
];
arrivalInput.addEventListener("keydown", function (event) {
    if (!validKeys.includes(event.key)) {
        event.preventDefault();
    }
});
departureInput.addEventListener("keydown", function (event) {
    if (!validKeys.includes(event.key)) {
        event.preventDefault();
    }
});

switchBtn.addEventListener("click", function () {
    let temp = arrivalInput.value;
    let temp2 = departureInput.value;
    arrivalInput.value = temp2;
    departureInput.value = temp;
});




// custom slider part designing :
let sliderPrice = document.querySelector('input.price-slider-custom');
let em = document.createElement('em');
em.classList.add('price-slider-em');
sliderPrice.insertAdjacentElement('beforebegin', em);
em.innerText = '€';
// USE INPUT EVENT for real time dynamic update of value :
sliderPrice.addEventListener( 'input', () => {
    em.innerText = sliderPrice.value + '€';
})
