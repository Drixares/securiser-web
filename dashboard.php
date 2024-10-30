<?php
session_start() ;


if (!isset($_SESSION['userId'])) {
    header('Location: login.php');
};
require_once 'db.php';

$todos = $pdo->prepare('SELECT * FROM todo_table WHERE user_id = :user_id');
$todos->execute(['user_id' => $_SESSION['userId']]);

require_once 'get_header.php'
?>

<body class="bg-gray-100">
<h1 class="text-5xl text-center mt-20">Dashboard</h1>
<button>
    Ajouter une todo
</button>

<?php if ($todos->rowCount() > 0) {
    echo "<p>Vos todos :</p>";
    echo "<ul>";
    while ($todo = $todos->fetch()) {
        echo "<li>" . $todo['todo'] . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>Vous n'avez pas encore ajouté de todo.</p>";
}
?>

<a href="me.php" class="text-blue-500">
    Mon profil
</a>
</body>s