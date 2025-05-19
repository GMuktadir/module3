<?php
//Golam Muktadir
define("TASKFILE","tasks.json");
// Php logic for json 
function save(array $task) {
    file_put_contents(TASKFILE,json_encode($task,JSON_PRETTY_PRINT));
}
function load(){
    if(!file_exists(TASKFILE)){
        //echo "NO task file";
        return [];
    }
    $data=file_get_contents(TASKFILE);
    return $data ? json_decode($data,true) : [];
}
$task=load();
//print_r($task);
// if post form
if($_SERVER['REQUEST_METHOD']==='POST'){
    if(isset($_POST['task'])&& !empty(trim($_POST['task']))){
        $task[]=[
            "task"=>htmlspecialchars(trim($_POST['task'])),
            "done"=> false
        ];
        save($task);
        header('Location:'.$_SERVER['PHP_SELF']);
        exit;
    }
    elseif(isset($_POST['delete'])){
        unset($task[$_POST['delete']]);
        $task=array_values($task);
        save($task);
        header('Location:'.$_SERVER['PHP_SELF']);
        exit;
    }
    elseif(isset($_POST['toggle'])){
        $task[$_POST['toggle']]['done']=!$task[$_POST['toggle']]['done'];
        save($task);
        header('Location:'.$_SERVER['PHP_SELF']);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple To-Do App</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.min.css">
    <style>
        body {
            margin-top: 20px;
        }
        .task-card {
            border: 1px solidrgb(145, 76, 76); 
            padding: 20px;
            border-radius: 5px;
            background: #fff;
            box-shadow: 0 2px 4px rgba(193, 67, 67, 0.1); 
        }
        .task{
            color: #888;
        }
        .task-done {
            text-decoration: line-through;
            color: #888;
        }
        .task-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        ul {
            padding-left: 50px;
        }
        button {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="task-card">
            <h1>Simple To-Do App</h1>

            <!-- Add Task Form -->
            <form method="POST">
                <div class="row">
                    <div class="column column-50">
                        <input type="text" id="input" name="task" placeholder="Text" required>
                    </div>
                    <div class="column column-50">
                        <button type="submit" class="button button-small">Add Task</button>
                    </div>
                </div>
            </form>

            <!-- Task List -->
            <h5>Task List</h5>
            <ul style="list-style: none; padding: 0;">
                <!-- TODO: Loop through tasks array and display each task with a toggle and delete option -->
                <!-- If there are no tasks, display a message saying "No tasks yet. Add one above!" -->
                <?php if(empty($task)): ?>
                  
                     <li><h5>No tasks yet !. Please Add one<h5></li>
                    <!-- if there are tasks, display each task with a toggle and delete option -->
                    <?php else: ?>
                    <?php foreach($task as $i => $tsk ): ?>
                        <table>
                       <tr> 
                        <td>
                    <h2>
                        <li class="task-item">
                            <form method="POST" style="flex-grow: 1;">
                                <input type="hidden" name="toggle" value="<?php echo $i ?>">
                           
                                <button type="submit" style="border: none; background: none; cursor: pointer; text-align: left; width: 100%;">
                                    <span class="task <?php echo $tsk['done']? 'task-done': '' ?>">
                                        <?php echo $tsk['task'] ?>
                                    </span>
                                </button>
                            </form>

                            <form method="POST">
                                <input type="hidden" name="delete" value="<?php echo $i ?>">
                                <button type="submit" class="button  button-small" style="margin-left: 10px;">Delete</button>
                            </form>
                        </li>
                    </h2>
                        </td>
                        </tr>
                         </table>
                    <?php endforeach ;?>
                    <?php endif ; ?>
            </ul>

        </div>
    </div>
</body>
</html>