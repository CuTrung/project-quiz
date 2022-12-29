<?php
$quizzes = $quizModel->getQuizzes();
if (count($quizzes) === 0) {
    echo "<h1 class='d-flex justify-content-center mt-5 pt-5 text-danger'>
    Can't found any quizzes :((
    </h1>";
    return;
}
?>


<div class="container my-5 pt-5 d-flex flex-wrap justify-content-around text-center">
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