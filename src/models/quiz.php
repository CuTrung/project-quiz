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

        if ($quantity) {
            $select = "SELECT * FROM quiz LIMIT $quantity";
        } else {
            $select = "SELECT * FROM quiz";
        }

        return $GLOBALS['db']->get($select);
    }

    public function createANewQuiz($name, $email, $password)
    {
        $query = "INSERT INTO quiz VALUES ($name, $email, $password)";

        return $GLOBALS['db']->insert($query);
    }
}
