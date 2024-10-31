<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['userId'])) {
    header('Location: login.php');
};

require_once '../includes/get_header.php';

?>

    <body class="bg-gray-100 min-h-screen flex flex-col">
        <div class="flex flex-col items-center justify-center flex-grow">
            <h1 class="text-5xl font-bold mt-20">Bienvenue <?= htmlspecialchars($_SESSION['username']); ?></h1>
            <p class="text-center mt-4 text-lg">Vous êtes connecté en tant
                que <?= htmlspecialchars($_SESSION['username']); ?></p>
            <p class="text-center mt-2 text-md">
                Votre email est: <span class="font-semibold"><?= htmlspecialchars($_SESSION['email']); ?></span>
            </p>
            <div class="flex gap-4 mt-8">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-6"
                        onclick="location.href='dashboard.php'">
                    Dashboard
                </button>

                <button class="bg-white hover:bg-red-700 border border-red-700 font-bold py-2 px-4 rounded mt-6 text-gray-950 hover:text-white"
                        onclick="location.href='logout.php'">
                    Déconnexion
                </button>
            </div>
        </div>

<?php require_once '../includes/get_footer.php'; ?>