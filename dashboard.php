<?php
session_start() ;

if (!isset($_SESSION['csrf_todo_create']) || empty($_SESSION['csrf_todo_create'])) {
    $_SESSION['csrf_todo_create'] = bin2hex(random_bytes(32));
}

if (!isset($_SESSION['csrf_todo_update']) || empty($_SESSION['csrf_todo_update'])) {
    $_SESSION['csrf_todo_update'] = bin2hex(random_bytes(32));
}

if (!isset($_SESSION['csrf_todo_delete']) || empty($_SESSION['csrf_todo_delete'])) {
    $_SESSION['csrf_todo_delete'] = bin2hex(random_bytes(32));
}


if (!isset($_SESSION['userId'])) {
    header('Location: login.php');
};

require_once 'get_todos.php';
require_once 'get_header.php';

// On récupère les tâches déjà faites et on les met dans un autre tableau tout en enlevant les enelevant du tableau de base
$completed_todos = array_filter($todos, function($todo) {
    return $todo['status'] == 'completed';
});

$todos = array_filter($todos, function($todo) {
    return $todo['status'] != 'completed';
});
?>
<body>
    <div class="px-4">
        <h1 class="text-5xl text-center font-semibold mt-20">
            Vos tâches
        </h1>
        <form 
            action="create_todos.php" 
            method="POST" 
            class="mx-auto w-full max-w-screen-sm 
            flex flex-col sm:flex-row sm:items-center justify-center gap-x-4 gap-y-2 mt-10"
        >
            <input 
                type="text" 
                name="title" 
                id="title"
                placeholder="Titre de la tâche"
                class="shadow appearance-none border rounded py-2 
                px-3 text-gray-700 leading-tight w-full"
            >
            <div class="flex items-center gap-4">
                <input 
                    type="date" 
                    name="due_date" 
                    id="due_date"
                    class="shadow appearance-none border rounded l py-2 
                    px-3 text-gray-700 leading-tight"
                >
                <input 
                    type="submit" 
                    value="Ajouter"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold 
                    py-2 px-4 rounded cursor-pointer"
                >
            </div>
            <input type="hidden" name="create_todos" value="1">
            <input 
                type="hidden" 
                name="token" 
                value="<?= $_SESSION['csrf_todo_create'] ?>"
            >
        </form>
        <div class="mx-auto w-full max-w-screen-sm mt-10">
            <h2 class="text-3xl font-semibold mb-5">
                Tâches à faire
            </h2>
            <ul class="flex flex-col gap-2">
                <?php if (empty($todos)): ?>
                    <p class="text-gray-500">
                        Vous n'avez pas de tâches à faire
                    </p>
                <?php endif; ?>
                <?php foreach ($todos as $todo): ?>
                    <div class="flex items-center gap-2 w-full">
                        <form
                            method="POST"
                            action="update_todos.php" 
                            class="flex items-center gap-3 border border-gray-200 rounded-lg
                            hover:bg-gray-50 transition-colors w-full"
                        >
                            <input 
                                type="checkbox" 
                                name="done" 
                                id="todo:<?= $todo['id'] ?>"
                                class="rounded cursor-pointer size-5 border-gray-300 
                                focus:ring-blue-500 ml-4" 
                                onchange="this.form.submit()"
                                <?= $todo['status'] == 'completed' ? 'checked' : '' ?>  
                            >
                            <label 
                                for="todo:<?= $todo['id'] ?>"
                                class="flex items-center justify-between w-full cursor-pointer
                                py-4 pr-4" 
                            >
                                <p class="text-lg font-medium">
                                    <?= $todo['title'] ?>
                                </p>
                                <span class="text-sm text-gray-700">
                                    <?= date('d M', strtotime($todo['due_date'])) ?>
                                </span>
                            </label>
                            <input 
                                type="hidden" 
                                name="token" 
                                value="<?= $_SESSION['csrf_todo_update'] ?>"
                            >
                            <input 
                                type="hidden" 
                                name="update_todo" 
                                value="1"
                            >
                            <input 
                                type="hidden" 
                                name="todo_id" 
                                value="<?= $todo['id'] ?>"
                            >
                        </form>
                        <form action="delete_todos.php" method="POST">
                            <button 
                                class="size-10 rounded-lg flex items-center justify-center
                                hover:bg-red-100 hover:border hover:border-red-200 transition-colors"

                            >
                                <i data-lucide="trash-2" class="text-gray-700"></i>
                            </button>
                            <input 
                                type="hidden" 
                                name="token" 
                                value="<?= $_SESSION['csrf_todo_delete'] ?>"
                            >
                            <input 
                                type="hidden" 
                                name="delete_todo" 
                                value="1"
                            >
                            <input 
                                type="hidden" 
                                name="todo_id" 
                                value="<?= $todo['id'] ?>"
                            >
                        </form>

                    </div>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="mx-auto w-full max-w-screen-sm mt-10">
            <h2 class="text-3xl font-semibold mb-5">
                Tâches terminées
            </h2>
            <ul class="flex flex-col gap-2">
                <?php if (empty($completed_todos)): ?>
                    <p class="text-gray-500">
                        Vous n'avez pas encore de tâches terminées
                    </p>
                <?php endif; ?>
                <?php foreach ($completed_todos as $todo): ?>
                    <div class="flex items-center gap-2 w-full">
                        <form
                            method="POST"
                            action="update_todos.php" 
                            class="flex items-center gap-3 border border-gray-200 rounded-lg
                            hover:bg-gray-50 transition-colors w-full"
                        >
                            <input 
                                type="checkbox" 
                                name="done" 
                                id="todo:<?= $todo['id'] ?>"
                                class="rounded cursor-pointer size-5 border-gray-300 
                                focus:ring-blue-500 ml-4" 
                                onchange="this.form.submit()"
                                <?= $todo['status'] == 'completed' ? 'checked' : '' ?>  
                            >
                            <label 
                                for="todo:<?= $todo['id'] ?>"
                                class="flex items-center justify-between w-full cursor-pointer
                                py-4 pr-4" 
                            >
                                <p class="text-lg font-medium">
                                    <?= $todo['title'] ?>
                                </p>
                                <span class="text-sm text-gray-700">
                                    <?= date('d M', strtotime($todo['due_date'])) ?>
                                </span>
                            </label>
                            <input 
                                type="hidden" 
                                name="token" 
                                value="<?= $_SESSION['csrf_todo_update'] ?>"
                            >
                            <input 
                                type="hidden" 
                                name="update_todo" 
                                value="1"
                            >
                            <input 
                                type="hidden" 
                                name="todo_id" 
                                value="<?= $todo['id'] ?>"
                            >
                        </form>
                        <form action="delete_todos.php" method="POST">
                            <button 
                                class="size-10 rounded-lg flex items-center justify-center
                                hover:bg-red-100 hover:border hover:border-red-200 transition-colors"

                            >
                                <i data-lucide="trash-2" class="text-gray-700"></i>
                            </button>
                            <input 
                                type="hidden" 
                                name="token" 
                                value="<?= $_SESSION['csrf_todo_delete'] ?>"
                            >
                            <input 
                                type="hidden" 
                                name="delete_todo" 
                                value="1"
                            >
                            <input 
                                type="hidden" 
                                name="todo_id" 
                                value="<?= $todo['id'] ?>"
                            >
                        </form>

                    </div>
                <?php endforeach; ?>
            </ul>
        </div>

    </div>
    
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
    lucide.createIcons();
    </script>
</body>
</html>
