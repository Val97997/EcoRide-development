let btn = document.getElementById('hook-button');
let semicirclebtn = document.getElementById('semicirclebtn');

btn.addEventListener('click', function() {
    semicirclebtn.style.visibility = 'visible';
    btn.style.visibility = 'hidden';
});

let carouselbtnprev = document.getElementById("carousel-prev");
let carouselbtnnext = document.getElementById("carousel-next");
let carouselSlider = document.getElementById("carousel-slider");
carouselbtnprev.addEventListener('mouseenter', function(){
    carouselSlider.classList.remove("anim-next");
    carouselSlider.classList.add('anim-back');
});
carouselbtnnext.addEventListener('mouseenter', function(){
    carouselSlider.classList.remove("anim-back");
    carouselSlider.classList.add('anim-next');
});
carouselbtnprev.addEventListener('mouseleave', function(){
});

carouselbtnnext.addEventListener('mouseleave', function(){
});


let btnPassenger = document.getElementById('btn-passenger');
let btnDriver = document.getElementById('btn-driver');


