<?php
$listQuizzes = $quizModel->getQuizzes();


?>



<form id="formUpload" action="?admin&quiz" method="post" enctype="multipart/form-data" class="d-flex gap-3 border border-2 border-success p-2 w-75 container">
    <h3>Add new quiz - question - answer</h3>
    <input onchange="handleUpload()" hidden type="file" name="fileUpload" id="upload">
    <label class="labelExcel btn btn-outline-info" for="upload">Import excel</label>
    <button type="submit" class="btn btn-primary">
        Submit
    </button>
</form>

<div style="height: 60vh;" class="mt-5 overflow-auto">
    <h3 class="text-center">List quizzes</h3>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">Name quiz</th>
                <th scope="col">Difficulty quiz</th>
                <th scope="col">Description question</th>
                <th scope="col">Description answer</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listQuizzes as $quiz) { ?>
                <tr>
                    <td>
                        <form action="?admin&quiz" method="post" enctype="multipart/form-data">
                            <input hidden id="quizImg-<?= $quiz['id']; ?>" type="file" name="fileImgQuiz">
                            <input hidden type="text" name="quizId" value="<?= $quiz['id']; ?>">
                            <p class="float-start"><?= $quiz['name']; ?></p>
                            <span class="float-end">
                                <label for="quizImg-<?= $quiz['id']; ?>">
                                    <i style="cursor: pointer;" class="fa-solid fa-image text-info"></i>
                                </label>
                                <button class="btnSubmit btn btn-sm btn-outline-primary mx-2 py-0">submit</button>
                                <a href="?admin&quiz&delete&delQuiz=<?= $quiz['id']; ?>">
                                    <i style="cursor: pointer;" class="fa-solid fa-trash text-danger float-end mt-1"></i>
                                </a>
                            </span>
                        </form>
                    </td>
                    <td><?= $quiz['difficulty']; ?></td>
                    <?php
                    $listQuestions = $questionModel->getQuestionsByQuizId($quiz['id']);
                    ?>

                    <td>
                        <?php foreach ($listQuestions as $key => $question) { ?>
                            <?php $key++; ?>
                            <form action="?admin&quiz" method="post" enctype="multipart/form-data">
                                <?= "<strong>$key. </strong>" . $question['description']; ?>
                                <input hidden id="questionImg-<?= $question['id']; ?>" type="file" name="fileImgQuestion">
                                <input hidden type="text" name="questionId" value="<?= $question['id']; ?>">
                                <p class="m-0 mb-1 float-end">
                                    <label for="questionImg-<?= $question['id']; ?>">
                                        <i style="cursor: pointer;" class="fa-solid fa-image text-info"></i>
                                    </label>
                                    <button class="btnSubmit btn btn-sm btn-outline-primary mx-2 py-0">submit</button>

                                    <a href="?admin&quiz&delete&delQues=<?= $question['id']; ?>">
                                        <i style="cursor: pointer;" class="fa-solid fa-trash text-danger float-end"></i>
                                    </a>
                                </p>
                            </form>
                            <?= $key === count($listQuestions) ? '' : '<hr>'; ?>
                        <?php } ?>
                    </td>
                    <td>
                        <?php foreach ($listQuestions as $keyQue => $question) { ?>
                            <?php $keyQue++; ?>
                            <?php foreach ($answerModel->getAnswersByQuestionId($question['id']) as $keyAns => $answer) { ?>
                                <?php $keyAns++; ?>
                                <p class="m-0 mb-2"><?= "<strong>$keyAns. </strong>" . $answer['description']; ?>
                                    <a href="?admin&quiz&delete&delAns=<?= $answer['id']; ?>">
                                        <i style="cursor: pointer;" class="fa-solid fa-trash text-danger float-end"></i>
                                    </a>
                                </p>
                            <?php } ?>

                            <?= $keyQue === count($listQuestions) ? '' : '<hr>'; ?>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>


<?php

useJavaScript("
    function handleUpload() {
        let file = document.getElementById('upload').files[0];
        document.querySelector('.labelExcel').textContent = file.name;
    }
");


if (isset($_FILES['fileUpload']['tmp_name'])) {
    $file = $_FILES['fileUpload']['tmp_name'];

    if (!$file) return toast("error", "Please update file");

    $dataFile = loadXLSX($file);
    unset($dataFile[0]);

    $mergeQuestion = mergeArraySameKeys($dataFile, 1);
    foreach ($mergeQuestion as $key => $item) {
        $questionModel->createANewQuestion($key);
        $questionCreated = $questionModel->getQuestionsBy(['description' => $key])[0];

        $quizModel->createANewQuiz($item[0][0], $item[0][4]);
        $quizCreated = $quizModel->getQuizzesBy(['name' => $item[0][0]])[0];

        $quizModel->createANewQuiz_Question($quizCreated['id'], $questionCreated['id']);
        foreach ($item as $value) {
            $answerModel->createANewAnswer($value[2], $value[3] === 'TRUE' ? 1 : 0, $questionCreated['id']);
        }
    }

    toast("success", "Created successful !");
    reloadCurrentPage(1, '?admin&quiz');
}

if (isset($_REQUEST['delQuiz']) || isset($_REQUEST['delQues']) || isset($_REQUEST['delAns'])) {
    $delId = $_REQUEST['delQuiz'] ?? $_REQUEST['delQues'] ?? $_REQUEST['delAns'];

    if (isset($_REQUEST['delQuiz']) && $_REQUEST['delQuiz'] !== '') {
        $listQuestionsDelete = $questionModel->getQuestionsByQuizId($delId);
        foreach ($listQuestionsDelete as $question) {
            $answerModel->deleteAnswersBy(['questionId' => $question['id']]);
            $questionModel->deleteQuestionsBy(['id' => $question['id']]);
        }

        $quizModel->deleteQuiz_QuestionBy(['quizId' => $delId]);
        $quizModel->deleteQuizzesBy(['id' => $delId]);
        toast("success", "Delete quiz successful !");
    }

    if (isset($_REQUEST['delQues']) && $_REQUEST['delQues'] !== '') {
        $answerModel->deleteAnswersBy(['questionId' => $delId]);
        $questionModel->deleteQuestionsBy(['id' => $delId]);
        toast("success", "Delete question successful !");
    }


    if (isset($_REQUEST['delAns']) && $_REQUEST['delAns'] !== '') {
        $answerModel->deleteAnswersBy(['id' => $delId]);
        toast("success", "Delete answer successful !");
    }

    reloadCurrentPage(1, '?admin&quiz');
}

if (isset($_FILES['fileImgQuiz']) || isset($_FILES['fileImgQuestion'])) {
    $isSuccess = false;
    $quizId = $_REQUEST['quizId'];
    if ($quizId) {
        $isSuccess = uploadImage('fileImgQuiz');
        $quizModel->updateImageQuizById($_FILES['fileImgQuiz']['name'], $quizId);
    }

    $questionId = $_REQUEST['questionId'];
    if ($questionId) {
        $isSuccess = uploadImage('fileImgQuestion');
        $questionModel->updateImageQuestionById($_FILES['fileImgQuestion']['name'], $questionId);
    }

    if ($isSuccess) {
        $mess = $quizId ? 'quiz' : 'question';
        toast("success", "Upload $mess image successful !");
    } else {
        toast("error", "Upload image failed !");
    }

    return reloadCurrentPage(1, '?admin&quiz');
}







?>