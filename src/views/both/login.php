<?php if (isset($_SESSION['user']) && $_SESSION['user']['name'] !== '') {
    reloadCurrentPage(0, '?');
}
if (isset($_REQUEST['logout'])) {
    // Load lại để render lại UI
    reloadCurrentPage(0, '?login');
}

$isRegister = false;
if (isset($_REQUEST['register'])) {
    $isRegister = true;
}

?>

<div class="container w-50 text-center my-3 mt-5 pt-5">
    <h3 className="my-3 "><?= $isRegister ? 'REGISTER' : 'LOGIN'; ?></h3>
    <Form action="?login" id="loginForm" method="post">
        <div class="form-floating mb-3 text-start <?= $isRegister ? '' : 'd-none'; ?>">
            <input name="name" type="text" class="form-control" id="floatingInput" placeholder="name@example.com">
            <label for="floatingInput">Name</label>
        </div>
        <div class="form-floating mb-3 text-start">
            <input name="email" type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
            <label for="floatingInput">Email address</label>
        </div>
        <div class="form-floating mb-3 text-start">
            <input name="password" type="text" class="form-control" id="floatingPassword" placeholder="Password">
            <label for="floatingPassword">Password</label>
        </div>

        <button type="submit" class="btn btn-<?= $isRegister ? 'info' : 'primary'; ?> w-100"><?= $isRegister ? 'Register' : 'Login'; ?></button>
    </Form>
</div>

<?php

if (isset($_REQUEST['email']) && isset($_REQUEST['password'])) {
    $email = $_REQUEST['email'];
    $password = $_REQUEST['password'];
    $isSuccess = false;

    $name = $_REQUEST['name'];


    // validate
    if ($email === '' || $password === '') {
        $name ? reloadCurrentPage(1, "?register") : reloadCurrentPage(1, "?login");
        return toast('error', 'Please input all field');
    }

    $participant = $participantModel->getUniqueParticipantBy('email', $email);

    if ($participant) {

        if ($name) {
            reloadCurrentPage(1, '?register');
            return toast('error', 'Email already existed!');
        }

        $password = hashPassword($password);
        if ($participant['password'] === $password) {
            unset($participant['password']);
            $_SESSION['user'] = $participant;
            $isSuccess = true;
            toast('success', "Login success!");
        } else {
            // toast('error', "Incorrect password, try again !");
            toast('error', "Login failed, try again !");
        }
    } else {

        if ($name) {
            $password = hashPassword($password);
            $isSuccess = $participantModel->upsertAParticipant($name, $email, $password);

            if ($isSuccess) {
                toast('success', "Register successful! Login now !");
                return reloadCurrentPage(1, '?login');
            } else {
                return toast('error', "Register failed!");
            }
        }

        // toast('error', "Email not existed, try again !");
        toast('error', "Login failed, try again !");
    }


    if ($isSuccess) {
        if ($_SESSION['user']['email'] === $_ENV['EMAIL_ADMIN']) {
            return reloadCurrentPage(1, '?admin&participant&page=1');
        }
        reloadCurrentPage(1, '?');
    } else {
        reloadCurrentPage(1, '?login');
    }
}
?>