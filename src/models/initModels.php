
<?php
include './src/configs/connectDB.php';
$db = new ConnectDB();
$GLOBALS['db'] = $db;


set_include_path(get_include_path() . PATH_SEPARATOR . 'models/');
spl_autoload_extensions('.php');
spl_autoload_register();

$participantModel = new Participant();

$quizModel = new Quiz();

$questionModel = new Question();

$answerModel = new Answer();

$historyModel = new History();

$roleModel = new Role();

$groupModel = new Group();
