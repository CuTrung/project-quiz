<?php

$param = explode('&', $_SERVER['QUERY_STRING'])[0];

// Rules: Nếu url có nhiều params thì bắt buộc params đầu tiên chỉ dùng để  
// render ra view tương ứng (Ex: ?question&quizId=1) => question
switch ($param) {
    case 'login':
        include $controller->render('views/both/login.php');
        break;
    case 'quiz':
        removeSessionStorage(
            '["timeout", "checkedBefore", "autoFinishSuccess", "wrong"]'
        );
        include $controller->render('views/user/indexQuiz.php');
        break;
    case 'question':
        include $controller->render('views/user/indexQuestion.php');
        break;
    default:
        include $controller->render('views/user/homePage.php');
        break;
}
