<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$servername = "10.10.0.21";
$username = "labuser";
$password = "LabPass123!";
$dbname = "acme_db";

// Créer la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>