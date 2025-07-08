import $ from 'jquery';
// Import the necessary styles and scripts
import './bootstrap';
import 'bootstrap';
import 'bootstrap-icons/font/bootstrap-icons.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import { waapi, animate, createDraggable, stagger, utils } from 'animejs';
import {Chart} from 'chart.js/auto';

window.Chart = Chart; // Make Chart.js globally available
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// app.js

// const $ = require('jquery');
// // this "modifies" the jquery module: adding behavior to it
// // the bootstrap module doesn't export/return anything
// require('bootstrap');

// // or you can include specific pieces


// anime.js animation setups NOTICE : condition is vital to avoid console warning and loading the full code on wrong pages
//login page
if(window.location.href == 'http://127.0.0.1:8000/login' || window.location.href == 'http://127.0.0.1:8080/login'){
  waapi.animate('.animate-title span', {
    translate: `0 -2rem`,
    delay: stagger(100),
    duration: 600,
    loop: 3,
    alternate: true,
    ease: 'inOut(2)',
  });
}

//list carshares page
if(window.location.href == 'http://127.0.0.1:8000/search' || window.location.href == 'http://127.0.0.1:8080/search'){
  animate('.search-page-big-hero h1', {
    opacity: [0, 1],
    translateY: ['-2rem', '0'],
    duration: 1000,
    ease: 'bounce(2, 0.3)',
    delay: stagger(100),
  });
  animate('.search-page-big-hero h4', {
    opacity: [0, 1],
    translateY: ['2rem', '0'],
    duration: 1000,
    ease: 'bounce(2, 0.3)',
    delay: stagger(100),
  });
}

// profile driver routes anim panel , check route and that user is driver by accessing driver only element on page:
if((window.location.href == 'http://127.0.0.1:8000/user/profile' || window.location.href == 'http://127.0.0.1:8080/user/profile')
&& document.getElementById('codeElem') != null){
  utils.set('.btn-reveal-routes', {z:100}, {snap: [90, 180]});
  let codeElem = document.getElementById('codeElem');
  const [$text] = utils.$('#btn-reveal-routes-title');
  const [$table] = utils.$('.table-carshare-listing');

  let offIcon = document.createElement('i');
  offIcon.classList.add('bi');
  offIcon.classList.add('bi-toggle-off');
  codeElem.insertAdjacentElement('afterbegin', offIcon);


  let draggable = createDraggable('.btn-reveal-routes', {
    x: { mapTo: 'rotateY' },
    y: { mapTo: 'z', snap: [90, 180] },
    onRelease: () => {
      $text.style.color = 'transparent';
      $table.classList.add('table-reveal-opac-anim');
      $table.classList.remove('table-reveal-opac-hidden');
      if(!offIcon.classList.contains('bi-toggle-on')){
        offIcon.classList.remove('bi-toggle-off');
        offIcon.classList.add('bi-toggle-on');
      }
    }
  });

  if(offIcon.classList.contains('bi-toggle-on')){
    draggable.disable();
  }
  if(document.getElementsByClassName('draggable-profile') != null){
  
    createDraggable('.draggable-profile',{
      x: false
    })
  }
}