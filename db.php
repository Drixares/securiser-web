<?php
  require_once "env.php";
  try {
    $pdo = new PDO('mysql:host=localhost;dbname=' . $DB_NAME, $DB_USER,  $DB_PASSWORD);
    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch(Exception $e) {
    die('Erreur : ' . $e->getMessage());
  }
?>