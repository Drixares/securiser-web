<?php

session_start();

// Vérifie si le token CSRF est présent et valide
if (!isset($_POST['token']) || empty($_POST['token']) || $_POST['token'] !== $_SESSION['csrf_user_create']) {
  die('Token CSRF invalide');
}

// Supprime le token CSRF
unset($_SESSION['csrf_user_create']);




// Traitement de l'inscription
if (isset($_POST['register'])) {
  if (isset($_POST['username']) && !empty($_POST['username'])) {
    $username = htmlspecialchars($_POST['username']);
  } else {
    echo "<p>Le pseudo est obligatoire</p>";
    exit();
  }

  if (isset($_POST['email']) && !empty($_POST['email'])) {
    $email = htmlspecialchars($_POST['email']);
  } else {
    echo "<p>L'email est obligatoire</p>";
    exit();
  }

  if (isset($_POST['password']) && !empty($_POST['password'])) {
    $password = htmlspecialchars($_POST['password']);
  } else {
    echo "<p>Le mot de passe est obligatoire</p>";
    exit();
  }

  // Hash le mot de passe
  $password_hashed = password_hash($password, PASSWORD_DEFAULT);
  // Génère un ID unique
  $id = bin2hex(random_bytes(16));

  // Se connecter à la base de données
  require_once 'db.php';

  // Prépare la requête
  $sauvegarde = $pdo->prepare(
    'INSERT INTO user_table (id, username, email, password) VALUES (:id, :username, :email, :password)'
  );

  // Exécute la requête
  $sauvegarde->execute([
    'id' => $id,
    'username' => $username,
    'email' => $email,
    'password' => $password_hashed
  ]);

  // Vérifie si l'utilisateur a bien été ajouté
  if ($sauvegarde->rowCount() > 0) {
    echo "<p>L'utilisateur a bien été ajouté</p>";

    // Démarre la session pour cet utilisateur
    $_SESSION['userId'] = $id;
    $_SESSION['username'] = $username;
    $_SESSION['email'] = $email;

    header('Location: me.php');
    exit();
  } else {
    echo "<p>Une erreur est survenue lors de l'ajout de l'utilisateur.</p>";
  }
}

?>