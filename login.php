<?php
session_start();

if (!isset($_SESSION['csrf_user_login']) || empty($_SESSION['csrf_user_login'])) {
    $_SESSION['csrf_user_login'] = bin2hex(random_bytes(32));
}

if (!isset($_SESSION['userId'])) {
    header('Location: dashboard.php');
};

require_once 'get_header.php';
?>

<body class="bg-gray-100">
<h1 class="text-5xl text-center mt-20">Connexion</h1>
<form action="traitement.php" method="POST" class="max-w-lg mx-auto mt-10 bg-white p-8 rounded-lg shadow-lg">
    <div class="mb-4">
        <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email :</label>
        <input type="email" name="email" id="email" placeholder="john.doe@example.com" required
               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
    </div>
    <div class="mb-4">
        <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Mot de passe :</label>
        <input type="password" name="password" id="password" placeholder="Ton mot de passe" required
               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
    </div>
    <div class="flex items-center justify-between">
        <input type="submit" value="Connexion" name="connexion"
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
    </div>

    <input type="hidden" name="login" value="2">
    <input type="hidden" name="token" value="<?= $_SESSION["csrf_user_login"]; ?>">
    <p class="mt-5">Vous n'avez pas de compte ? <a href="index.php">Créer un compte</a></p>
</form>

<?php require_once 'get_footer.php'; ?>