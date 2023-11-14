<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="https://ridmyinventory.com"><img src="./assets/img/navbar-logo.png" style="max-width:80px;margin-left:12px;" /></a>

        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent"
                aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle"
                       href="#"
                       id="navbarDropdown"
                       role="button"
                       data-bs-toggle="dropdown"
                       aria-haspopup="true"
                       aria-expanded="false">
                        <?= trans('welcome'); ?>, <?= e($currentUser->username);  ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="profile.php">
                            <i class="fa fa-user text-muted me-2" ></i>
                            <?= trans('my_profile') ?>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="logout.php">
                            <i class="fa fa-sign-out-alt me-2 text-muted"></i>
                            <?= trans('logout') ?>
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>