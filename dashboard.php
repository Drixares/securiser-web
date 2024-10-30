<?php
session_start() ;

// Si on a pas de token CSRF, on en génère un
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

?>
<body>
    <div class="px-4">
        <h1 class="text-5xl text-center font-medium mt-20">
            Faire une nouvelle tâche
        </h1>
        <form 
            action="create_todos.php" 
            method="POST" 
            class="mx-auto w-full max-w-screen-sm 
            flex items-center justify-center gap-4 mt-10"
        >
            <input 
                type="text" 
                name="title" 
                id="title"
                placeholder="Titre de la tâche"
                class="shadow appearance-none border rounded py-2 
                px-3 text-gray-700 leading-tight w-full"
            >
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
            <input type="hidden" name="create_todos" value="1">
            <input 
                type="hidden" 
                name="token" 
                value="<?= $_SESSION['csrf_todo_create'] ?>"
            >
        </form>
        <div class="mx-auto w-full max-w-screen-sm mt-10">
            <ul class="flex flex-col gap-2">
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
                                    <?= $todo['due_date'] ?>
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
                                <!-- Your HTML file -->
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
