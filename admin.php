<?php

session_start();
require_once 'db.php';

if (!isset($_SESSION['userId'])) {
    header('Location: login.php');
};

function checkAdmin()
{
    global $pdo;
    $userId = $_SESSION['userId'];
    $checkAdmin = $pdo->prepare('SELECT * FROM user_table WHERE id = :userId AND admin = 1');
    $checkAdmin->execute(['userId' => $userId]);
    return $checkAdmin->rowCount() > 0;
}

if (!checkAdmin()) {
    header('Location: dashboard.php');
    exit();
}

require_once 'get_header.php';
require_once 'get_users.php';

?>

<body class="bg-gray-100">
<h1 class="text-5xl text-center mt-20">Administration</h1>

<div class="flex flex-col gap-4">
    <div class="flex flex-col gap-2">
        <h2 class="text-xl font-semibold">
            Liste des utilisateurs
        </h2>
        <table class="table-auto w-full">
            <thead>
            <tr class="border-b border-gray-200">
                <th class="px-4 py-2">
                    Pseudo
                </th>
                <th class="px-4 py-2">
                    Email
                </th>
                <th class="px-4 py-2">
                    Admin
                </th>
                <th class="px-4 py-2">
                    Actions
                </th>
            </tr>
            </thead>
            <tbody>


            <?php
            foreach ($users as $user) {
                ?>
                <tr class="border-b border-gray-200">
                    <td class="px-4 py-2">
                        <?= htmlspecialchars($user['username']); ?>
                    </td>
                    <td class="px-4 py-2">
                        <?= htmlspecialchars($user['email']); ?>
                    </td>
                    <td class="px-4 py-2">
                        <?= $user['admin'] == 1 ? 'Oui' : 'Non'; ?>
                    </td>
                    <td class="px-4 py-2">
                        <a href="admin.php?action=delete&id=<?= $user['id']; ?>">
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Supprimer
                            </button>
                        </a>
                    </td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </div>
    <?php
    require_once 'get_footer.php';
    ?>

