<?php
$userId = $_SESSION['user']['id'];

if (isset($_REQUEST['quizId']) && isset($_REQUEST['incorrect']) && isset($_REQUEST['timeStart']) && isset($_REQUEST['timeEnd'])) {
    $totalQuestionCorrect = count($quizModel->getQuiz_QuestionBy(['quizId' => $_REQUEST['quizId']])) - +$_REQUEST['incorrect'];

    $data = [
        "quizId" => $_REQUEST['quizId'],
        "participantId" => $userId,
        "timeStart" => $_REQUEST['timeStart'],
        "timeEnd" => $_REQUEST['timeEnd'],
        "totalQuestionCorrect" => $totalQuestionCorrect
    ];

    $historyModel->updateHistoriesBy($data);

    reloadCurrentPage(0, "?history");
}

$histories = $historyModel->getHistoriesBy(["participantId" => $userId]);

?>

<div class="container mt-5 pt-5">
    <h3 class="text-center">Histories</h3>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">Name quiz</th>
                <th scope="col">Difficulty</th>
                <th scope="col">Correct / Total (Question)</th>
                <th scope="col">Time start</th>
                <th scope="col">Time end</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($histories as $history) { ?>
                <tr>
                    <td><?= $history['quizName']; ?></td>
                    <td><?= $history['difficulty']; ?></td>
                    <td><?= $history['timeStart'] ? $history['totalQuestionCorrect'] . '/' . count($quizModel->getQuiz_QuestionBy(['quizId' => $history['quizId']])) : '' ?></td>
                    <td><?= $history['timeStart']; ?></td>
                    <td><?= $history['timeEnd']; ?></th>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>