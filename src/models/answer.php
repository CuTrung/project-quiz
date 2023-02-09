<?php
class Answer
{
    public function __construct()
    {
    }

    public function getAnswers($quantity = '')
    {

        if ($quantity) {
            $select = "SELECT * FROM answer LIMIT $quantity";
        } else {
            $select = "SELECT * FROM answer";
        }

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

    public function createANewAnswer($name, $email, $password)
    {
        $query = "INSERT INTO answer VALUES ($name, $email, $password)";

        return $GLOBALS['db']->executeQuery($query);
    }
}
