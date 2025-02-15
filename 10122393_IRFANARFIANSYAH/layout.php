<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT MENCARI CINTA SEJATI </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar-nav .nav-link:hover {
            background-color: #f8f9fa;
            color: #0056b3;
        }
        .navbar-nav .nav-link.active {
            background-color: #0056b3;
            color: #ffffff;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">PT MENCARI CINTA SEJATI</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if ($_SESSION['role_id'] == 1): ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'super_admin_dashboard.php' ? 'active' : ''; ?>" href="super_admin_dashboard.php">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'manage_users.php' ? 'active' : ''; ?>" href="manage_users.php">Kelola Pengguna</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'manage_employees.php' ? 'active' : ''; ?>" href="manage_employees.php">Kelola Karyawan</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'manage_departments.php' ? 'active' : ''; ?>" href="manage_departments.php">Kelola Departemen</a>
                            </li>
                        <?php elseif ($_SESSION['role_id'] == 2): ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'director_dashboard.php' ? 'active' : ''; ?>" href="director_dashboard.php">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'manage_managers.php' ? 'active' : ''; ?>" href="manage_managers.php">Kelola Manajer</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'view_department_employees.php' ? 'active' : ''; ?>" href="view_department_employees.php">Lihat Karyawan</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'approve_employee_changes.php' ? 'active' : ''; ?>" href="approve_employee_changes.php">Setujui Perubahan</a>
                            </li>
                        <?php elseif ($_SESSION['role_id'] == 3): ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'manager_dashboard.php' ? 'active' : ''; ?>" href="manager_dashboard.php">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'manage_department_employees.php' ? 'active' : ''; ?>" href="manage_department_employees.php">Kelola Karyawan</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'approve_leave_requests.php' ? 'active' : ''; ?>" href="approve_leave_requests.php">Setujui Cuti</a>
                            </li>
                        <?php elseif ($_SESSION['role_id'] == 4): ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'employee_dashboard.php' ? 'active' : ''; ?>" href="employee_dashboard.php">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'view_profile.php' ? 'active' : ''; ?>" href="view_profile.php">Lihat Profil</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'leave_request.php' ? 'active' : ''; ?>" href="leave_request.php">Ajukan Cuti</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php echo $content; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>