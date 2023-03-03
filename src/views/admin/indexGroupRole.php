<?php
$listRoles = $roleModel->getRoles();
$listGroups = $groupModel->getGroups();
$listGroupsMerge = mergeArraySameKeys($listGroups, 'name');

$isRole = false;
if (isset($_REQUEST['role'])) {
    $isRole = true;
}

?>

<div class="row">
    <div class="col-4">
        <h3>Add new <?= $isRole ? 'role' : 'group'; ?>
            <a href="?admin&<?= $isRole ? 'group' : 'role'; ?>" class="btn btn-outline-info btn-sm">Add new <?= $isRole ? 'group' : 'role'; ?></a>
        </h3>

        <form action="?admin&<?= $isRole ? 'role' : 'group'; ?>" method="post">
            <div class="form-floating mb-3">
                <input name="<?= $isRole ? 'url' : 'name'; ?>" type="text" class="form-control" id="floatingInput" placeholder="name@example.com">
                <label for="floatingInput"><?= $isRole ? 'Url' : 'Name'; ?></label>
            </div>

            <button class='btn btn-primary me-3' type='submit'>Submit</button>
        </form>

        <hr />

        <form action="?admin&group" method="post">
            <h3 class='my-3'>Set role for group</h3>
            <select onchange="handleSelect(this)" name="group" class="form-select mb-3" aria-label="Default select example">
                <option hidden selected>Choose group</option>
                <?php foreach ($listGroupsMerge as $key => $group) { ?>
                    <option value="<?= $group[0]['groupId']; ?>"><?= $key; ?></option>
                <?php } ?>
            </select>

            <span class='d-flex gap-3 flex-wrap'>
                <?php foreach ($listRoles as $role) { ?>
                    <span class='d-flex gap-2'>
                        <input name="role[]" class="role form-check-input" type="checkbox" data-groupId="<?= $role['groupId'] ?? '' ? implode('-', $role['groupId']) : ''; ?>" value="<?= $role['id'] ?>"> <?= $role['url']; ?>
                        <a href="?admin&role&delete&roleId=<?= $role['id'] ?>">
                            <i class="fa-solid fa-trash text-danger mt-1"></i>
                        </a>
                    </span>
                <?php } ?>
            </span>

            <button class='btn btn-primary me-3 mt-3' type='submit'>Submit</button>
            <a href="?admin&role" class='btn btn-warning me-3 mt-3'>Clear</a>
        </form>
    </div>


    <div class="col-8">
        <h3 class='text-center my-3'>List Group Roles</h3>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">Group</th>
                    <th scope="col">Role</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listGroupsMerge as $keyGroup => $group) { ?>
                    <tr>
                        <td>
                            <?= $keyGroup ?>
                            <a href="?admin&group&delete&groupId=<?= $group[0]['groupId'];  ?>">
                                <i class="fa-solid fa-trash text-danger float-end"></i>
                            </a>
                        </td>
                        <td>
                            <?php foreach ($group as $key => $role) { ?>
                                <?php $key++; ?>
                                <p class="m-0 mb-1 <?= $role['url'] ? '' : 'd-none'; ?>"><?= "<strong>$key. </strong>" . $role['url']; ?>
                                    <a href="?admin&role&delete&roleListId=<?= $role['roleId'] . '&groupRoleListId=' . $group[0]['groupId']; ?>">
                                        <i class="fa-solid fa-trash text-danger float-end"></i>
                                    </a>
                                </p>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script>

</script>
<?php
useJavaScript("
    function handleSelect(e){
        for (const item of document.querySelectorAll('.role')) {
            item.checked = false;
            if(item.getAttribute('data-groupId').split('-').includes(e.value)){
                item.checked = true;
            }
        }
    }
");

if (isset($_REQUEST['url']) || isset($_REQUEST['name'])) {
    $url = $_REQUEST['url'] ?? '';
    $name = $_REQUEST['name'] ?? '';

    if ($url !== '') {
        $roleModel->createANewRole($url);
    }

    if ($name !== '') {
        $groupModel->createANewGroup(strtoupper($name));
    }

    reloadCurrentPage(0);
}

if (isset($_REQUEST['roleListId']) || isset($_REQUEST['roleId']) || isset($_REQUEST['groupId'])) {
    $roleListId = $_REQUEST['roleListId'] ?? '';
    $groupRoleListId = $_REQUEST['groupRoleListId'] ?? '';
    $roleId = $_REQUEST['roleId'] ?? '';
    $groupId = $_REQUEST['groupId'] ?? '';

    if ($roleListId !== '' && $groupRoleListId !== '') {
        $groupModel->deleteGroup_RoleBy(['groupId' => $groupRoleListId, 'roleId' => $roleListId]);
    }

    if ($roleId !== '') {
        $roleModel->deleteRolesBy(['id' => $roleId]);
        $groupModel->deleteGroup_RoleBy(['roleId' => $roleId]);
    }

    if ($groupId !== '') {
        $groupModel->deleteGroup_RoleBy(['groupId' => $groupId]);
        // Update groupId of participant to STUDENT
        $participantModel->updateParticipantsGroupId($groupId, 1);

        $groupModel->deleteGroupsBy(['id' => $groupId]);
    }

    reloadCurrentPage(0, '?admin&' . ($groupId ? 'group' : 'role'));
}

if (isset($_REQUEST['role']) && isset($_REQUEST['group'])) {
    $roles = $_REQUEST['role'];
    $group = $_REQUEST['group'];

    $groupModel->deleteGroup_RoleBy(["groupId" => $group]);
    foreach ($roles as $role) {
        $groupModel->createANewGroup_Role(["groupId" => $group, "roleId" => $role]);
    }

    reloadCurrentPage(0);
}


?>