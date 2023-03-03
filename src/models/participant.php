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

    public function getUniqueParticipantBy($condition)
    {
        $column = key($condition);
        $select = "SELECT p.id, p.name, p.email, p.password, p.groupId, g.name as groupName, r.url FROM participant p INNER JOIN `group` g ON g.id = p.groupId INNER JOIN group_role g_r ON g_r.groupId = g.id INNER JOIN `role` r ON r.id = g_r.roleId WHERE p.$column = '{$condition[$column]}'";

        $listParticipants = $GLOBALS['db']->executeQuery($select);

        if (count($listParticipants) === 0) return null;

        $listParticipants[0]['url'] = [$listParticipants[0]['url']];
        for ($i = 1; $i < count($listParticipants); $i++) {
            $listParticipants[0]['url'] = [...$listParticipants[0]['url'], $listParticipants[$i]['url']];
        }

        return $listParticipants[0];
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

    public function updateParticipantsGroupId($oldGroupId, $newGroupId)
    {
        $query = "UPDATE participant SET groupId = ? WHERE groupId = ?";
        return $GLOBALS['db']->executeQuery($query, [$newGroupId, $oldGroupId]);
    }
}
