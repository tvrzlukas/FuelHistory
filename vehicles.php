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
        <link rel="stylesheet" href="./lib/stackonly/tablesaw.stackonly.css"> <!-- Table stylesheet -->
        <script src="./lib/stackonly/tablesaw.stackonly.js"></script>
        <script src="./lib/stackonly/tablesaw-init.js"></script>
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

            $username = $_SESSION['username'];
            $loginDb = "SELECT username FROM f123831.USERS WHERE username ='$username'";
            $query1 = mysqli_query($cnn, $loginDb);
            $emailDb = "SELECT email FROM f123831.USERS WHERE username ='$username'";
            $query2 = mysqli_query($cnn, $emailDb);



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

                    <form action="#">

                        <label for="znacka">Značka</label><br>
                        <input type="text" id="znacka" placeholder="Ford" ><br>
                        <label for="model">Model vozidla</label><br>
                        <input type="text" id="model" placeholder="Focus" ><br>
                        <label for="spz">SPZ</label><br>
                        <input type="text" id="spz" placeholder="5AP 1099" ><br>
                        <label for="motorizace">Motorizace</label><br>
                        <input type="text" id="motorizace" placeholder="1.0 Ecoboost" ><br>
                        <label for="vyroba">Rok výroby</label><br>
                        <input type="text" id="vyroba" placeholder="2015" ><br>
                        <label for="palivo">Palivo</label><br>
                        <input type="text" id="palivo" placeholder="Natural 95" ><br>
                        <label for="foto">Foto vozidla</label><br>
                        <input type="text" id="foto" placeholder="/assets/focus.png" ><br>
                        <input type="submit" id="change" value="Změnit">  
                        <input type="submit" id="delete" value="Smazat" style="background-color: red;">                     

                    </form>
              
                </section>
            </div>
       </div>

    </body>
</html>