<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <!-- Sidebar Toggle Button -->
        <button class="btn btn-light me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
        ☰</i>
        </button>

        <a class="navbar-brand" href="dashboard.php">Focus Website Admin Dashboard</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'dashboard2.php') ? 'active text-warning' : '' ?>" href="dashboard2.php">
                        <i class="fas fa-briefcase"></i> Employer
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'dashboard.php') ? 'active text-warning' : '' ?>" href="dashboard.php">
                        <i class="fas fa-users"></i> Worker
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-danger text-white" href="logout.php">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Sidebar Drawer -->
<div class="offcanvas offcanvas-start bg-dark text-white" tabindex="-1" id="sidebarMenu">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Admin Menu</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link  <?= ($current_page == 'dashboard.php') ? 'active text-warning' : 'text-white' ?> " href="dashboard.php">
                    <i class="fas fa-chart-line"></i> Workers/Migrants
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link  <?= ($current_page == 'dashboard2.php') ? 'active text-warning' : 'text-white' ?> " href="dashboard2.php">
                    <i class="fas fa-folder"></i> Employers
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="logout.php">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </div>
</div>

<!-- Font Awesome CDN -->
<script src="https://kit.fontawesome.com/your-kit-code.js" crossorigin="anonymous"></script>
