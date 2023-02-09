<!-- BUGS: 
    - Khi finish chỉ tạm thời ẩn timer đếm ngược, thực tế timer đang được
    set value về đúng timer khởi tạo ban đầu
    - Chưa thống kê được số câu đúng và sai 
-->
<div class="container my-3">
    <?php
    $questions = $questionModel->getQuestionsByQuizId(+$_REQUEST['quizId']);
    if (count($questions) === 0) {
        echo "<h1 class='d-flex justify-content-center mt-5 pt-5 text-danger'>
        Can't found any question in this quiz :((
        </h1>";
        return;
    }
    ?>

    <form action="?" class="row mt-5 pt-5">
        <input type="text" name="quizId" hidden value="<?= $_REQUEST['quizId']; ?>">
        <div class="col-9">
            <?php  ?>
            <?php foreach ($questions as $index => $question) { ?>
                <div id="question-<?= $index + 1 ?>" class="card mb-5">
                    <div class="card-body text-center">
                        <img src="<?= getUrlImg($question['image']); ?>" alt="">
                        <h4 class="text-start mt-3">Question's <?= $index + 1 ?>: <?= $question['description'] ?></h4>
                    </div>
                    <!-- Với input có name='test[]' thì $_REQUEST['text'] là một Array -->
                    <div class="card_question card-footer">
                        <?php
                        $answers = $answerModel->getAnswersByQuestionId($question['id']);
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
                    <button type="submit" class="btnFinish btn btn-info float-end">Finish</button>

                    <!-- Chưa xử lí được phần đếm số câu đúng / sai -->
                    <!-- <table class="table table-hover mt-5 ">
                        <tbody>
                            <tr>
                                <td class="text-danger">Wrong</td>
                                <td class="wrong text-danger">0</td>
                            </tr>
                            <tr>
                                <td class="text-success">Correct</td>
                                <td class="correct text-success">0</td>
                            </tr>
                        </tbody>
                    </table> -->
                </div>
            </div>
        </div>
    </form>
</div>
</div>


<?php
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
                        } else {
                            item.classList.add('is-valid');
                        }
                    }
                    if(+item.getAttribute('data-isCorrect') === 1){
                        item.classList.add('is-valid');
                    }
                } 
            }
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
        disableAndCheckedCheckbox(['id' => '-1', 'isCorrect' => '-1'], 1);
    }
}



?>