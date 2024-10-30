<?php
session_start();

// Si on a pas de token CSRF, on en génère un
if (!isset($_SESSION['csrf_user_create']) || empty($_SESSION['csrf_user_create'])) {
    $_SESSION['csrf_user_create'] = bin2hex(random_bytes(32));
}

require_once 'get_header.php';
?>

<body class="bg-gray-100">
<h1 class="text-5xl text-center mt-20">Création de compte</h1>
<form action="traitement.php" method="POST" class="max-w-lg mx-auto mt-10 bg-white p-8 rounded-lg shadow-lg">
    <div class="mb-4">
        <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Pseudo :</label>
        <input type="text" name="username" id="username" placeholder="John Doe" required
               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
    </div>
    <div class="mb-4">
        <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email :</label>
        <input type="email" name="email" id="email" placeholder="john.doe@example.com" required
               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
    </div>
    <div class="mb-4">
        <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Mot de passe :</label>
        <input type="password" name="password" id="password" placeholder="A strong password" required
               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
    </div>
    <div class="flex items-center justify-between">
        <input type="submit" value="Ajouter" name="ajouter"
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
    </div>

    <!-- Champ caché pour indiquer une requête d'inscription -->
    <input type="hidden" name="register" value="1">

    <input type="hidden" name="token" value="<?= $_SESSION["csrf_user_create"]; ?>">
    <p class="mt-5">Vous avez déjà un compte ? <a href="login.php">Connexion</a></p>
</form>
</body>
</html>