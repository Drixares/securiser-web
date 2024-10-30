<?php 

  session_start();
  require_once 'db.php';

  if (isset($_POST['create_todos']) && (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['csrf_todo_create'])) {
    die('Token CSRF invalide');
  }

  unset($_SESSION['csrf_todo_create']);

  if (isset($_POST['create_todos'])) {

    echo "Traitement en cours...";
    $title = isset($_POST['title']) && !empty($_POST['title'])
      ? htmlspecialchars($_POST['title']) 
      : die("<p>Le titre est obligatoire</p>");
    
    $due_date = isset($_POST['due_date']) && !empty($_POST['due_date'])
      ? htmlspecialchars($_POST['due_date']) 
      : die("<p>La date est obligatoire</p>");

    $sauvegarde = $pdo->prepare(
      'INSERT INTO todos (title, due_date, user_id) VALUES (:title, :due_date, :user_id)'
    );

    $sauvegarde->execute([
      'user_id' => $_SESSION['userId'],
      'title' => $title,
      'due_date' => $due_date,
    ]);

    if ($sauvegarde->rowCount() > 0) {
      echo "<p>La tâche a bien été ajoutée</p>";
    } else {
      echo "<p>Une erreur est survenue lors de l'ajout de la tâche.</p>";
    }

    header('Location: dashboard.php');
  }



?>