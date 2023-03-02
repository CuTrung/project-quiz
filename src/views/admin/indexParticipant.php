<!-- 
    BUGS:
    - Về cơ bản PHP sẽ reload page when submit so nên việc validate form dường
    như chỉ làm khi nhấn submit, điều này sẽ clear toàn bộ data cũng như 
    animation. Khó để lưu lại data user đã nhập trước đó.
    - Để tạo cảm giác update real time ta buộc phải reload lại page when submit điều này sẽ làm mất animation (ở đây sử dụng toastr), để thấy được ta cần chỉ định duration page sẽ refresh 
-->
<?php

$GLOBALS['participantModel'] = $participantModel;
$participants = $participantModel->getParticipants();

$groups = $groupModel->getGroups();

logArray($groups);

if (isset($_REQUEST['page'])) {
    $page = $_REQUEST['page'];
    $limit = 3;
    $dataParticipantsWithPagination = $participantModel->getParticipantsWithPagination($page, $limit);
    $participants = $dataParticipantsWithPagination['participants'];
}


function button()
{
    $button = [
        'content' => 'Submit',
        'color' => 'primary',
        'headerForm' => 'Add New',
        "auxiliaryColor" => 'warning',
        "auxiliaryContent" => 'Clear',
    ];
    if (isset($_REQUEST['edit'])) {
        $button['content'] = 'Save';
        $button['color'] = 'success';
        $button['headerForm'] = 'Update';
        $button['auxiliaryColor'] = 'danger';
        $button['auxiliaryContent'] = 'Cancel';
    }

    return $button;
}
?>

<form id="upsertForm" method="post">
    <h3><?= button()['headerForm'] ?> A Participant: <span class="nameUpdate text-danger fst-italic"></span></h3>
    <div class="form-floating mb-3">
        <input name="name" type="text" class="form-control" placeholder="name@example.com">
        <label for="floatingInput">Name</label>
    </div>
    <div class="form-floating mb-3">
        <input name="email" type="text" class="form-control" placeholder="name@example.com">
        <label for="floatingInput">Email</label>
    </div>
    <div class="form-floating mb-3 position-relative">
        <input name="password" type="text" class="form-control" placeholder="Password">
        <label for="floatingPassword">Password</label>
        <i onclick="handleEye(this)" class="eye fa-solid fa-eye position-absolute top-0 end-0"></i>
    </div>

    <select name="group" class="form-select mb-3 w-25" aria-label="Default select example">
        <option hidden selected value="">Choose group</option>
        <?php foreach ($groups as $key => $group) { ?>
            <option value="<?= $group['groupId']; ?>"><?= $group['name']; ?></option>
        <?php } ?>
    </select>

    <button type="submit" class="me-3 btn btn-<?= button()['color'] ?>">
        <?= button()['content'] ?>
    </button>
    <a href='?admin&participant' class='btn btn-<?= button()['auxiliaryColor'] ?>'>
        <?= button()['auxiliaryContent'] ?>
    </a>

</form>


<!-- List participants -->
<table class="table table-hover table-bordered">
    <h3 class="my-3">List participants</h3>
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($participants as $index => $participant) { ?>
            <tr>
                <th><?= $index + 1 ?></th>
                <td><?= $participant['name'] ?></td>
                <td><?= $participant['email'] ?></td>
                <td>
                    <a href="?admin&participant&page=<?= $page; ?>&edit=<?= $participant['id']; ?>" class="btn btn-warning">Edit</a>
                    <a href="?admin&participant&delete=<?= $participant['id']; ?>" class="btn btn-danger">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<!-- Pagination -->
<?php include $controller->render('views/both/pagination.php');

if (isset($_REQUEST['page']))
    Pagination((int)$dataParticipantsWithPagination['totalPages']);

?>

<?php

useJavaScript("
    function handleEye(e){
        e.classList.toggle('fa-eye-slash');
        if(e.classList.contains('fa-eye-slash'))
            e.parentNode.querySelector('input').type = 'password';
        else
            e.parentNode.querySelector('input').type = 'text';
    }
");

function isEmailExists($email)
{
    $participant = $GLOBALS['participantModel']->getUniqueParticipantBy('email', $email);
    if ($participant) return true;
    return false;
}

if (isset($_REQUEST['name'], $_REQUEST['email'], $_REQUEST['password'], $_REQUEST['group'])) {
    $name = $_REQUEST['name'];
    $email = $_REQUEST['email'];
    $password = $_REQUEST['password'];
    $group = $_REQUEST['group'];

    // Update
    $id = $_REQUEST['edit'] ?? '';

    if ($name === '' || $email === '' || $password === '' || $group === '') {
        toast('error', "Please input all field");
        if ($id)
            setValueInputWhenEdit();
        return;
    }

    if (!isValidEmail($email)) {
        toast('error', "Email is invalid, try again !");
        return;
    }

    if (isEmailExists($email) && !$id) {
        toast('error', "Email is existed, try again !");
        return;
    }

    $password = hashPassword($password);
    $isSuccess = $participantModel->upsertAParticipant($name, $email, $password, $group, $id);

    if ($isSuccess) {
        toast('success', ($id ? 'Update' : 'Create') . " a participant successful !");
    } else {
        toast('error', ($id ? 'Update' : 'Create') . " a participant failed !");
    }

    // Reload page in order to clear form data 
    if ($id) {
        // Go to form submit after edit success
        reloadCurrentPage(1, "?admin&participant&page=$page");
        return;
    }
    reloadCurrentPage(1);
}

function setValueInputWhenEdit()
{
    $participant = $GLOBALS['participantModel']->getUniqueParticipantBy('id', $_REQUEST['edit']);
    useJavaScript("
        document.querySelector('[name=name]').value = '{$participant['name']}';
        document.querySelector('[name=email]').value = '{$participant['email']}';
        document.querySelector('[name=password]').value = '{$participant['password']}';
        document.querySelector('[name=group]').value = '{$participant['groupId']}';
        document.querySelector('.nameUpdate').innerText = '{$participant['name']}';
    ");
}

if (isset($_REQUEST['edit'])) {
    setValueInputWhenEdit();
}

if (isset($_REQUEST['delete'])) {
    $idDelete = $_REQUEST['delete'];
    $isSuccess = $participantModel->deleteAParticipant($idDelete);
    if ($isSuccess) {
        toast('success', "Delete a participant successful !");
    } else {
        toast('error', "Delete a participant failed !");
    }

    // Reload page in order to clear form data 
    reloadCurrentPage(1, '?admin&participant');
}
?>