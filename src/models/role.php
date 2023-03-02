<?php
class Role
{
    private $name;
    private $email;
    private $password;

    public function __construct()
    {
    }

    public function getRoles($quantity = '')
    {
        $select = "SELECT r.id, r.url, g_r.groupId as groupId FROM `role` r JOIN group_role g_r ON r.id = g_r.roleId " . ($quantity ? "LIMIT $quantity" : "");
        $listRoles = $GLOBALS['db']->executeQuery($select);

        $selectAll = "SELECT * FROM `role`" . ($quantity ? "LIMIT $quantity" : "");
        $listRolesAll = $GLOBALS['db']->executeQuery($selectAll);

        foreach ($listRolesAll as $key => $item) {
            $listRolesAll[$key] = [...$item, "groupId" => '', 2 => ''];
        }

        $arrayCombine = array_merge($listRolesAll, $listRoles);
        $arrayMerge = mergeArraySameKeys($arrayCombine, 1);
        $roleNotExist = [];
        foreach ($arrayMerge as $item) {
            if (count($item) === 1) {
                array_push($roleNotExist, $item);
            }
        }

        return array_merge($listRoles, ...$roleNotExist);
    }

    public function getRolesByQuizId($quizId)
    {
        $select = "SELECT role.id, role.description, role.image  
        FROM role JOIN quiz_role ON role.id = quiz_role.roleId
        WHERE quiz_role.quizId = $quizId";

        return $GLOBALS['db']->executeQuery($select);
    }

    public function createANewRole($url)
    {
        $query = "INSERT INTO `role` (`url`) VALUES (?)";

        return $GLOBALS['db']->executeQuery($query, [$url]);
    }

    public function getRolesBy($condition)
    {
        $column = key($condition);
        $query = "SELECT * FROM `role` WHERE $column = '{$condition[$column]}'";

        return $GLOBALS['db']->executeQuery($query);
    }

    public function deleteRolesBy($condition)
    {
        $column = key($condition);
        $query = "DELETE FROM `role` WHERE $column = '{$condition[$column]}'";

        return $GLOBALS['db']->executeQuery($query);
    }
}
