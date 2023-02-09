<?php

$param = explode('&', $_SERVER['QUERY_STRING'])[0];

// Rules: Nếu url có nhiều params thì bắt buộc params đầu tiên chỉ dùng để  
// render ra view tương ứng (Ex: ?question&quizId=1) => question

$result = in_array($param, ["login", "logout", "", "register"]);
if (!$result && !$_SESSION['user']) {
    // Login mới cho đi tiếp
    reloadCurrentPage(0, '?login');
}

switch ($param) {
    case 'register':
    case 'login':
        include $controller->render('views/both/login.php');
        break;
    case 'logout':
        session_destroy();
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
    case 'history':
        include $controller->render('views/user/indexHistory.php');
        break;
    default:
        include $controller->render('views/user/homePage.php');
        break;
}
