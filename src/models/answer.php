<?php
class Answer
{
    public function __construct()
    {
    }

    public function getAnswers($quantity = '')
    {
        $select = "SELECT * FROM answer" . ($quantity ? "LIMIT $quantity" : "");

        return $GLOBALS['db']->executeQuery($select);
    }

    public function getAnswersById($id)
    {

        $select = "SELECT * FROM answer WHERE id = $id";
        return $GLOBALS['db']->executeQuery($select)[0];
    }

    public function getAnswersByQuestionId($questionId)
    {
        $select = "SELECT * FROM answer WHERE questionId = $questionId";

        return $GLOBALS['db']->executeQuery($select);
    }

    public function createANewAnswer($description, $isCorrect, $questionId)
    {
        $query = "INSERT INTO answer (description, isCorrect, questionId) VALUES (?, ?, ?)";

        return $GLOBALS['db']->executeQuery($query, [$description, $isCorrect, $questionId]);
    }

    public function deleteAnswersBy($condition)
    {
        $column = key($condition);
        $query = "DELETE FROM answer WHERE $column = '{$condition[$column]}'";

        return $GLOBALS['db']->executeQuery($query);
    }
}
