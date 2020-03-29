<?php
session_start();

if ($_SESSION['username'] == ""){
    $logged = "Uživatel nepřihlášen";
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Fuel History</title>
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="style_login.css">
        <link href="https://fonts.googleapis.com/css?family=Great%20Vibes" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
        <!-- Google Fonts -->

        <meta charset="UTF-8">
        <meta name="description" content="Aplikace pro evidování tankování">
        <meta name="keywords" content="tankování,aplikace,vozidlo,benzín,nafta,evidence,e-evidence">
        <meta name="viewport" content="width=device-width, initial-scale=1">

    </head>

    <body>

        <?php

            require("db.php");

            if (isset($_POST['login-btn'])){

                $username = mysqli_real_escape_string($cnn, $_POST["username"]);
                $password = mysqli_real_escape_string($cnn, $_POST["password"]);
                $password = md5($password);

                $sql = "SELECT * FROM USERS WHERE username='$username' AND password='$password'";
                $result = mysqli_query($cnn, $sql);

                    if ( mysqli_num_rows($result) == 1 ){
                        $_SESSION['username'] = $username;
                        header("location: app.php");
                    }
                else{
                    $info = "Chybně zadané heslo nebo uživatelské jméno";
                }                

            }

            $cnn->close();

        ?>
        
        <!-- Navigace -->
        <nav>
            <ul class="navbar">
                <li class="headerlogo">Fuel History</li>
                <li><a href="index.php">O aplikaci</a></li>
                <li class="hide"><a href="index.php#2">Cena</a></li>
                <li class="hide"><a href="index.php#3">Kontakt</a></li>  
                <li style="float: right;"><a href="login.php" class="login">Přihlásit</a></li>
                <li style="float: right;"><p><?php echo $logged; ?>&nbsp;&nbsp;</p></li>
            </ul>
        </nav>

        <!-- Content -->

        <div class="container" id="login">
            
                <section>
                    <h2>Přihlásit</h2>

                    <div class="success">
                        <?php
                            echo $_SESSION['success'];
                            unset($_SESSION['success']);
                        ?>                
                    </div>

                    <div class="error">
                        <?php
                            echo $info;
                        ?>                
                    </div>

                    <form action="login.php" method="post">
                        <input type="text" name="username" placeholder="Vaše uživatelské jméno" required>
                        <input type="password" name="password" placeholder="Heslo" required>
                        <input type="submit" name="login-btn" value="Přihlásit">
                    </form>
                    <p style="color: white;"><a href="register.php" class="odkaz">Registrovat</a></p>
                </section>

        </div>

    </body>
</html>