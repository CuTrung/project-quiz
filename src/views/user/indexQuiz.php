<?php

$quizzes = $quizModel->getQuizzes();
if (count($quizzes) === 0) {
    echo "<h1 class='d-flex justify-content-center mt-5 pt-5 text-danger'>
    Can't found any quizzes :((
    </h1>";
    return;
}

if (isset($_REQUEST['search'])) {
    $search = $_REQUEST['search'];
    echo $search;
    $listQuizzesSearch = $quizModel->getListQuizzesWhenSearch($search);
    if (count($listQuizzesSearch) > 0)
        $quizzes = $listQuizzesSearch;
    else
        toast("error", 'Not found quizzes');
}
?>


<div class="container d-flex flex-column">
    <form action="?quiz&search" method="post" class="d-flex gap-3" style="margin-top: 100px;">
        <input name="search" class="form-control w-25" type="text" placeholder="Searching...">
        <button class="btn btn-outline-info">Tìm kiếm</button>
    </form>
    <div class="d-flex flex-wrap justify-content-around text-center my-5">
        <?php foreach ($quizzes as $index => $quiz) { ?>
            <div class="card mb-3">
                <span class="badge bg-<?= $quiz['difficulty'] === 'HARD' ? 'danger' : ($quiz['difficulty'] === 'MEDIUM' ? 'warning' : 'success'); ?> w-25 position-absolute top-0 end-0">
                    <?= $quiz['difficulty'] ?>
                </span>
                <div class="card-body">
                    <img class="mt-3" src="<?= getUrlImg($quiz['image']); ?>" alt="">
                </div>
                <div class="card-footer">
                    <h4>Quiz's <?= $index + 1 ?>: <?= $quiz['name'] ?></h4>
                    <a href="?question&quizId=<?= $quiz['id']; ?>" class="btn btn-info">Try now</a>
                </div>
            </div>
        <?php } ?>
    </div>
</div>