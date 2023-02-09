<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <div class="container-fluid ">
        <a class="navbar-brand p-0 m-0" href="?home" style="height: 50px; width: 100px;">
            <img class="w-100" src="<?= getUrlImg('logo.png'); ?>" alt="">
        </a>
        <div class="collapse navbar-collapse position-relative" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" href="?">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="?history">History</a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link" href="?admin&participant&page=1">Admin</a>
                </li> -->
                <li class="nav-item <?= showUserOrLogin()['classBootstrap']; ?> position-absolute top-0 end-0">
                    <?= showUserOrLogin()['content']; ?>
                </li>
            </ul>
        </div>
    </div>
</nav>

<?php
function showUserOrLogin()
{
    $item = ['content' => '', 'classBootstrap' => ''];

    if (isset($_SESSION['user'])) {
        $item['content'] = "
        <a class='nav-link' href='#' id='navbarDarkDropdownMenuLink' data-bs-toggle='dropdown'>
            Welcome {$_SESSION['user']['name']} !
        </a>
        <ul class='dropdown-menu dropdown-menu-dark' aria-labelledby='navbarDarkDropdownMenuLink'>
            <li><a class='dropdown-item' href='#'>Profile</a></li>
            <li><a class='dropdown-item' href='#'>Settings</a></li>
            <li><a class='dropdown-item' href='?logout'>Logout</a></li>
        </ul>
        ";
    } else {
        $item['content'] = "
            <span class='d-flex'>
                <a class='nav-link' href='?login'>
                    Login
                </a>  
                <a class='nav-link' href='?register'>
                    Register
                </a>  
            </span>
        ";
    }

    if (isset($_SESSION['user']) && $_SESSION['user']['name'] !== '')
        $item['classBootstrap'] = 'dropdown';

    return $item;
}
?>