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

        $listRolesMerge = mergeArraySameKeys($listRoles, 0);

        foreach ($listRolesMerge as $key => $item) {
            $item[0]['groupId'] = [$item[0]['groupId']];
            if (count($item) > 1) {
                for ($i = 1; $i < count($item); $i++) {
                    $item[0]['groupId'] = [...$item[0]['groupId'], $item[$i]['groupId']];
                }
            }
            $listRolesMerge[$key] = $item[0];
        }

        $listRolesIdExist = [];
        foreach ($listRolesMerge as $key => $role) {
            array_push($listRolesIdExist, $role['id']);
        }

        $listRolesIdExist = implode(",", $listRolesIdExist);
        $selectListRolesExist = "SELECT * FROM `role` WHERE id NOT IN ($listRolesIdExist)" . ($quantity ? "LIMIT $quantity" : "");
        $listRolesExist = $GLOBALS['db']->executeQuery($selectListRolesExist);

        return array_merge($listRolesMerge, $listRolesExist);
    }

    public function getRolesByGroupId($groupId)
    {
        $select = "SELECT role.id, role.url, g_r.groupId as groupId  
        FROM `role` r INNER JOIN group_role g_r ON r.id = g_r.roleId INNER JOIN 
        WHERE g_r.groupId = $groupId";

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
