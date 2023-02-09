<?php
$param = explode('&', $_SERVER['QUERY_STRING'])[1] ?? 'participant';

// Rules: Nếu url có nhiều params thì bắt buộc params thứ hai chỉ dùng để  
// render ra view tương ứng (Ex: ?question&quizId=1) => question
switch ($param) {
    case 'quiz':
        include $controller->render('views/admin/indexQuiz.php');
        break;
    case 'participant':
        include $controller->render('views/admin/indexParticipant.php');
        break;
}
