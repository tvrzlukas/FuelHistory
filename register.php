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

            if (isset($_POST['register-btn'])){

                $username = mysqli_real_escape_string($cnn, $_POST['username']);
                $email = mysqli_real_escape_string($cnn, $_POST['email']);
                $password = mysqli_real_escape_string($cnn, $_POST['password']);
                $password2 = mysqli_real_escape_string($cnn, $_POST['password2']);

                // kontrola
                $a = "SELECT * FROM USERS WHERE username='$username' OR email='$email'";
                $a_res = mysqli_query($cnn, $a);

                if ( mysqli_num_rows($a_res) == 0 )
                {
                    if ($password == $password2){
                        $h_password = md5($password);
                        $sql = "INSERT INTO USERS (username,password,email) VALUES ('$username','$h_password','$email')";
                        if ($cnn->query($sql) === TRUE){
                            $info = "Registrace proběhla v pořádku, můžete se přihlásit";
                            header("Location: login.php");
                        }
                        else {
                            echo "Error: " . $sql . "<br>" . $cnn->error;
                        }
                        $_SESSION['success'] = "Registrace proběhla úspěšně";
                    }
                    else {
                        $info = "Zadaná hesla se neshodují";
                    }
                }
                else {
                    $info = "Uživatelské jméno nebo emailová adresa je již používaná";
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

        <div class="container" id="registrace" style="display: block;">
            
                <section>
                    <h2>Registrace</h2>

                    <div class="error"><?php echo $info ?></div>

                    <form method="post" action="register.php">
                        <input type="text" name="username" placeholder="Uživatelské jméno" values="<?php echo $username; ?>" required>
                        <input type="email" name="email" placeholder="E-mailová adresa" values="<?php echo $email; ?>" required>
                        <input type="password" name="password" placeholder="Heslo" required>
                        <input type="password" name="password2" placeholder="Kontrola hesla" required>
                        <input type="submit" name="register-btn" value="Registrovat">
                    </form>

                    <p style="color: white;"><a href="login.php" class="odkaz">Přihlásit se</a></p>


                </section>

        </div>

    </body>
</html>