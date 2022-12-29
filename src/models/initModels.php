
<?php
include './src/configs/connectDB.php';
$db = new ConnectDB();
$GLOBALS['db'] = $db;


include './src/models/participant.php';
$participantModel = new Participant();

include './src/models/quiz.php';
$quizModel = new Quiz();

include './src/models/question.php';
$questionModel = new Question();

include './src/models/answer.php';
$answerModel = new Answer();
