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
        $select = "SELECT * FROM participant" . ($quantity ? "LIMIT $quantity" : "");

        return $GLOBALS['db']->executeQuery($select);
    }

    public function getParticipantsWithPagination($page, $limit)
    {

        $offset = ($page - 1) * $limit;
        $count = count((new Participant())->getParticipants());
        $query = "SELECT * FROM participant LIMIT $limit OFFSET $offset";

        $totalPages = ceil($count / $limit);

        return [
            'participants' => $GLOBALS['db']->executeQuery($query),
            'totalPages' => $totalPages
        ];
    }

    public function getUniqueParticipantBy($column, $value)
    {
        $select = "SELECT * FROM participant WHERE $column = '$value'";

        if (count($GLOBALS['db']->executeQuery($select)) === 0) return null;
        return $GLOBALS['db']->executeQuery($select)[0];
    }

    public function upsertAParticipant($name, $email, $password, $group, $id = '')
    {
        if ($id) {
            $query = "UPDATE participant SET name = ?, email = ?, password = ?, groupId=? WHERE id = ?";

            return $GLOBALS['db']->executeQuery($query, [$name, $email, $password, $group, $id]);
        }

        $query = "INSERT INTO participant (name, email, password, groupId) VALUES (?, ?, ?, ?)";

        return $GLOBALS['db']->executeQuery($query, [$name, $email, $password, $group]);
    }

    public function deleteAParticipant($id)
    {
        $query = "DELETE FROM participant WHERE id = ?";

        return $GLOBALS['db']->executeQuery($query, [$id]);
    }
}
