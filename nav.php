<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <!-- Sidebar Toggle Button -->
        <button class="btn btn-light me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
            ☰
        </button>

        <a class="navbar-brand" href="dashboard.php">Admin Dashboard</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="records.php">Records</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-danger text-white" href="logout.php">Logout</a>
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
                <a class="nav-link text-white" href="dashboard.php"> 📊 Successful Migrants</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="records.php">📁 Top Clients</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="settings.php">⚙️ Settings</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="logout.php">🚪 Logout</a>
            </li>
        </ul>
    </div>
</div>
