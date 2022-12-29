<?php
class Participant
{
    private $name;
    private $email;
    private $password;

    public function __construct()
    {
    }

    public function getParticipants($quantity = '')
    {

        if ($quantity) {
            $select = "SELECT * FROM participant LIMIT $quantity";
        } else {
            $select = "SELECT * FROM participant";
        }

        return $GLOBALS['db']->get($select);
    }

    public function createANewParticipant($name, $email, $password)
    {
        $query = "INSERT INTO participant VALUES ($name, $email, $password)";

        return $GLOBALS['db']->insert($query);
    }
}
