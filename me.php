<?php
session_start() ;
require_once 'db.php';

if (!isset($_SESSION['userId'])) {
    header('Location: login.php');
};
?>


<body class="bg-gray-100">
<h1 class="text-5xl text-center mt-20">Bienvenue <?= $_SESSION['username']; ?></h1>
<p class="text-center">Vous êtes connecté en tant que <?= $_SESSION['username']; ?></p>
<p class="text-center">
 Votre email est <?= $_SESSION['email']; ?>
</p>

<button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" >
    Deconnexion
</button>
</body>
</html>