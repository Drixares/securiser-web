<?php

session_start();

// Vérifie si le token CSRF est présent et valide
if (!isset($_POST['token']) || empty($_POST['token']) || $_POST['token'] !== $_SESSION['csrf_user_create']) {
  die('Token CSRF invalide');
}

// Supprime le token CSRF
unset($_SESSION['csrf_user_create']);

if (isset($_POST['username']) && !empty($_POST['username'])) {
  $username = htmlspecialchars($_POST['username']);
} else {
  echo "<p>Le titre est obligatoire</p>";
}

if (isset($_POST['email']) && !empty($_POST['email'])) {
  $email = htmlspecialchars($_POST['email']);
} else {
  echo "<p>Le contenu est obligatoire</p>";
}

if (isset($_POST['password']) && !empty($_POST['password'])) {
  $password = htmlspecialchars($_POST['password']);
} else {
  echo "<p>Le password est obligatoire</p>";
}


if (isset($username) && isset($email) && isset($password)) {

  // On hash le mot de passe
  $password = password_hash($password, PASSWORD_DEFAULT, []);
  // On génère un ID unique
  $id = bin2hex(random_bytes(16));

  // On se connecte à la base de données
  require_once 'db.php';

  // On prépare la requête
  $sauvegarde = $pdo->prepare(
    'INSERT INTO user_table (id, username, email, password) VALUES (:id, :username, :email, :password)'
  );

  // On exécute la requête
  $sauvegarde->execute([
    'id' => $id,
    'username' => $username,
    'email' => $email,
    'password' => $password
  ]);

  // On vérifie si l'utilisateur a bien été ajouté
  if($sauvegarde->rowCount() > 0) {
    echo "<p>L'utilisateur a bien été ajouté</p>";
    echo "<p>Voici les données de l'utilisateur ajouté : </p>";
    echo "<p>ID : " . $id . "</p>";

    session_start();
    $_SESSION['userId'] = $id;
    $_SESSION['username'] = $username;

    header('Location: me.php');

    error_log("L'utilisateur " . $username . " a été ajouté avec l'ID : " . $id);

  } else {
    echo "<p>Une erreur est survenue</p>";
  }
}

?>