<!-- BUGS: 
    - Khi finish chỉ tạm thời ẩn timer đếm ngược, thực tế timer đang được
    set value về đúng timer khởi tạo ban đầu
    - Chưa thống kê được số câu đúng và sai
    - Nếu chỉ vừa chọn vừa bỏ answer question thì chưa tính đúng incorrect
    - Khi random question thì lúc finish thì ko giữ nguyên mà random lại 
-->
<div class="container my-3">
    <?php
    $questions = $questionModel->getQuestionsByQuizId(+$_REQUEST['quizId']);
    $totalQuestion = count($questions);
    if ($totalQuestion === 0) {
        echo "<h1 class='d-flex justify-content-center mt-5 pt-5 text-danger'>
        Can't found any question in this quiz :((
        </h1>";
        return;
    }
    ?>

    <form action="?question&quizId=<?= $_REQUEST['quizId']; ?>&timeout" class="row mt-5 pt-5" method="post">
        <input type="text" name="quizId" hidden value="<?= $_REQUEST['quizId']; ?>">
        <div class="col-9">
            <?php
            $isRandomQuestions = $_SESSION['isRandomQuestions'] ?? false;
            if (!$isRandomQuestions) {
                shuffle($questions);
                $_SESSION['isRandomQuestions'] = true;
            }
            ?>
            <?php foreach ($questions as $index => $question) { ?>
                <div id="question-<?= $index + 1 ?>" class="questionMain card mb-5">
                    <div class="card-body text-center">
                        <img src="<?= getUrlImg($question['image']); ?>" alt="">
                        <h4 class="text-start mt-3">Question's <?= $index + 1 ?>: <?= $question['description'] ?></h4>
                    </div>
                    <!-- Với input có name='test[]' thì $_REQUEST['text'] là một Array -->
                    <div class="card_question card-footer">
                        <?php
                        $answers = $answerModel->getAnswersByQuestionId($question['id']);
                        $isRandomAnswers = $_SESSION['isRandomAnswers'] ?? false;
                        if (!$isRandomAnswers) {
                            shuffle($answers);
                            $_SESSION['isRandomAnswers'] = true;
                        }
                        ?>
                        <?php
                        foreach ($answers as $indexAnswer => $answer) { ?>
                            <div class="form-check">
                                <input data-questionIndex='<?= $index + 1 ?>' onclick="handleOnClick(this)" data-isCorrect="<?= $answer['isCorrect'] ?>" class="checkbox form-check-input" name="answerIds[<?= $answer['id']; ?>]" value="<?= $answer['id'] ?? ''; ?>" type="checkbox">
                                <label class="form-check-label" for="flexCheckDefault">
                                    <?= $indexAnswer + 1 ?>. <?= $answer['description'] ?>
                                </label>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="col-3 position-relative">
            <div class="card position-fixed w-25">
                <div class="card-header text-center">
                    <span id="time" class="border border-2 border-success p-1">00:00</span>
                    <span id="finishMess" class="border border-2 border-success p-1 d-none">Nộp bài thành công</span>
                </div>
                <div class="card-body d-flex flex-wrap">
                    <?php foreach ($questions as $index => $question) { ?>
                        <a href="#question-<?= $index + 1 ?>" class="question btn btn-secondary rounded-circle me-2 mb-2"><?= $index + 1 ?></a>
                    <?php } ?>
                </div>
                <div class="card-footer">
                    <a class="btnResult btn btn-danger <?= isset($_REQUEST['timeout']) ?? '' ? '' : 'd-none';  ?>" id="history" href="?history&quizId=<?= $_REQUEST['quizId'];  ?>">Save result</a>
                    <button type="submit" class="btnFinish btn btn-info float-end">Finish</button>
                </div>
            </div>
        </div>
    </form>
</div>
</div>


<?php

useJavaScript("
    if(!window.sessionStorage.getItem('timeStart'))
        window.sessionStorage.setItem('timeStart', new Date().toLocaleString().replace(',', '').split(' ').join('|'));
");


function toggleColorQuestion()
{
    useJavaScript("
        let isCheckedBefore = (value) => {
            return [...value.querySelectorAll('input')].find(item => item.checked);
        }

        function handleOnClick(e) {
            for (const item of document.querySelectorAll('.question')) {
                if (+item.innerText === +e.getAttribute('data-questionIndex')) {
                    item.classList.add('btn-warning');
                    if (!isCheckedBefore(e.parentNode.parentNode))
                        item.classList.remove('btn-warning');
                }
            }
        }
    ");
}
toggleColorQuestion();

function autoClickFinish()
{
    useJavaScript("
        let arrChecked = JSON.parse(window.sessionStorage.getItem('checkedBefore'));
        if(arrChecked){
            for (const item of document.querySelectorAll('input')) {
                if(arrChecked.includes(item.value)){
                    item.checked = true;
                }
            }
        
            if(!window.sessionStorage.getItem('autoFinishSuccess'))
                document.querySelector('.btnFinish').click();
            
            window.sessionStorage.setItem('autoFinishSuccess', true);
        }
    ");
}


function disableAndCheckedCheckbox($answer, $isShowAnswer = 0)
{
    useJavaScript("
            if(!window.sessionStorage.getItem('incorrect')){
                window.sessionStorage.setItem('incorrect', 0);
            }
            document.querySelector('.btnFinish').classList.add('disabled');

            for (const item of document.querySelectorAll('input')) {
                item.disabled = true;
                if(+item.value === +{$answer['id']}){
                    item.checked = true;
                }

                if($isShowAnswer || window.sessionStorage.getItem('autoFinishSuccess')){
                    if(+item.value === +{$answer['id']}){
                        item.checked = true;
                        if({$answer['isCorrect']} === 0){
                            item.classList.add('is-invalid');
                            window.sessionStorage.setItem('incorrect', +window.sessionStorage.getItem('incorrect') + 1);
                        } else {
                            item.classList.add('is-valid');
                        }
                    }
                    if(+item.getAttribute('data-isCorrect') === 1){
                        item.classList.add('is-valid');
                    }
                } else {
                    document.querySelector('.btnResult').classList.add('d-none');
                } 
            }

            window.sessionStorage.setItem('timeEnd', new Date().toLocaleString().replace(',', '').split(' ').join('|'));
            
            document.getElementById('history').href += '&incorrect=' + (+window.sessionStorage.getItem('incorrect') + (document.querySelectorAll('.questionMain').length - JSON.parse(window.sessionStorage.getItem('checkedBefore'))?.length)) + '&timeStart=' + window.sessionStorage.getItem('timeStart') + '&timeEnd=' + window.sessionStorage.getItem('timeEnd');
    ");
}



function disableTimer()
{
    useJavaScript("
        document.querySelector('#time').classList.add('d-none');
        document.querySelector('#finishMess').classList.remove('d-none');
    ");
}


countDownTimer((int)$_ENV['TIME_COUNT_DOWN'], '#time');

// Có check nhưng hết giờ auto nộp bài
autoClickFinish();
if (isset($_REQUEST['answerIds'])) {
    disableTimer();
    $answersIds = $_REQUEST['answerIds'];
    foreach ($answersIds as $answerId) {
        $answer = $answerModel->getAnswersById($answerId);
        if ($answer)
            disableAndCheckedCheckbox($answer);
    }
}



// Nhấn finish nhưng ko làm bài
if ($param = explode('&', $_SERVER['QUERY_STRING'])[0] !== 'question') {
    disableAndCheckedCheckbox(['id' => '-1', 'isCorrect' => '-1']);
}


// Hết giờ show đáp án 
if (isset($_REQUEST['timeout'])) {
    $answersIds = $_REQUEST['answerIds'] ?? '';
    if ($answersIds) {
        // Khi có check và nhấn finish 
        foreach ($answersIds as $answerId) {
            $answer = $answerModel->getAnswersById($answerId);
            if ($answer)
                disableAndCheckedCheckbox($answer, 1);
        }
    } else {
        // Khi ko check
        disableAndCheckedCheckbox(['id' => '-1', 'isCorrect' => '-1'], 0);
    }
}

?>