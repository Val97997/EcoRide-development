const dateInput = document.getElementById('car_registration_date');

// Preventing the input of a non valid date not in the past
let today = new Date();
let dateTreshold = new Date(today.getFullYear(), today.getMonth(), today.getDay());

// Format the date as YYYY-MM-DD
const formattedDate = dateTreshold.toISOString().split('T')[0];
// Set the max attribute of the date input
dateInput.max = formattedDate;

const plusIcon = document.getElementById('new-car-auto-input');
let modelInput = document.getElementById('car_model');
plusIcon.addEventListener('click', () => {
    modelInput.focus();
})

plusIcon.addEventListener('mousedown', () => {
    modelInput.style.scale = '.95';
})

plusIcon.addEventListener('mouseup', () => {
    modelInput.style.scale = '1';
})