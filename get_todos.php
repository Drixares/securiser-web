<?php 

session_start();
require_once 'db.php';

// On vérifie que l'utilisateur est connecté
if (!isset($_SESSION['userId'])) {
    header('Location: login.php');
    exit();
}

// On récupère les tâches de l'utilisateur
$requete = $pdo->prepare('SELECT * FROM todos WHERE user_id = :user_id ORDER BY due_date ASC');
$requete->execute(['user_id' => $_SESSION['userId']]);
$todos = $requete->fetchAll();
?>