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
function openTab(selectedTabId, tabContentId) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(tabContentId).style.display = "block";
    document.getElementById(selectedTabId).className += " active";
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
