<?php
class Quiz
{
    private $name;
    private $difficulty;
    private $image;

    public function __construct()
    {
    }


    public function getQuizzes($quantity = '')
    {
        $select = "SELECT * FROM quiz" . ($quantity ? "LIMIT $quantity" : "");

        return $GLOBALS['db']->executeQuery($select);
    }

    public function createANewQuiz($name, $difficulty, $image = '')
    {
        $query = "INSERT INTO quiz (name, difficulty, image) VALUES (?, ?, ?)";

        return $GLOBALS['db']->executeQuery($query, [$name, $difficulty, $image]);
    }

    public function getListQuizzesWhenSearch($strSearch)
    {
        $select = "SELECT * FROM quiz WHERE name LIKE '%$strSearch%'";

        return $GLOBALS['db']->executeQuery($select);
    }

    public function getQuizzesBy($condition)
    {
        $column = key($condition);
        $query = "SELECT * FROM quiz WHERE $column = '{$condition[$column]}'";

        return $GLOBALS['db']->executeQuery($query);
    }

    public function createANewQuiz_Question($quizId, $questionId)
    {
        $query = "INSERT INTO quiz_question (quizId, questionId) VALUES (?, ?)";

        return $GLOBALS['db']->executeQuery($query, [$quizId, $questionId]);
    }

    public function deleteQuizzesBy($condition)
    {
        $column = key($condition);
        $query = "DELETE FROM quiz WHERE $column = '{$condition[$column]}'";

        return $GLOBALS['db']->executeQuery($query);
    }

    public function deleteQuiz_QuestionBy($condition)
    {
        $column = key($condition);
        $query = "DELETE FROM quiz_question WHERE $column = '{$condition[$column]}'";

        return $GLOBALS['db']->executeQuery($query);
    }

    public function getQuiz_QuestionBy($condition)
    {
        $column = key($condition);
        $select = "SELECT * FROM quiz_question WHERE $column = '{$condition[$column]}'";

        return $GLOBALS['db']->executeQuery($select);
    }
}
