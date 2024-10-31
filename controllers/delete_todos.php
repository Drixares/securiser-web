<?php
  session_start();
  require_once 'db.php';

  $userId = $_SESSION['userId'];

  if (!isset($userId) || empty($userId)) {
    header('Location: login.php');
    exit();
  }
  
  if (isset($_POST['delete_todo']) && (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['csrf_todo_delete'])) {
    die('Token CSRF invalide');
  }
  
  $todoId = $_POST['todo_id'];

  try {
    $deleteTodo = $pdo->prepare(
      'DELETE FROM todos WHERE id = :todoId AND user_id = :userId'
    );
  
    $deleteTodo->execute([
      'todoId' => $todoId,
      'userId' => $userId
    ]);
    $todo = $deleteTodo->fetch();

    header('Location: dashboard.php');

  } catch (Exception $e) {
    die('<br>Erreur : ' . $e->getMessage());
  }

?>