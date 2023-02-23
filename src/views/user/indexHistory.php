<?php

$_SESSION['user']['id'];
$histories = $historyModel->getHistoriesBy(["participantId" => "2"]);

print_r($histories);
?>

<div class="container mt-5 pt-5">
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">Name quiz</th>
                <th scope="col">Total Question</th>
                <th scope="col">Total Question Correct</th>
                <th scope="col">Time start</th>
                <th scope="col">Time end</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($histories as $history) { ?>
                <tr>
                    <th><?= $history['name']; ?></th>
                    <th><?= $history['totalQuestion']; ?></th>
                    <th><?= $history['totalQuestionCorrect']; ?></th>
                    <th><?= $history['timeStart']; ?></th>
                    <th><?= $history['timeEnd']; ?></th>
                    <th>
                        <button class="btn btn-secondary">Details</button>
                    </th>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>