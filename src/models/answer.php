<?php
class Answer
{
    private $name;
    private $email;
    private $password;

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

        return $GLOBALS['db']->get($select);
    }

    public function getAnswersById($id)
    {

        $select = "SELECT * FROM answer WHERE id = $id";
        return $GLOBALS['db']->get($select);
    }

    public function getAnswersByQuestionId($questionId)
    {
        $select = "SELECT * FROM answer WHERE questionId = $questionId";

        return $GLOBALS['db']->get($select);
    }

    public function createANewAnswer($name, $email, $password)
    {
        $query = "INSERT INTO answer VALUES ($name, $email, $password)";

        return $GLOBALS['db']->insert($query);
    }
}
