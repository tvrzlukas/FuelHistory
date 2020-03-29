<?php
session_start();

if ($_SESSION['username'] == ""){
    $logged = "Uživatel nepřihlášen";
}
else {
    $logged = "Přihlášený uživatel: ".$_SESSION['username']."  ";
}
if (isset($_GET['logout'])){
    session_destroy();
    unset($_SESSION['username']);
    header("location: index.php");
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Fuel History</title>
        <link rel="stylesheet" href="style.css">
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Great%20Vibes" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">

        <meta charset="UTF-8">
        <meta name="description" content="Aplikace pro evidování tankování">
        <meta name="keywords" content="tankování,aplikace,vozidlo,benzín,nafta,evidence,e-evidence">
        <meta name="viewport" content="width=device-width, initial-scale=1">

    </head>

    <body>
        
        <!-- Navigace -->
        <?php 

        if ($_SESSION['username'] == ""){ 
           echo"<nav>
            <ul class='navbar'>
                <li class='headerlogo'>Fuel History</li>
                <li><a href='index.php'>O aplikaci</a></li>
                <li class='hide'><a href='index.php#2'>Cena</a></li>
                <li class='hide'><a href='index.php#3'>Kontakt</a></li>  
                <li style='float: right;'><a href='login.php' class='login'>Přihlásit</a></li>
                <li style='float: right;''><p>". $logged ."&nbsp;&nbsp;</p></li>
            </ul>
        </nav>";
        }
        else{
            echo"<nav>
            <ul class='navbar'>
                <li class='headerlogo'>Fuel History</li>
                <li><a href='index.php'>O aplikaci</a></li>
                <li class='hide'><a href='index.php#2'>Cena</a></li>
                <li class='hide'><a href='index.php#3'>Kontakt</a></li>
                <li style='float: right;'><a href='index.php?logout='1'' class='logout'>Odhlásit</a></li>  
                <li style='float: right;'><a href='app.php' class='app'>Aplikace</a></li>
                <li style='float: right;''><p>". $logged ."&nbsp;&nbsp;</p></li>
            </ul>
        </nav>";
        }

        ?>

        <!-- Content -->

        <div class="container">
            
            <section class="about" id="1">
                <h2 class="heading">Fuel History</h2>
                
                <p>
                    Fuel History je aplikace pro sledování ujetých kilometrů, spotřeby paliva, nákladů při tankování a informací o vozidle.
                    S touto aplikací můžete sledovat historii tankování, náklady na tankování, průběžnou spotřebu, celkovou spotřebu, průměrnou cenu paliva 
                    při Vašich tankováních a další užitečné informace.
                </p>

            </section>

            <section class="examples">
                
                <h2>Možnosti evidování</h2>
                <div class="container-evidence">
                    <div><img src="assets/photo1.jpg" alt="Foto-informace o vozidlu" />
                        Informace o vozidlu</div>
                    <div><img src="assets/photo2.jpg" alt="Foto-mesicni naklady" />
                        Měsíční náklady na tankování</div>
                    <div><img src="assets/photo3.jpg" alt="Foto-prumerna spotreba" />
                        Průměrná spotřeba v daném období</div>
                </div>

            </section>

            <section class="pricing" id="2">
                
                <h2>Cena</h2>
                <div class="flex-container">
                    <div>
                        <h3>Zkušební verze</h3>
                            <span class="cena">O,-</span><br>
                            <span class="mesic">14-ti denní zkušební verze</span>   
                            <input id="vyzkouset" type="submit" value="Vyzkoušet" >  
                        <p>Zkušební verze aplikace s moduly Tankování, Statistiky pro jedno vozdidlo</p>
                          
                    </div>
                    <div>
                        <h3>Lite verze</h3>
                        <span class="cena">39,-</span><br>
                        <span class="mesic">CZK/měsíc</span><br>
                        <input type="submit" value="Objednat">  
                        <p>Moduly Tankování, Statistiky pro jedno vozdidlo</p>
                    </div>
                    <div>
                        <h3>Prémium verze</h3>
                        <span class="cena">199,-</span><br>
                        <span class="mesic">CZK/měsíc</span><br>
                        <input type="submit" value="Objednat" >  
                        <p>Moduly Tankování, Statistiky pro libovolný počet vozidel</p>
                        
                    </div>
                </div>

            </section>


            <section class="contact" id="3">
                
                <h2>Kontakt</h2>

                <form class="contactform" name="f" onsubmit="return kontrola_formulare();">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="E-mail" >
                    <label for="subject">Předmět</label>
                    <input type="text" id="subject" name="subject" placeholder="Předmět" >
                    <label for="mess">Zpráva</label>
                    <textarea id="mess" name="mess" placeholder="Vaše zpráva" style="height: 200px;" ></textarea>
                    <input id="odeslat" type="submit" value="Odeslat" >  
                </form>     
            
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
    </body>
</html>