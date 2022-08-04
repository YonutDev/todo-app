<?php 
if (!isset($_COOKIE['TODOS'])) { // All todos data are saved in cookies, if user don't visited the app before, let's create a cookie for he :)
    setcookie('TODOS', '{}', time()+31536000); 
    $_COOKIE['TODOS'] = '{}'; 
}

// Check for color mode
if (isset($_COOKIE['COLOR_MODE'])) $color_mode = $_COOKIE['COLOR_MODE'];
else $color_mode = "LIGHT";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Add new todo
    if (isset($_POST['create-todo']) && !empty($_POST['create-todo'])) {
        $todos = json_decode($_COOKIE['TODOS'], TRUE); // Get data from TODOS cookie, that is in JSON format
        $todos[] = ['name' => str_replace(array("'", '"', '\\'), "", $_POST['create-todo']), 'completed' => false, 'disabled' => false]; // Create the new todo in data getted before

        // Save new todo in cookies
        setcookie('TODOS', json_encode($todos), time()+31536000);
        $_COOKIE['TODOS'] = json_encode($todos);
    }
} 
// Filters
if (isset($_GET['filter'])) {
    if ($_GET['filter'] === 'all') $filter = 'all';
    else if ($_GET['filter'] === 'active') $filter = 'active';
    else if ($_GET['filter'] === 'completed') $filter = 'completed';
} ?>
<!-- Challenge by frontendmentor.io & Coded by yonutdev.xyz -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo App</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">
    <?php if ($color_mode === 'DARK') { ?> <!-- Dark Mode --> <link rel="stylesheet" href="css/dark.css"> <!-- --> <?php } ?>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>TODO</h1>
        <?php if ($color_mode === 'DARK') { ?><img src="img/icon-sun.svg" alt="Change color mode" id="color-mode-changer"> <?php }
        else if ($color_mode === 'LIGHT') { ?><img src="img/icon-moon.svg" alt="Change color mode" id="color-mode-changer"> <?php } ?>
    </div>

    <form method="POST">
        <!-- Create a new todo -->
        <div class="add-todo">
            <input type="text" placeholder="Create a new todo..." name="create-todo" id="create-todo">
        </div>
    </form>
    <br><br>
    <?php if (isset($_COOKIE['TODOS']) && $_COOKIE['TODOS'] !== '{}') { /* Check if user have todos */ ?>
        <!-- Todo list -->
        <div class="todos">
            <div class="todos-list">
                <?php 
                $todos = json_decode($_COOKIE['TODOS'], TRUE); // Get data from cookie that is in json format
                $todos_count = 0; // This variable is used to count todos, that will help us at elements ids, elements ids will help us in JavaScript to know what's the todo for the user action :)
                $todos_shown = 0; // Some todos are not shown because they are disabled (deleted by user). $todos_count is including and disabled todos
                foreach($todos as $todo) { 
                    $todos_count++;
                    if ($todo['disabled'] === true) continue; // If todo is disabled, skip it
                    else if (isset($filter)) {
                        if ($filter === 'active') {
                            if($todo['completed'] === true) continue; // If filter is set to get only active todos and the todo is completed, skip it
                        }
                        else if ($filter === 'completed') {
                            if($todo['completed'] === false) continue;  // If filter is set to get only complete todos and the todo is not completed, skip it
                        }
                    } 
                    $todos_shown++;
                    if ($todos_count > 0 && $todos_shown > 0) { ?>
                        <div class="todo" id="todo-<?php echo $todos_count; ?>">
                            <input type="radio" <?php if ($todo['completed'] === true) echo 'checked'; ?> id="todo-complete-<?php echo $todos_count; ?>">
                            <label <?php if ($todo['completed'] === true) { if ($color_mode === 'DARK') echo 'style="color: hsl(233, 14%, 35%); text-decoration: line-through;"'; else echo 'style="color: hsl(236, 9%, 61%); text-decoration: line-through;"'; } ?>><?php echo htmlentities($todo['name']); ?></label>
                            <img src="img/icon-cross.svg" class="todo-delete" id="todo-delete-<?php echo $todos_count; ?>" alt="X">
                        </div> 
                    <?php }
                }
                if ($todos_count > 0 && $todos_shown > 0) { ?>
                    <!-- Todo footer -->
                    <div class="todo-footer">
                        <p class="todo-items-left">... items left</p>
                        <p class="todo-clear-completed" id="todo-clear-completed">Clear Completed</p>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php } ?>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <?php if (isset($_COOKIE['TODOS']) && $_COOKIE['TODOS'] !== '{}') { // Check if user have todos
        if ($todos_count > 0 && $todos_shown > 0 || isset($filter) && $filter !== 'all') { ?>
            <!-- Todo Filters -->
            <div class="todo-filters">
                <a class="todo-filter-all" <?php if (isset($filter) && $filter === 'all') echo 'style="color: hsl(220, 98%, 61%)"'; else if (!isset($filter)) echo 'style="color: hsl(220, 98%, 61%)"'; ?> href="?filter=all">All</a>
                <a class="todo-filter-active" <?php if (isset($filter) && $filter === 'active') echo 'style="color: hsl(220, 98%, 61%);"'; ?> href="?filter=active">Active</a>
                <a class="todo-filter-completed" <?php if (isset($filter) && $filter === 'completed') echo 'style="color: hsl(220, 98%, 61%);"'; ?> href="?filter=completed">Completed</a>
            </div>
            <?php if($todos_shown == 0) echo '<script>$(".todos").css("display", "none");</script>';
    } else { echo '<script>$(".todos").css("display", "none");</script>'; } } ?>
    <br><br>

    <!-- Script -->
    <script>/* Get data from TODO cookie */ const todos = JSON.parse('<?php echo $_COOKIE['TODOS']; ?>');</script>
    <script src="js/script.js"></script>
</body>
</html>