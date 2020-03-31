<?php
session_start();

require("db.php");

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

//nacteni vyrobcu a jejich modelu z db do pole $manufacturers
$stmt = $cnn->prepare("SELECT
                        ID_MA as manufacturerId,
                        NAME as manufacturer
                    FROM MANUFACTURER;
                    ");
$stmt->execute();
$result = $stmt->get_result();
$manufacturers = [];
while ($row = $result->fetch_assoc()) {
    $manufacturer = new IdNameHolder();
    $manufacturer->id = $row['manufacturerId'];
    $manufacturer->name = $row['manufacturer'];
    array_push($manufacturers, $manufacturer);
}
$stmt->close();

class IdNameHolder
{
    var $id;
    var $name;
}

//HashMapa pro comba - vyrobce a model
function createJSONModelMap($cnn) {
    $stmt = $cnn->prepare("SELECT
                        ID_MA as manufacturerId,
                        ID_MO as modelId,
                        NAME as name
                    FROM MODEL;
                    ");
    $stmt->execute();
    $result = $stmt->get_result();
    $map = array();
    $currentManufacturer = 0;
    $manufacturerModels = [];
    while ($row = $result->fetch_assoc()) {
        $model = new IdNameHolder;
        $model->id = $row['modelId'];
        $model->name = $row['name'];

        if($currentManufacturer != $row['manufacturerId']) {
            //dosahl jsem na dalsiho vyrobce aut
            $currentManufacturer = $row['manufacturerId'];
            $manufacturerModels = [];
        }
        array_push($manufacturerModels, $model);
        $map[$row['manufacturerId']] = $manufacturerModels;
    }
    $stmt->close();
    echo json_encode($map);
}



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
        <script>var modelsMap=<?php createJSONModelMap($cnn)?></script>
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
                <li style="float: right;"><a href="app.php" class="app">Aplikace</a></li>
                <li style="float: right;"><p>Přihlášený uživatel: <?php echo $_SESSION['username']; ?>&nbsp;&nbsp;</p></li>
                <li style="float: right;"><p>SessionId: <?php echo session_id(); ?>&nbsp;&nbsp;</p></li>

            </ul>
        </nav>

        <!-- Content -->

       <div class="container">
            <h2 style="text-align: center;">Vozidla</h2>
            <div>
                <?php


                $stmt = $cnn->prepare("SELECT
                        USER_VEHICLES.ID_UV as carId,
                        MANUFACTURER.NAME as manufacturer,
                        MANUFACTURER.ID_MA as manufacturerId,
                        MODEL.NAME as model,
                        MODEL.ID_MO as modelId,
                        USER_VEHICLES.MAN_YEAR as 'year',
                        USER_VEHICLES.LIC_PLATE as licencePlate,
                        USER_VEHICLES.REGISTRATION_MILEAGE as registrationMileage,
                        USER_VEHICLES.CUBICS as cubics,
                        USER_VEHICLES.POWER as power,
                        FUEL_TYPE.TYPE as fuelType
                    FROM USER_VEHICLES
                    INNER JOIN MODEL
                    ON USER_VEHICLES.ID_MO = MODEL.ID_MO
                    INNER JOIN MANUFACTURER
                    ON MODEL.ID_MA = MANUFACTURER.ID_MA
                    INNER JOIN FUEL_TYPE
                    ON USER_VEHICLES.ID_FT = FUEL_TYPE.ID_FT
                    INNER JOIN USERS
                    ON USER_VEHICLES.ID_US = USERS.ID_US
                    WHERE USERS.USERNAME = ?
                    ");
                $stmt->bind_param("s", $_SESSION['username']);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
//                    $myArray[] = $row;
                    echo "<section id='section-". $row["carId"] ."' class=\"settings\">
                        <h3>Údaje o vozidle   ". $row["manufacturer"] ." ". $row["model"] ."</h3>
                        <form id='form-". $row["carId"] ."'  target=\"dummyframe\" method=\"post\" >";


                    echo "   
                            <label for=\"spz-".$row["carId"]."\">SPZ</label><br>
                            <input type=\"text\" name=\"spz\" id=\"spz-".$row["carId"]."\" value='". $row["licencePlate"] ."' required><br>
    
                            <label for=\"motorizace-".$row["carId"]."\">Objem motoru v litrech</label><br>
                            <input type=\"number\" step='0.1' name=\"cubics\" id=\"motorizace-".$row["carId"]."\" value='". $row["cubics"] ."' required><br>
    
                            <label for=\"power-".$row["carId"]."\">Výkon</label><br>
                            <input type=\"number\" name=\"power\" id=\"power-".$row["carId"]."\" value='". $row["power"] ."' required><br>
    
                            <label for=\"vyroba-".$row["carId"]."\">Rok výroby</label><br>
                            <input type=\"number\" maxlength=\"4\" name=\"year\"  id=\"vyroba-".$row["carId"]."\" value='". $row["year"] ."' required><br>
    
                            <label for=\"registrationMileage-".$row["carId"]."\">Najeté kilometry při registraci</label><br>
                            <input type=\"number\" name=\"registrationMileage\" id=\"registrationMileage-".$row["carId"]."\" value='". $row["registrationMileage"] ."' required><br>
                          
                            <input type=\"submit\" id=\"change\" value=\"Změnit\" style=\"background-color: orange;\" onclick='editCar(". $row["carId"] .")'>
                            <input type=\"submit\" id=\"delete\" value=\"Smazat\" style=\"background-color: red;\" onclick='removeCar(". $row["carId"] .")'>  

                    </form>

                </section>
                        ";

                }
                $stmt->close();
                ?>

                <h2 style="text-align: center;">Přidat vozidlo</h2>

                <section class="settings">

                    <h3>Údaje o novém vozidle</h3>

                    <form id="addVehicle" target="dummyframe" method="post" onsubmit="addCar()">

                        <?php
                            echo "<label>Značka</label><br>";
                            echo "<select onchange=\"updateManufacturerModels('model', this)\">";
                            echo "<option>-- Značka --</option>";
                            foreach ($manufacturers as $manufacturer){
                                echo "<option value=\"".$manufacturer->id."\">".$manufacturer->name."</option>";
                            }
                            echo "</select><br><label>Model</label><br>";

                            echo "<select id='model' name='model'><option>-- Model --</option></select><br>";
                        ?>

                        <label for="newSpz">SPZ</label><br>
                        <input type="text" name="spz" id="newSpz" required><br>

                        <label for="newMotorizace">Objem motoru v litrech</label><br>
                        <input type="number" step='0.1' name="cubics" id="newMotorizace" required><br>

                        <label for="newPower">Výkon</label><br>
                        <input type="number" name="power" id="newPower" required><br>

                        <label for="newVyroba">Rok výroby</label><br>
                        <input type="number" name="year" id="newVyroba" required><br>

                        <label for="newfuelType">Palivo</label><br>
                        <select name="fuelType" id="newfuelType" required>
                            <option value="1">--</option>
                            <option value="2">Benzin</option>
                            <option value="3">Diesel</option>
                            <option value="4">LPG-Benzin</option>
                            <option value="5">CNG</option>
                        </select><br>

                        <label for="registrationMileage">Najeté kilometry při registraci</label><br>
                        <input type="number" name="registrationMileage" id="registrationMileage" required><br>

                        <input type="submit" value="Přidat">


                    </form>

                </section>
            </div>
       </div>

        <iframe name="dummyframe" id="dummyframe" style="display: none;"></iframe>
        <img id="ajaxLoader" class="ajaxLoader" src="./assets/ajax-loader.gif" />



        <?php

        ?>

    </body>

    <script>

        function updateManufacturerModels(id, selectedObject) {
            const manufacturer = selectedObject.value;
            const modelSelect = document.getElementById(id);
            modelSelect.innerHTML = "";
            for (const models in modelsMap[manufacturer]) {
                // for(const model in modelsMap[manufacturer][models]) {
                //     console.log(model);
                    const option = document.createElement("option");
                    option.setAttribute("value", modelsMap[manufacturer][models]['id']);
                    option.innerText = modelsMap[manufacturer][models]['name'];
                    modelSelect.appendChild(option);
                // }
            }
        }

        function removeCar(carId) {
            showAjaxLoading();

            const form = document.getElementById(`form-${carId}`);
            const data = new FormData(form);
            console.log(Array.from(data));
            const request = new XMLHttpRequest();
            request.open('DELETE', `./api/cars.php?sessionId=${sessionId}`, true);
            request.onload = function() {
                if (this.status >= 200 && this.status < 400) {
                    // Success!
                    console.log(this.response);
                    // reload();
                    document.getElementById(`section-${carId}`).remove();
                    hideAjaxLoading();
                } else {
                    // We reached our target server, but it returned an error
                    alert("Problém s kontaktováním serveru, zkontrolujte připojení");
                    hideAjaxLoading();
                }
            };
            const dataString = `carId=${carId}`;
            request.send(dataString);

        }

        function editCar(carId) {
            showAjaxLoading();

            const form = document.getElementById(`form-${carId}`);
            const data = new FormData(form);
            console.log(Array.from(data));
            const request = new XMLHttpRequest();
            request.open('PUT', `./api/cars.php?sessionId=${sessionId}`, true);
            request.onload = function() {
                if (this.status >= 200 && this.status < 400) {
                    // Success!
                    console.log(this.response);
                    if(this.response == 1) {
                        alert("Změny úspěšně uloženy");
                    } else {
                        alert("Změny se nepodařilo uložit");
                    }
                    // reload();
                    hideAjaxLoading();
                } else {
                    // We reached our target server, but it returned an error
                    alert("Problém s kontaktováním serveru, zkontrolujte připojení");
                    hideAjaxLoading();
                }
            };
            const dataString = "spz=" + data.get('spz') + "&model=" + data.get('model') + "&cubics=" + data.get('cubics') + "&power=" + data.get('power')+ "&year=" + data.get('year') + "&fuelType=" + data.get('fuelType') + "&registrationMileage=" + data.get('registrationMileage') + "&carId=" + carId;
            console.log(dataString);
            request.send(dataString);

        }


        function addCar() {
            showAjaxLoading();

            const form = document.getElementById('addVehicle');
            const data = new FormData(form);
            console.log(Array.from(data));
            const request = new XMLHttpRequest();
            request.open('POST', `./api/cars.php?sessionId=${sessionId}`, true);
            request.onload = function() {
                if (this.status >= 200 && this.status < 400) {
                    // Success!
                    console.log(this.response);
                    // reload();
                    hideAjaxLoading();
                    location.replace("app.php");
                } else {
                    // We reached our target server, but it returned an error
                    alert("Problém s kontaktováním serveru, zkontrolujte připojení");
                    hideAjaxLoading();
                }
            };
            const dataString = "model=" + data.get('model') + "&spz=" + data.get('spz') + "&cubics=" + data.get('cubics') + "&power=" + data.get('power')+ "&year=" + data.get('year') + "&fuelType=" + data.get('fuelType') + "&registrationMileage=" + data.get('registrationMileage');
            console.log(dataString);
            request.send(dataString);

        }
    </script>
</html>