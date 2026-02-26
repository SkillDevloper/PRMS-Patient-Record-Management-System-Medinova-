<?php
$current_page = basename($_SERVER['PHP_SELF']);
$role = strtolower($_SESSION['role']);

// Home URL based on role
switch ($role) {
    case 'admin':
        $home_url = "index.php";
        break;
    case 'doctor':
        $home_url = "doctor_dashboard.php";
        break;
    case 'receptionist':
        $home_url = "staff_dashboard.php";
        break;
    default:
        $home_url = "#"; // Fallback if role is unknown
}
?>

<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!-- Sidebar Brand -->
    <?php if (in_array($role, ['admin', 'doctor', 'receptionist'])): ?>
        <div class="sidebar-brand">
            <a href="<?= $home_url ?>" class="brand-link">
                <img src="assets/img/logo.png" alt="AdminLTE Logo" class="brand-image" />
                <span class="brand-text fw-light text-uppercase">Medinova</span>
            </a>
        </div>
    <?php endif; ?>

    <!-- Sidebar Menu -->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">

                <!-- Common for all (or customize per role below) -->
                <?php if ($role == 'admin'): ?>
                    <li class="nav-item">
                        <a href="../admin/index.php" class="nav-link <?= ($current_page == 'index.php') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-home"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                <?php elseif ($role == 'doctor'): ?>
                    <li class="nav-item">
                        <a href="../admin/doctor_dashboard.php" class="nav-link <?= ($current_page == 'doctor_dashboard.php') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-home"></i>
                            <p>Doctor Dashboard</p>
                        </a>
                    </li>
                <?php elseif ($role == 'receptionist'): ?>
                    <li class="nav-item">
                        <a href="../admin/staff_dashboard.php" class="nav-link <?= ($current_page == 'staff_dashboard.php') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-home"></i>
                            <p>Receptionist Dashboard</p>
                        </a>
                    </li>
                <?php endif; ?>


                <!-- Patients List -->
                <?php if ($role == 'admin' || $role == 'receptionist'): ?>
                    <li class="nav-item">
                        <a href="../admin/patient_list_add.php" class="nav-link <?= ($current_page == 'patient_list_add.php') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-user-injured"></i>
                            <p>Add Patients</p>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if ($role == 'admin' || $role == 'doctor' || $role == 'receptionist'): ?>
                    <li class="nav-item">
                        <a href="../admin/total_patients_list.php" class="nav-link <?= ($current_page == 'total_patients_list.php') ? 'active' : '' ?>">
                            <i class="nav-icon fa-solid fa-list"></i>
                            <p>Total Patients List</p>
                        </a>
                    </li>
                <?php endif; ?>


                <!-- Medications -->
                <?php if ($role == 'admin' || $role == 'doctor'): ?>
                    <li class="nav-item">
                        <a href="../admin/medication.php" class="nav-link <?= ($current_page == 'medication.php') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-pills"></i>
                            <p>Medications</p>
                        </a>
                    </li>
                <?php endif; ?>


                <!-- Reports -->
                <?php if ($role == 'admin' || $role == 'doctor'): ?>
                    <li class="nav-item">
                        <a href="../admin/report.php" class="nav-link <?= ($current_page == 'report.php') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-file-medical"></i>
                            <p>Reports</p>
                        </a>
                    </li>
                <?php endif; ?>


                <!-- Doctors List (admin only) -->
                <?php if ($role == 'admin'): ?>
                    <li class="nav-item">
                        <a href="../admin/doctor_list.php" class="nav-link <?= ($current_page == 'doctor_list.php') ? 'active' : '' ?>">
                            <i class="nav-icon fa-solid fa-stethoscope"></i>
                            <p>Doctors</p>
                        </a>
                    </li>
                <?php endif; ?>


                <!-- Add User (admin only) -->
                <?php if ($role == 'admin'): ?>
                    <li class="nav-item">
                        <a href="../admin/add_users.php" class="nav-link <?= ($current_page == 'add_users.php') ? 'active' : '' ?>">
                            <i class="fa-solid fa-user-plus"></i>
                            <p>Add New User</p>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</aside>