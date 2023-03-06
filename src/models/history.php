<?php
class History
{
    private $name;
    private $difficulty;
    private $image;

    public function __construct()
    {
    }


    public function getHistories($quantity = '')
    {

        if ($quantity) {
            $select = "SELECT h.id, q.name, p.name, h.totalQuestion, h.totalQuestionCorrect, h.timeStart, h.timeEnd FROM history h INNER JOIN participant p ON h.participantId = p.id INNER JOIN quiz q ON h.quizId = q.id LIMIT $quantity";
        } else {
            $select = "SELECT h.id, q.name, p.name, h.totalQuestion, h.totalQuestionCorrect, h.timeStart, h.timeEnd FROM history h INNER JOIN participant p ON h.participantId = p.id INNER JOIN quiz q ON h.quizId = q.id";
        }

        return $GLOBALS['db']->executeQuery($select);
    }

    public function getHistoriesBy($condition)
    {
        $column = key($condition);
        $select = "SELECT h.id, q.name as quizName, q.difficulty, p.name, h.quizId, h.totalQuestionCorrect, h.timeStart, h.timeEnd FROM history h INNER JOIN participant p ON h.participantId = p.id INNER JOIN quiz q ON h.quizId = q.id WHERE $column LIKE {$condition[$column]}";

        return $GLOBALS['db']->executeQuery($select);
    }


    public function createANewHistory($name, $email, $password)
    {
        $query = "INSERT INTO history VALUES ($name, $email, $password)";

        return $GLOBALS['db']->executeQuery($query);
    }

    public function getListHistoriesWhenSearch($strSearch)
    {
        $select = "SELECT * FROM history WHERE name LIKE '%$strSearch%'";

        return $GLOBALS['db']->executeQuery($select);
    }

    public function updateHistoriesBy($data)
    {
        $quizId = $data['quizId'];
        $participantId = $data['participantId'];
        $totalQuestionCorrect = $data['totalQuestionCorrect'];
        $timeStart = $data['timeStart'];
        $timeEnd = $data['timeEnd'];

        $query = "INSERT INTO history (quizId, participantId, totalQuestionCorrect, timeStart, timeEnd) VALUES (?, ?, ?, ?, ?)";

        return $GLOBALS['db']->executeQuery($query, [$quizId, $participantId, $totalQuestionCorrect, $timeStart, $timeEnd]);
    }
}
