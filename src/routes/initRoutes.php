<?php

include './src/controllers/controller.php';
$controller = new Controller();

$GLOBALS['controller'] = $controller;

isset($_REQUEST['admin']) ?
    include $controller->render('controllers/adminController.php')
    :
    include $controller->render('controllers/userController.php');
