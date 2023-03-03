<?php
if ($_SESSION['user']['groupName'] === 'STUDENT') {
    return reloadCurrentPage(0, '?');
}
?>

<div class="row my-3 mt-5 pt-5">
    <div class="col-3">
        <div class="card">
            <div class="card-header">
                <button type="button" class="btn btn-success w-100">Logo</button>
            </div>
            <div class="card-body">
                <a class="w-100 mb-3 btn btn-primary" href="?admin&participant&page=1">
                    Participant
                </a>
                <a class="btn btn-info w-100 mb-3" href="?admin&quiz">Quiz</a>
                <a class="btn btn-secondary w-100 mb-3" href="?admin&group">Group Role</a>
            </div>
        </div>
    </div>
    <div class="col-9">
        <?php
        include $controller->render('controllers/adminContentController.php');
        ?>
    </div>
</div>