<?php

include './src/controllers/controller.php';
$controller = new Controller();


isset($_REQUEST['admin']) ?
    include $controller->render('controllers/adminController.php')
    :
    include $controller->render('controllers/userController.php');
