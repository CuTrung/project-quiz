<?php

use function PHPSTORM_META\map;

class Group
{
    private $name;
    private $email;
    private $password;

    public function __construct()
    {
    }

    public function getGroups($quantity = '')
    {
        $select = "SELECT g.id as groupId, g.name, r.id as roleId, r.url FROM `group` g INNER JOIN group_role g_r ON g.id = g_r.groupId INNER JOIN `role` r ON r.id = g_r.roleId" . ($quantity ? "LIMIT $quantity" : "");
        $listGroups = $GLOBALS['db']->executeQuery($select);

        $selectAll = "SELECT g.id as groupId, g.name FROM `group` g" . ($quantity ? "LIMIT $quantity" : "");
        $listGroupsAll = $GLOBALS['db']->executeQuery($selectAll);

        foreach ($listGroupsAll as $key => $item) {
            $listGroupsAll[$key] = [...$item, "roleId" => '', 2 => '', "url" => '', 3 => ''];
        }

        $arrayCombine = array_merge($listGroupsAll, $listGroups);
        $arrayMerge = mergeArraySameKeys($arrayCombine, 1);
        $groupNotExist = [];
        foreach ($arrayMerge as $item) {
            if (count($item) === 1) {
                array_push($groupNotExist, $item);
            }
        }

        return array_merge($listGroups, ...$groupNotExist);
    }

    public function getGroupsByQuizId($quizId)
    {
        $select = "SELECT group.id, group.description, group.image  
        FROM group JOIN quiz_group ON group.id = quiz_group.groupId
        WHERE quiz_group.quizId = $quizId";

        return $GLOBALS['db']->executeQuery($select);
    }

    public function createANewGroup($name)
    {
        $query = "INSERT INTO `group` (name) VALUES (?)";

        return $GLOBALS['db']->executeQuery($query, [$name]);
    }

    public function getGroupsBy($condition)
    {
        $column = key($condition);
        $query = "SELECT * FROM group WHERE $column = '{$condition[$column]}'";

        return $GLOBALS['db']->executeQuery($query);
    }

    public function deleteGroupsBy($condition)
    {
        $column = key($condition);
        $query = "DELETE FROM `group` WHERE $column = '{$condition[$column]}'";

        return $GLOBALS['db']->executeQuery($query);
    }

    public function createANewGroup_Role($data)
    {
        $query = "INSERT INTO group_role (groupId, roleId) VALUES (?, ?)";

        return $GLOBALS['db']->executeQuery($query, [$data['groupId'], $data['roleId']]);
    }

    public function deleteGroup_RoleBy($condition)
    {

        $column = key($condition);
        $query = "DELETE FROM group_role WHERE $column = '{$condition[$column]}'";

        return $GLOBALS['db']->executeQuery($query);
    }
}
