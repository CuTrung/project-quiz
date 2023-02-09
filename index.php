<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,600;1,700&family=Amatic+SC:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="src/assets/css/main.css" rel="stylesheet">

    <!-- References to library -->
    <?php include './src/lib/libReferences.php'; ?>

    <!-- My style -->
    <link href="src/assets/css/both/loading.css" rel="stylesheet">
    <link href="src/assets/css/admin/indexParticipant.css" rel="stylesheet">
    <link href="src/assets/css/user/header.css" rel="stylesheet">
    <link href="src/assets/css/admin/indexQuiz.css" rel="stylesheet">

    <!-- Init Models -->
    <?php include './src/models/initModels.php'; ?>

    <!-- Use utils -->
    <?php include './src/utils/myIcons.php'; ?>
    <?php include './src/utils/myUtils.php'; ?>
    <?php include './src/utils/validationUtils.php'; ?>

    <!-- Start SESSION PHP -->
    <?php session_start(); ?>

    <?php loadENV(); ?>
</head>

<body>

    <?php include './src/views/user/header.php'; ?>

    <?php include './src/routes/initRoutes.php'; ?>

    <!-- <?php include './src/views/user/footer.php'; ?> -->


</body>

</html>