<?php
include('config.php'); // Database connection
include('Include/header.php'); // Include header file
include('Include/navbar.php'); // Include navigation bar
include('Include/sidebar.php'); // Include sidebar for doctor dashboard

// Ensure that the user is logged in and is a doctor
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Doctor') {
    // Redirect to login page if not logged in as doctor
    header("Location: login.php");
    exit();
}

$doctor_id = $_SESSION['user_id'];
$query = "SELECT * FROM doctors WHERE name = name";
$result = $conn->query($query);
?>

<main class="app-main">
    <div class="app-content">
        <div class="container-fluid">
            <!-- breadCrumb -->
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">Doctor Dashboard</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                                <li class="breadcrumb-item active">Doctor Dashboard</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Total Doctors</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Patient Name</th>
                            <th>Condition</th>
                            <th>Last Visit</th>
                        </tr>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td><?= $row['name']; ?></td>
                                <!-- <td><?= $row['status']; ?></td> -->
                                <td>
                                    <span class="badge status-badge"><?= ucfirst($row['status']) ?></span>
                                </td>
                                <td><?= date("d M Y", strtotime($row['created_at'])); ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".status-badge").forEach(function(badge) {
            if (badge.textContent.trim() === "Active") {
                badge.classList.add("bg-success");
            } else {
                badge.classList.add("bg-danger");
            }
        });
    });
</script>
<?php include('Include/footer.php'); ?>