<?php

require_once 'db.php';

if (!isset($_SESSION['userId'])) {
    header('Location: login.php');
    exit();
}

$requete = $pdo->prepare('SELECT * FROM user_table ORDER BY username ASC');
$requete->execute();
$users = $requete->fetchAll();
?>