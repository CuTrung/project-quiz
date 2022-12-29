<?php
$participants = $participantModel->getParticipants();

if (isset($_REQUEST['name'])) {
    $name = $_REQUEST['name'];
    $email = $_REQUEST['email'];
    $password = $_REQUEST['password'];

    $participantModel->createANewParticipant($name, $email, $password);
}
?>


<!-- Form add new participant -->
<form method="post">
    <h3>Add new participant</h3>
    <div class="form-floating mb-3">
        <input name="name" type="text" class="form-control" id="floatingInput" placeholder="name@example.com">
        <label for="floatingInput">Name</label>
    </div>
    <div class="form-floating mb-3">
        <input name="email" type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
        <label for="floatingInput">Email</label>
    </div>
    <div class="form-floating">
        <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password">
        <label for="floatingPassword">Password</label>
    </div>
    <button class="btn btn-primary mt-3">Submit</button>
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
        <?php foreach ($participants as $index => $participant) { ?>
            <tr>
                <th><?= $index + 1 ?></th>
                <td><?= $participant['name'] ?></td>
                <td><?= $participant['email'] ?></td>
                <td>
                    <button class="btn btn-warning">Edit</button>
                    <button class="btn btn-danger">Delete</button>
                </td>
            </tr>

        <?php } ?>



    </tbody>
</table>