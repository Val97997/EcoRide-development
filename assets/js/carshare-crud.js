// set up logical methods for restricting the date picking options for a new carshare :

let departureDateInput = document.getElementById('carshare_departure_date');
let arrivalDateInput = document.getElementById('carshare_arrival_date');
let departureHourInput = document.getElementById('carshare_departure_hour');
let arrivalHourInput = document.getElementById('carshare_arrival_hour');
let seatInput = document.getElementById('carshare_available_seats');
let priceInput = document.getElementById('carshare_price');


let today = new Date();
let intervalMax = 3;
const formattedDate = today.toISOString().split('T')[0];

departureDateInput.min = formattedDate;

// force the time window to be restricted : !! max duration => 3 days
departureDateInput.addEventListener('change', () => {
    let departureDate = new Date(departureDateInput.value);
    let maxArrivalDate = new Date(departureDate);
    maxArrivalDate.setDate(maxArrivalDate.getDate() + intervalMax);
    arrivalDateInput.max = maxArrivalDate.toISOString().split('T')[0];
    arrivalDateInput.min = departureDate.toISOString().split('T')[0] ;
});

departureHourInput.addEventListener('change', () => {
    let depHour = departureHourInput.value;
    console.log(depHour);
    arrivalHourInput.value = depHour;

})


// plus minus icon buttons
function increase(input, treshold, max){
    let temp = parseInt(input.value);
    if(temp < max){
        return input.value = temp+treshold;
    }
    else{
        return;
    }
}
function decrease(input, treshold, min){
    let temp = parseInt(input.value);
    if(temp > min){
        return input.value = temp - treshold;
    }
    else{
        return;
    }
}
document.getElementById('plus-seat').addEventListener('click', ()=>{
    increase(seatInput, 1, 25);
})

document.getElementById('minus-seat').addEventListener('click', ()=>{
    decrease(seatInput, 1, 1);
})

document.getElementById('plus-price').addEventListener('click', ()=>{
    increase(priceInput, 1, 1000);
})
document.getElementById('minus-price').addEventListener('click', ()=>{
    decrease(priceInput, 1, 1);
})

document.getElementById('big-plus').addEventListener('click', ()=>{
    increase(priceInput, 10, 1000);
})
document.getElementById('big-minus').addEventListener('click', ()=>{
    decrease(priceInput, 10, 0);
})
