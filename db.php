
<?php

$databaze = 'ete89e_1920zs_03';
$uzivatel = 'ete89e_1920zs_03';
$heslo = 'w2LLED';

$cnn = mysqli_connect('localhost', $uzivatel, $heslo);
if (!$cnn)
    die('Nepodarilo se pripojit k databazovemu serveru.');
if (!mysqli_select_db($cnn, $databaze))
    die('Nepodarilo se otevrit databazi.');

?>