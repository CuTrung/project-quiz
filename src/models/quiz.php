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

    public function createANewQuiz($name, $email, $password)
    {
        $query = "INSERT INTO quiz VALUES ($name, $email, $password)";

        return $GLOBALS['db']->executeQuery($query);
    }

    public function getListQuizzesWhenSearch($strSearch)
    {
        $select = "SELECT * FROM quiz WHERE name LIKE '%$strSearch%'";

        return $GLOBALS['db']->executeQuery($select);
    }
}
