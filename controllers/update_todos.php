<?php
  session_start();
  require_once '../includes/db.php';

  $userId = $_SESSION['userId'];
  

  if (!isset($userId) || empty($userId)) {
    header('Location: login.php');
    exit();
  }
  
  if (isset($_POST['update_todo']) && (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['csrf_todo_update'])) {
    die('Token CSRF invalide');
  }
  
  $todoId = $_POST['todo_id'];

  try {
    $getTodo = $pdo->prepare(
      'SELECT * FROM todos WHERE id = :todoId AND user_id = :userId'
    );
  
    $getTodo->execute([
      'todoId' => $todoId,
      'userId' => $userId
    ]);
    $todo = $getTodo->fetch();
    
    if (!$todo) {
      die('Todo not found');
    }
  
    $sql = "";
  
    if ($todo['status'] == "completed") {
      $sql = "UPDATE todos SET status = 'pending' WHERE id = :todoId AND user_id = :userId";
    } else {
      $sql = "UPDATE todos SET status = 'completed' WHERE id = :todoId AND user_id = :userId";
    }
  
    $update = $pdo->prepare($sql);
    $update->execute([
      'todoId' => $todoId, 
      'userId' => $userId
    ]);

    header('Location: dashboard.php');

  } catch (Exception $e) {
    die('<br>Erreur : ' . $e->getMessage());
  }

?>