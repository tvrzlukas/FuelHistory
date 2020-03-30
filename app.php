<?php
session_start();

if(!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Fuel History</title>
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="appstyle.css">
        <link href="https://fonts.googleapis.com/css?family=Great%20Vibes" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="./lib/stackonly/tablesaw.stackonly.css"> <!-- Table stylesheet -->

        <link rel="stylesheet" href="lib/html5-simple-date-input-polyfill/html5-simple-date-input-polyfill.css" /><!--        Date input-->

        <script src="./lib/stackonly/tablesaw.stackonly.js"></script>

        <script>var sessionId= '<?php echo session_id(); ?>'</script>
        <script src="script.js"></script>

        <!-- Google Fonts -->
        <meta charset="UTF-8">
        <meta name="description" content="Aplikace pro evidování tankování">
        <meta name="keywords" content="tankování,aplikace,vozidlo,benzín,nafta,evidence,e-evidence">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>

    <body>
        <?php
            if (isset($_GET['logout'])){
                session_destroy();
                unset($_SESSION['username']);
                header("location: index.php");
            }
        ?>
              
        <!-- Navigace -->
        <nav>
            <ul class="navbar">
                <li class="headerlogo">Fuel History</li>
                <li><a href="index.php">O aplikaci</a></li>
                <li class="hide"><a href="index.php#2">Cena</a></li>
                <li class="hide"><a href="index.php#3">Kontakt</a></li>  
                <li style="float: right;"><a href="app.php?logout='1'" class="logout">Odhlásit</a></li>
                <li style="float: right;"><a href="vehicles.php" class="app">Vozidla</a></li>
                <li style="float: right;"><p>Přihlášený uživatel: <?php echo $_SESSION['username']; ?>&nbsp;&nbsp;</p></li>
                <li style="float: right;"><p>SessionId: <?php echo session_id(); ?>&nbsp;&nbsp;</p></li>
            </ul>
        </nav>

        <!-- Content -->

        <div class="container">
           
            <header>
                <div class="slider">
                    <!-- Slideshow container -->
                    <div class="slideshow-container">
                        <!-- Previous -->
            
                        <a id="prev" class="prev" onclick="plusSlides(-1);reload();">&#10094;</a>
            
                        <!-- Full-width images with number and caption text -->
                        <div id="carsListContainer">
                        </div>
            
                        <!-- Next -->
                        <a id="next" class="next" onclick="plusSlides(1);reload();">&#10095;</a>
            
                    </div>
            
                    <!-- The dots/circles -->
                    <div id="dotsContainer" class="dot-container">
                    </div>

<!--                    <div id="dots" class="dot-container">-->
<!--                        <span class="dot" onclick="currentSlide(1);reload();"></span>-->
<!--                        <span class="dot" onclick="currentSlide(2);reload();"></span>-->
<!--                    </div>-->
            
            
                </div>
            </header>

            <section>
                <div class="tab-container">

                    <!-- Tab links -->
                    <div class="tab">
                        <button id="gasTab" class="tablinks" onclick="openTab('gasTab', 'gas')">Tankování</button>
                        <button id="statisticsTab" class="tablinks" onclick="openTab('statisticsTab', 'statistics')">Statistiky</button>
                        <button id="vehiclesTab" class="tablinks" onclick="openTab('vehiclesTab', 'vehicles')">Vozidlo</button>
                    </div>
                
                    <!-- Tab content -->
                    <div id="gas" class="tabcontent">
                        <div class="buttonDiv">
                            <button id="pridat" class="pridat" onclick="insertNew()">Přidat záznam</button>
                            <button id="x" class="x" onclick="zavrit()">X</button>
                        </div>

                        <div class="insertrow" id="insert-row">
                            <h3>Nový záznam</h3>
                            <form id="addRecordForm" target="dummyframe" method="post" onsubmit="addRow()">
                                <input type="hidden" id="carId" name="carId" value="0">
                                <input type="date" name="date" title="Datum tankování" data-polyfill="all" required>
                                <input type="number" step="0.01" name="liters" placeholder="Množství tankovaného paliva" required>
                                <input type="number" step="0.01" name="literPrice" placeholder="Cena za litr" required>
                                <input type="number" name="mileage" placeholder="Stav km" required>
                                <input type="submit" value="Vložit">
                            </form>
                            <iframe name="dummyframe" id="dummyframe" style="display: none;"></iframe>


                        </div>

                        <img id="ajaxLoader" class="ajaxLoader" src="./assets/ajax-loader.gif" />


                        <div class="deleterow" id="delete-row">
                            <h3 style="text-align: center;">Smazat záznam</h3>
                            <form action="#" style="color: black;">
                                <input type="text" placeholder="Zvolte datum tankování které se má smazat" >
                                <input type="submit" style="background-color: red; border: none;" value="Smazat">
                            </form>
                        </div>


                        <table id="historyTable">
                        </table>

                    </div>
                
                    <div id="statistics" class="tabcontent" style="display: none;">

                        <h2>Statistiky jízd</h2>
                        <table id="stat_table" class="stat_table">

                            <tr>
                                <th>Název automobilu</th>
                                <td id="carName">Ford Focus - Trend 1.0 Ecoboost 74kW</td>
                            </tr>
                            <tr>
                                <th>Počet jednotlivých tankování</th>
                                <td id="totalRefuelCount">45</td>
                            </tr>
                            <tr>
                                <th>Celkové náklady na tankování od registrace</th>
                                <td id="totalCost">26 045 Kč</td>
                            </tr>
                            <tr>
                                <th>Měsíční náklady na tankování ( akutuální měsíc )</th>
                                <td id="monthlyCosts">849 Kč</td>
                            </tr>
                            <tr>
                                <th>Celkový nájezd v kilometrech od registrace</th>
                                <td id="totalDriven">11 744 km</td>
                            </tr>
                            <tr>
                                <th>Roční nájezd v kilometrech ( aktuální rok )</th>
                                <td id="yearlyDriven">5714 km</td>
                            </tr>
                            <tr>
                                <th>Průměrná spotřeba automobilu</th>
                                <td id="averageConsumption">6,81 l/100km</td>
                            </tr>
                            <tr>
                                <th>Průměrná cena za natankovaný litr paliva</th>
                                <td id="averagePrice">32,4 Kč/l</td>
                            </tr>
                            <tr>
                                <th>Počet tankování za aktuální rok</th>
                                <td id="yearlyRefuelCount">19</td>
                            </tr>

                        </table>

                        <img src="assets/big.jpg" class="photo" alt="background">

                    </div>
                
                    <div id="vehicles" class="tabcontent" style="display: none;">

                        <h2>Informace o vozidle</h2>

                        <table class="stat_table">

                            <tr>
                                <th>Značka vozidla</th>
                                <td id="manufacturer"></td>
                            </tr>
                            <tr>
                                <th>Model vozidla</th>
                                <td id="model"></td>
                            </tr>
                            <tr>
                                <th>Rok výroby</th>
                                <td id="year"></td>
                            </tr>
                            <tr>
                                <th>Najeté km při registraci</th>
                                <td id="registrationMileage"></td>
                            </tr>
                            <tr>
                                <th>Aktuální najeté km</th>
                                <td id="totalMileage"></td>
                            </tr>
                            <tr>
                                <th>SPZ</th>
                                <td id="licencePlate"></td>
                            </tr>
                            <tr>
                                <th>Motorizace</th>
                                <td id="engine"></td>
                            </tr>
<!--                            <tr>-->
<!--                                <th>Stupeň výbavy</th>-->
<!--                                <td id="modelType"></td>-->
<!--                            </tr>-->
                            <tr>
                                <th>Palivo</th>
                                <td id="fuelType"></td>
                            </tr>
                            

                        </table>

                                        
                    </div>
                
                </div>
            </section>

        </div>

        <section class="footer">
            <h2 class="heading">Fuel History</h2>
            <div>
                <p>Fuel History</p>
                <span class="bold">&copy; 2020</span><br>
                <span>Studentský projekt pro předmět Internetové technologie</span><br>
                <span>Využití fotodatabáze <a href="https://www.pexels.com/" target="_blank">https://www.pexels.com</a></span>
            </div>
            
        </section>

        <script>
            initialLoad();
        </script>

    </body>
</html>