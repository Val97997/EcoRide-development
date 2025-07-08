//slider part 1
let sliderTop = document.getElementById("slider1");
let sliderBottom = document.getElementById("slider2");
let sliderContainer = document.getElementById("form-container");
sliderContainer.addEventListener('mouseover', function(){
    sliderBottom.style.transform = 'translateY(0rem)';
    sliderTop.style.transform = 'translateY(0rem)';
})
// slider part 2
let sliderTop2 = document.getElementById("slider3");
let sliderBottom2 = document.getElementById("slider4");
let sliderContainer2 = document.getElementById("form-container2");
sliderContainer2.addEventListener('mouseover', function(){
    sliderBottom2.style.transform = 'translateY(0rem)';
    sliderTop2.style.transform = 'translateY(0rem)';
})


//form registration styling and UX enrichment
let submitBtn = document.getElementById("submit-btn");
let passwLabel = document.getElementById("");
let pseudoInput = document.getElementById("registration_form_pseudo");
let fnameInput = document.getElementById("registration_form_first_name");
let lnameInput = document.getElementById("registration_form_last_name");
let passwordInput = document.getElementById("registration_form_plainPassword");
const passwRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/;
let confirmPwInput = document.getElementById("registration_form_confirmPw");
let phoneInput = document.getElementById('registration_form_phone_nb')

confirmPwInput.disabled = true;
submitBtn.disabled =true;

passwordInput.addEventListener("keyup", function(){
    confirmPwInput.disabled = false;
    if((this.value).match(passwRegex)){
        this.style.color = "green";
    }
    else{
        this.style.color = "red";
    }
})

confirmPwInput.addEventListener("keyup", function(){
    if(this.value === passwordInput.value){
        submitBtn.disabled = false;
    }
    else {
        submitBtn.disabled = true;
    }
})
fnameInput.addEventListener("keyup", function(){
    pseudoInput.value = this.value + lnameInput.value + Math.floor(Math.random()* 10);
})
lnameInput.addEventListener("keyup", function(){
    pseudoInput.value = fnameInput.value + this.value + Math.floor(Math.random()* 10);
})

// display styling to make warnings readable
let flashMessages = document.querySelectorAll("ul");
let k = 5;
flashMessages.forEach((message) => {
    message.appendChild(
        document.createElement("i")
    );
    message.style.right = k + "%";
    k += 20;
});

// Role selection dynamic feedback in select element :
const driverRole = document.getElementById("driver");
const passengerRole = document.getElementById("passenger");
if(window.location == "http://127.0.0.1:8000/register/driver"){
    driverRole.checked = true;
    passengerRole.checked = false;
}
else{
    driverRole.checked = false;
    passengerRole.checked = true;
}
driverRole.addEventListener("click", function() {
    window.location.replace("driver");
})

passengerRole.addEventListener("click", function() {
    window.location.replace("passenger");
})

document.addEventListener("DOMContentLoaded", function( ) {
    const divRole = document.getElementById("role-choice");
    divRole.classList.add('slide-role-choice');
})

// Preventing the input of a non valid date (too recent) for the birth_date field :
// Only customers of at least 18 yrs old are allowed for legal reasons ?

let birthInput = document.getElementById('registration_form_birth_date');
// Calculate the date 18 years ago from today
let today = new Date();
let dateTreshold = new Date(today.getFullYear()-18, today.getMonth(), today.getDay());

// Format the date as YYYY-MM-DD
const formattedDate = dateTreshold.toISOString().split('T')[0];
// Set the max attribute of the date input
birthInput.max = formattedDate;

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
const validPhoneKeys = [
    '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '+',
    'backspace', 'Tab', 'Enter', 'Shift', 'Control', 'Alt', 'Meta',
    'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Escape', 'Backspace',
];
lnameInput.addEventListener('keydown', function(){
    if (!validKeys.includes(event.key)) {
        event.preventDefault();
    }
})
fnameInput.addEventListener('keydown', function(){
    if (!validKeys.includes(event.key)) {
        event.preventDefault();
    }
})
phoneInput.addEventListener('keydown', ()=>{
    if (!validPhoneKeys.includes(event.key)) {
        event.preventDefault();
    }
})