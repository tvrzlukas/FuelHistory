<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Fuel History</title>
        <link rel="stylesheet" href="appstyle.css">
        <link rel="stylesheet" href="style.css">
        <link href="https://fonts.googleapis.com/css?family=Great%20Vibes" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">

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

            require("db.php");

            if (isset($_GET['logout'])){
                session_destroy();
                unset($_SESSION['username']);
                header("location: index.php");
            }

//            $username = $_SESSION['username'];
//            $loginDb = "SELECT username FROM f123831.USERS WHERE username ='$username'";
//            $query1 = mysqli_query($cnn, $loginDb);
//            $emailDb = "SELECT email FROM f123831.USERS WHERE username ='$username'";
//            $query2 = mysqli_query($cnn, $emailDb);



        ?>
              
        <!-- Navigace -->
        <nav>
            <ul class="navbar">
                <li class="headerlogo">Fuel History</li>
                <li><a href="index.php">O aplikaci</a></li>
                <li class="hide"><a href="index.php#2">Cena</a></li>
                <li class="hide"><a href="index.php#3">Kontakt</a></li>  
                <li style="float: right;"><a href="app.php?logout='1'" class="logout">Odhlásit</a></li>
                <li style="float: right;"><a href="app.php" class="app">Aplikace</a></li>
                <li style="float: right;"><p>Přihlášený uživatel: <?php echo $_SESSION['username']; ?>&nbsp;&nbsp;</p></li>
                <li style="float: right;"><p>SessionId: <?php echo session_id(); ?>&nbsp;&nbsp;</p></li>

            </ul>
        </nav>

        <!-- Content -->

       <div class="container">
            <h2 style="text-align: center;">Nastavení</h2>
            <div>
                <section class="settings">

                    <h3>Základní údaje</h3>
                    <form action="#">

                        <label for="username">Login</label><br>
                        <input type="text" id="username" readonly style="background-color: lightgray;"> <br>
                        <label for="email">E-mailová adresa</label>  <br>     
                        <input type="text" id="email" readonly style="background-color: lightgray;"> <br>

                    </form>

                </section>

                <section class="settings">

                    <h3>Údaje o vozidle</h3>

                    <form id="addVehicle" target="dummyframe2" method="post" onsubmit="addCar()">

                        <label>Značka</label><br>
                        <input type="text"><br>

                        <label for="model">Model vozidla</label><br>
                        <input type="text" name="model"><br>

                        <label for="spz">SPZ</label><br>
                        <input type="text" name="spz"><br>

                        <label for="motorizace">Motorizace</label><br>
                        <input type="text" name="cubics"><br>

                        <label for="power">Výkon</label><br>
                        <input type="number" name="power"><br>

                        <label for="vyroba">Rok výroby</label><br>
                        <input type="text" name="year"><br>

                        <label for="palivo">Palivo</label><br>
                        <input type="text" name="fuelType"><br>

                        <label for="registrationMileage">Najeté kilometry</label><br>
                        <input type="text" name="registrationMileage"><br>

                        <input type="submit" value="Přidat">
<!--                        <input type="submit" id="change" value="Změnit">-->
<!--                        <input type="submit" id="delete" value="Smazat" style="background-color: red;">                     -->

                    </form>
                    <iframe name="dummyframe2" id="dummyframe2" style="display: none;"></iframe>
              
                </section>
            </div>
       </div>

        <img id="ajaxLoader" class="ajaxLoader" src="./assets/ajax-loader.gif" />

    </body>

    <script>
        function addCar() {
            showAjaxLoading();

            const form = document.getElementById('addVehicle');
            const data = new FormData(form);
            console.log(Array.from(data));
            const request = new XMLHttpRequest();
            request.open('PUT', `./api/cars.php?sessionId=${sessionId}`, true);
            request.onload = function() {
                if (this.status >= 200 && this.status < 400) {
                    // Success!
                    console.log(this.response);
                    // reload();
                    hideAjaxLoading();
                } else {
                    // We reached our target server, but it returned an error
                    alert("Problém s kontaktováním serveru, zkontrolujte připojení");
                    hideAjaxLoading();
                }
            };
            const dataString = "model=" + data.get('spz') + "&spz=" + data.get('date') + "&cubics=" + data.get('cubics') + "&power=" + data.get('power')+ "&year=" + data.get('year') + "&fuelType=" + data.get('fuelType') + "&registrationMileage=" + data.get('registrationMileage');
            console.log(dataString);
            request.send(dataString);

        }
    </script>
</html>