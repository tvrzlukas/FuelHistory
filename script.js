var slideIndex = 1;
showSlides(slideIndex);

// Next/previous controls
function plusSlides(n) {
    showSlides(slideIndex += n);
}

// Thumbnail image controls
function currentSlide(n) {
     showSlides(slideIndex = n);
}

function showSlides(n) {
    var i;
    var slides = document.getElementsByClassName("mySlides");
    var dots = document.getElementsByClassName("dot");
    if (n > slides.length) {
        slideIndex = 1
    }
    if (n < 1) {
        slideIndex = slides.length
    }
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }
    slides[slideIndex - 1].style.display = "block";
    dots[slideIndex - 1].className += " active";
}

// Funkce na přechod mezi jednotlivými Taby

function openTab(tabName){
    var i;
    var x = document.getElementsByClassName("tabcontent");
    for(i=0;i < x.length; i++){
        x[i].style.display = "none";
    }
    document.getElementById(tabName).style.display = "block";
}

// přidání nového řádku

function insertNew(){
    var vlozit = document.getElementById("insert-row");
    var smazat = document.getElementById("smazat");
    var pridat = document.getElementById("pridat");
    var x = document.getElementById("x");
    vlozit.style.display = "block";
    smazat.style.display = "none";
    x.style.display = "block";
    pridat.style.display = "none";
}

// smazání řádku

function deleterow(){
    var y = document.getElementById("delete-row");
    var smazat = document.getElementById("smazat");
    var pridat = document.getElementById("pridat");
    var x = document.getElementById("x");
    y.style.display = "block";
    smazat.style.display = "none";
    x.style.display = "block";
    pridat.style.display = "none";
}

// zavíací button

function zavrit(){
    var vlozit = document.getElementById("insert-row");
    var y = document.getElementById("delete-row");
    var smazat = document.getElementById("smazat");
    var x = document.getElementById("x");
    var pridat = document.getElementById("pridat");
    vlozit.style.display = "none";
    smazat.style.display = "inline-block";
    pridat.style.display = "inline-block";
    x.style.display = "none"; 
    y.style.display = "none";   
}
