<?php
class Question
{
    private $name;
    private $email;
    private $password;

    public function __construct()
    {
    }

    public function getQuestions($quantity = '')
    {

        if ($quantity) {
            $select = "SELECT * FROM question LIMIT $quantity";
        } else {
            $select = "SELECT * FROM question";
        }

        return $GLOBALS['db']->executeQuery($select);
    }

    public function getQuestionsByQuizId($quizId)
    {
        $select = "SELECT question.id, question.description, question.image  
        FROM question JOIN quiz_question ON question.id = quiz_question.questionId
        WHERE quiz_question.quizId = $quizId";



        return $GLOBALS['db']->executeQuery($select);
    }

    public function createANewQuestion($name, $email, $password)
    {
        $query = "INSERT INTO question VALUES ($name, $email, $password)";

        return $GLOBALS['db']->executeQuery($query);
    }
}
