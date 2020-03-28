var slideIndex = 1;
var cars = [];

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
    if(slides[slideIndex - 1]) {
        slides[slideIndex - 1].style.display = "block";
        dots[slideIndex - 1].className += " active";
    }
    document.getElementById("carId").setAttribute("value", cars[slideIndex-1]['carId']);
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
    // var smazat = document.getElementById("smazat");
    var pridat = document.getElementById("pridat");
    var x = document.getElementById("x");
    vlozit.style.display = "block";
    // smazat.style.display = "none";
    x.style.display = "block";
    pridat.style.display = "none";
}

// smazání řádku

// function deleterow(){
//     var y = document.getElementById("delete-row");
//     var smazat = document.getElementById("smazat");
//     var pridat = document.getElementById("pridat");
//     var x = document.getElementById("x");
//     y.style.display = "block";
//     smazat.style.display = "none";
//     x.style.display = "block";
//     pridat.style.display = "none";
// }

// zavírací button
function zavrit(){
    var vlozit = document.getElementById("insert-row");
    var y = document.getElementById("delete-row");
    // var smazat = document.getElementById("smazat");
    var x = document.getElementById("x");
    var pridat = document.getElementById("pridat");
    vlozit.style.display = "none";
    // smazat.style.display = "inline-block";
    pridat.style.display = "inline-block";
    x.style.display = "none"; 
    y.style.display = "none";   
}




async function initialLoad() {
    const carsListContainer = document.getElementById("carsListContainer");

    const response = await fetch("https://kitlab.pef.czu.cz/1920zs/ete89e/03/php/cars.php?userId=1");
    cars = await response.json();

    for (let i = 0; i < cars.length; i++) {
        cars[i].totalMileage = cars[i]['registrationMileage'];

        const mySlides = document.createElement("div");
        mySlides.setAttribute("class", "mySlides");
        if(i===0) {
            mySlides.setAttribute("style", "display:block");
        }
        carsListContainer.appendChild(mySlides);

        const carLabel = document.createElement("text");
        carLabel.setAttribute("class", "text");
        carLabel.setAttribute("style", "display: block");
        carLabel.innerHTML = cars[i]['manufacturer'] + ' ' + cars[i]['model'];

        mySlides.appendChild(carLabel);

        const url = `https://www.carimagery.com/api.asmx/GetImageUrl?searchTerm=${cars[i]['manufacturer']}+${cars[i]['model']}`;

        const carImg = document.createElement("img");
        await loadCarImage(carImg, url);
        carImg.setAttribute("alt", cars[i]['model'] + 'car');
        mySlides.appendChild(carImg);

    }

    showSlides(1);
    reload();
    openTab('gasTab', 'gas');
}

async function loadCarImage(carImg, url) {
    const response = await fetch(url);
    const text = await response.text();
    const htmlResponse = document.createElement('html');
    htmlResponse.innerHTML = text;
    carImg.setAttribute("src", htmlResponse.getElementsByTagName("string").item(0).textContent);
}

/**
 * Loading data
 * */
function reload() {
    loadHistoryTable();
    loadStatisticsTable();
    loadVehicleTable();
}

/**
 * Fuel History Table
 */
function loadHistoryTable() {
    fetch(`https://kitlab.pef.czu.cz/1920zs/ete89e/03/php/fuelHistory.php?carId=${cars[slideIndex-1].carId}`)
        .then((response) => {
            return response.json();
         })
        .then((data) => {
            //fill gui
            const historyTableContainer = document.getElementById('gas');
            const historyTable = document.getElementById("historyTable");
            if(data === null || data.length === 0) {
                historyTable.innerHTML = "";
            } else {
                countRowData(data);
                cars[slideIndex - 1].totalMileage = data[0]['mileage'];
                fillDataByProperty("totalMileage", cars[slideIndex - 1]);
                historyTableContainer.replaceChild(buildHtmlTable(data), historyTable);
                Tablesaw.init(historyTableContainer);
            }
        });
}

function countRowData(data) {
    for (let i = 0; i < data.length; i++) {
        let trip = 0;
        if(i === data.length - 1) {
            data[i].trip = data[i]['mileage'] - cars[slideIndex-1]['registrationMileage'];
        } else {
            data[i].trip = data[i]['mileage']- data[i+1]['mileage'];
        }
        data[i].consumption = parseFloat( (data[i]['units'] / data[i]['trip']) * 100).toFixed(2);
        data[i].priceForOneKm = parseFloat(data[i]['totalPrice'] / data[i]['trip']).toFixed(2);

        data[i].date = new Date(data[i].date);
        var formatedDate = data[i].date.getDate()  + ". " + (data[i].date.getMonth() + 1) + ". " + data[i].date.getFullYear();
        data[i].date = formatedDate;

        // data[i].date = data[i].date.split(' ')[0];
    }

}

// Builds the HTML Table out of myList json data from Ivy restful service.
function buildHtmlTable(arr) {
    var table = document.createElement("table");
    table.setAttribute("id", "historyTable");
    table.innerHTML = "";

    table.setAttribute("class", "tablesaw tablesaw-stack");
    table.setAttribute("data-tablesaw-mode", "stack");

    var columns = addAllColumnHeaders(arr, table);
    var tbody = document.createElement('tbody');
    table.appendChild(tbody);
    for (var i = 0, maxi = arr.length; i < maxi; ++i) {
        var tr = document.createElement('tr');
        for (var j = 0, maxj = columns.length; j < maxj; ++j) {
            var td = document.createElement('td');
            cellValue = arr[i][columns[j]];
            if(columns[j] !== "fhId") {
                td.appendChild(document.createTextNode(arr[i][columns[j]] || ''));
                tr.appendChild(td);
            }

            if(j === maxj - 1) {
                var tdDelete = document.createElement('td');
                var deleteBtn = document.createElement("div");
                deleteBtn.setAttribute("id", "deleteFh-" + arr[i]['fhId']);
                deleteBtn.setAttribute("onClick", "onRowDeleted(" + arr[i]['fhId'] + ");");
                deleteBtn.innerText = "Smazat";
                deleteBtn.setAttribute("class", "deleteRowBtn");
                deleteBtn.value = arr[i]['fhId'];
                tdDelete.appendChild(deleteBtn);
                tr.appendChild(tdDelete);
            }
        }

        tbody.appendChild(tr);
    }
    return table;
}

// Adds a header row to the table and returns the set of columns.
// Need to do union of keys from all records as some records may not contain
// all records
function addAllColumnHeaders(arr, table) {
    var columnSet = [],
        thead = document.createElement('thead');
    tr = document.createElement('tr');

    thead.appendChild(tr);
    for (var i = 0; i < arr.length; i++) {
        var j = 0;
        for (var key in arr[i]) {
            if (arr[i].hasOwnProperty(key) && columnSet.indexOf(key) === -1) {

                columnSet.push(key);
                var th = document.createElement('th');
                th.setAttribute("scope", "col");
                th.setAttribute("data-tablesaw-priority", columnSet.indexOf(key));
                if(key !== "fhId") {
                    tr.appendChild(th);
                    th.appendChild(document.createTextNode(translate(key)));
                }
                if(j === Object.getOwnPropertyNames(arr[i]).length -1) {
                    var thId = document.createElement('th');
                    tr.appendChild(thId);
                    thId.appendChild(document.createTextNode(""));
                    // thId.setAttribute("style", "display:none");
                }
                j++;
            }
        }
    }
    table.appendChild(thead);
    return columnSet;
}

function translate(key) {
    switch (key) {
        case "date": return "Datum";
        case "mileage": return "Stav tachometru";
        case "units": return "Litry";
        case "priceForOneUnit": return "Cena/l";
        case "totalPrice": return "Cena celkem";
        case "trip" : return "TRIP";
        case "consumption": return "Spotřeba";
        case "priceForOneKm": return "Cena za 1 Km";
        default : return key;
    }
}

/***
 * Statistics Tab;e
 */
function loadStatisticsTable(data) {
    for (const property in data) {
        fillDataByProperty(property, data);
    }
}


/**
 * Vehicle table
 * */
function loadVehicleTable() {
    for (const property in cars[slideIndex-1]) {
        fillDataByProperty(property, cars[slideIndex-1]);
    }
}

function fillDataByProperty(property, data) {
    if(document.getElementById(property)) {
        document.getElementById(property).innerText = data[property];
    }
}


function onRowDeleted(fhId) {
    showAjaxLoading();

    var request = new XMLHttpRequest();
    request.open('DELETE', './php/fuelHistory.php', true);
    request.onload = function() {
        if (this.status >= 200 && this.status < 400) {
            // Success!
            // var resp = this.response;
            reload();
            hideAjaxLoading();
        } else {
            // We reached our target server, but it returned an error
            alert("Problém s kontaktováním serveru, zkontrolujte připojení");
            hideAjaxLoading();
        }
    };
    request.send("fhId=" + fhId);
}

function onRowAdded() {
    showAjaxLoading();
    var form = document.getElementById("addRecordForm");
    var data = new FormData(form);

    var request = new XMLHttpRequest();
    request.open('PUT', './php/fuelHistory.php', true);
    request.onload = function() {
        if (this.status >= 200 && this.status < 400) {
            // Success!
            // console.log(this.response);
            reload();
            hideAjaxLoading();
        } else {
            // We reached our target server, but it returned an error
            alert("Problém s kontaktováním serveru, zkontrolujte připojení");
            hideAjaxLoading();
        }
    };
    var dataString = "carId=" + data.get('carId') + "&date=" + data.get('date') + "&liters=" + data.get('liters') + "&literPrice=" + data.get('literPrice') + "&mileage=" + data.get('mileage');
    // console.log(dataString);
    request.send(dataString);

}

function showAjaxLoading() {
    document.getElementById("ajaxLoader").style = "display: block;";
}

function hideAjaxLoading() {
    document.getElementById("ajaxLoader").style = "display: none;";
}
