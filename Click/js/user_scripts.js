let profile = document.querySelector('.header .flex .profile-detail');
let searchForm = document.querySelector('header .flex .search-form');
let nav = document.querySelector('.navbar');

document.querySelector('#user-btn').onclick = () => {
    profile.classList.toggle('active');
    searchForm.classList.remove('active');
}

document.querySelector('#search-btn').onclick = () =>{
    searchForm.classList.toggle('active');
    profile.classList.remove('active');
}

document.querySelector('#menu-btn').onclick = () =>{
    nav.classList.toggle('active');
}

const imgBox = document.querySelector('.slider-container');
const slides = document.getElementsByClassName('slideBox');
var i = 0;

function nextSlide(){
    slides[i].classList.remove('active');
    i = (i + 1) % slides.length;
    slides[i].classList.add('active');
}

function prevSlide(){
    slides[i].classList.remove('active');
    i = (i - 1 + slides.length) % slides.length;
    slides[i].classList.add('active');
}