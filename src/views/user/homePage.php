<?php
if (isset($_REQUEST['quizId'])) {
    include $controller->render('views/user/indexQuestion.php');
    return;
}
?>

<section id="hero" class="hero d-flex align-items-center section-bg">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-5 order-2 order-lg-1 d-flex flex-column justify-content-center align-items-center align-items-lg-start text-center text-lg-start">
                <h2 data-aos="fade-up" class="aos-init aos-animate">Enjoy Your Quizzes<br>Have Fun 😆</h2>
                <p data-aos="fade-up" data-aos-delay="100" class="aos-init aos-animate">Sed autem laudantium dolores. Voluptatem itaque ea consequatur eveniet. Eum quas beatae cumque eum quaerat.</p>
                <div class="d-flex aos-init aos-animate" data-aos="fade-up" data-aos-delay="200">
                    <a href="?quiz" class="btn-book-a-table text-decoration-none">Do Quiz now</a>
                </div>
            </div>
            <div class="col-lg-5 order-1 order-lg-2 text-center text-lg-start">
                <img src="<?= getUrlImg("quizBanner.png") ?>" class="img-fluid aos-init aos-animate" alt="" data-aos="zoom-out" data-aos-delay="300">
            </div>
        </div>
    </div>
</section>