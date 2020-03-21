<?php
$databaze = 'ete89e_1920zs_03';
$uzivatel = 'ete89e_1920zs_03';
$heslo = 'w2LLED';

if (!($cnn = mysqli_connect('localhost', $uzivatel, $heslo)))
	die('Nepodarilo se pripojit k databazovemu serveru.');
if (!mysqli_select_db($cnn, $databaze))
	die('Nepodarilo se otevrit databazi.');

echo 'Pripojeni k databazi bylo uspesne.';
