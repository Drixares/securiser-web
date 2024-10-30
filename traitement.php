<?php
session_start();
require_once 'db.php';


if (isset($_POST['login']) && (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['csrf_user_login'])) {
    die('Token CSRF invalide');
}
if (isset($_POST['register']) && (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['csrf_user_create'])) {
    die('Token CSRF invalide');
}

unset($_SESSION['csrf_user_login']);
unset($_SESSION['csrf_user_create']);


if (isset($_POST['register'])) {

    echo "Traitement en cours...";
    
    $username = isset($_POST['username']) ? htmlspecialchars($_POST['username']) : die("<p>Le pseudo est obligatoire</p>");
    $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : die("<p>L'email est obligatoire</p>");
    $password = isset($_POST['password']) ? htmlspecialchars($_POST['password']) : die("<p>Le mot de passe est obligatoire</p>");

    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    $id = bin2hex(random_bytes(16));
    
    
    $sauvegarde = $pdo->prepare(
        'INSERT INTO user_table (id, username, email, password) VALUES (:id, :username, :email, :password)'
    );
    
    $sauvegarde->execute([
        'id' => $id,
        'username' => $username,
        'email' => $email,
        'password' => $password_hashed
    ]);
    
    echo "Traitement en cours...";
    if ($sauvegarde->rowCount() > 0) {
        echo "<p>L'utilisateur a bien été ajouté</p>";


        $_SESSION['userId'] = $id;
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;

        header('Location: dashboard.php');
        exit();
    } else {
        echo "<p>Une erreur est survenue lors de l'ajout de l'utilisateur.</p>";
    }
}

if (isset($_POST['login'])) {
    $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : die("<p>L'email est obligatoire</p>");
    $password = isset($_POST['password']) ? htmlspecialchars($_POST['password']) : die("<p>Le mot de passe est obligatoire</p>");

    $login = $pdo->prepare('SELECT id, username, email, password FROM user_table WHERE email = :email');
    $login->execute(['email' => $email]);

    if ($login->rowCount() > 0) {
        $user = $login->fetch();

        if (password_verify($password, $user['password'])) {

            $_SESSION['username'] = $user['username'];
            $_SESSION['userId'] = $user['id'];
            $_SESSION['email'] = $user['email'];

            header('Location: me.php');
            exit();
        } else {
            echo "<p>Le mot de passe est incorrect.</p>";
        }
    } else {
        echo "<p>L'utilisateur n'existe pas.</p>";
    }
}
?>