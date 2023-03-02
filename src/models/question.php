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
        $select = "SELECT * FROM question" . ($quantity ? "LIMIT $quantity" : "");

        return $GLOBALS['db']->executeQuery($select);
    }

    public function getQuestionsByQuizId($quizId)
    {
        $select = "SELECT question.id, question.description, question.image  
        FROM question INNER JOIN quiz_question ON question.id = quiz_question.questionId
        WHERE quiz_question.quizId = $quizId";

        return $GLOBALS['db']->executeQuery($select);
    }

    public function createANewQuestion($description, $image = '')
    {
        $query = "INSERT INTO question (description, image) VALUES (?, ?)";

        return $GLOBALS['db']->executeQuery($query, [$description, $image]);
    }

    public function getQuestionsBy($condition)
    {
        $column = key($condition);
        $query = "SELECT * FROM question WHERE $column = '{$condition[$column]}'";

        return $GLOBALS['db']->executeQuery($query);
    }

    public function deleteQuestionsBy($condition)
    {
        $column = key($condition);
        $query = "DELETE FROM question WHERE $column = '{$condition[$column]}'";

        return $GLOBALS['db']->executeQuery($query);
    }
}
