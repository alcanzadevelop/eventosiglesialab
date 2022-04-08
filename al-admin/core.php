<?php
$username="u260263254_Eventos";
$db="u260263254_Eventos";
$password="u260263254Eventos";
#$servername = "localhost";
$servername = "185.211.7.1";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$db", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {

}