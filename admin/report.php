<?php
$page_title = "Patients Reports";
include 'config.php';
include('Include/header.php');
include('Include/navbar.php');
include('Include/sidebar.php');

//// ---- TOTAL COUNTS ---- ////
$total_doctors = $conn->query("SELECT COUNT(*) AS total FROM doctors")->fetch_assoc()['total'];
$total_doctors_active = $conn->query("SELECT COUNT(*) AS total FROM doctors WHERE status='active'")->fetch_assoc()['total'];
$total_patients = $conn->query("SELECT COUNT(*) AS total FROM patients")->fetch_assoc()['total'];
$total_active_cases = $conn->query("SELECT COUNT(*) AS total FROM patients WHERE status='active'")->fetch_assoc()['total'];

//// ---- GET FILTER PARAMETERS ---- ////
$startDate  = $_GET['startDate'] ?? '';
$endDate    = $_GET['endDate'] ?? '';
$doctorId   = $_GET['doctorId'] ?? '';
$searchText = $_GET['searchText'] ?? '';

//// ---- REPORT DATA QUERY ---- ////
$query = "
    SELECT 
        patients.name AS patient_name, 
        doctors.name AS doctor_name, 
        medications.medication_name, 
        medications.status, 
        medications.created_at 
    FROM medications 
    JOIN patients ON medications.patient_id = patients.id 
    JOIN doctors ON medications.prescribed_by = doctors.id 
    WHERE 1=1
";

// ---- APPLY FILTERS ---- //
if (!empty($startDate)) {
    $query .= " AND medications.created_at >= '" . $conn->real_escape_string($startDate) . "'";
}
if (!empty($endDate)) {
    $query .= " AND medications.created_at <= '" . $conn->real_escape_string($endDate) . "'";
}
if (!empty($doctorId)) {
    $query .= " AND doctors.id = '" . $conn->real_escape_string($doctorId) . "'";
}
if (!empty($searchText)) {
    $searchEscaped = $conn->real_escape_string($searchText);
    $query .= " AND (patients.name LIKE '%$searchEscaped%' OR medications.medication_name LIKE '%$searchEscaped%')";
}

$query .= " ORDER BY medications.created_at DESC"; // Latest First

$result = $conn->query($query);

//// ---- CHART DATA ---- ////

// Patients by City
$city_data = $conn->query("SELECT location, COUNT(*) AS total FROM patients GROUP BY location");
$cities = [];
$city_counts = [];

while ($row = $city_data->fetch_assoc()) {
    $cities[] = $row['location'];
    $city_counts[] = $row['total'];
}

// Monthly Patient Growth
$growth_data = $conn->query("
    SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, COUNT(*) AS total 
    FROM patients 
    GROUP BY month 
    ORDER BY month ASC
");
$months = [];
$patient_counts = [];

while ($row = $growth_data->fetch_assoc()) {
    $months[] = $row['month'];
    $patient_counts[] = $row['total'];
}

// Patients by Status
$status_data = $conn->query("SELECT status, COUNT(*) AS total FROM patients GROUP BY status");
$statuses = [];
$status_counts = [];

while ($row = $status_data->fetch_assoc()) {
    $statuses[] = $row['status'];
    $status_counts[] = $row['total'];
}

// Re-fetch Total Counts (optional: you can reuse earlier vars if no change)
$total_doctors = $conn->query("SELECT COUNT(*) AS total FROM doctors")->fetch_assoc()['total'];
$total_patients = $conn->query("SELECT COUNT(*) AS total FROM patients")->fetch_assoc()['total'];
?>
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<style>
    div#patientTable_length {
        margin-bottom: 15px !important;
    }
</style>

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Reports</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Reports</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <div class="small-box text-bg-primary">
                        <div class="inner">
                            <h3><?= $total_doctors; ?></h3>
                            <p>Total Doctors</p>
                        </div>
                        <i class="fas fa-user-md small-box-icon"></i>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="small-box text-bg-success">
                        <div class="inner">
                            <h3><?= $total_doctors_active; ?></h3>
                            <p>Active Doctors</p>
                        </div>
                        <i class="fas fa-user-md small-box-icon"></i>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="small-box text-bg-danger">
                        <div class="inner">
                            <h3><?= $total_patients; ?></h3>
                            <p>Total Patients</p>
                        </div>
                        <i class="fas fa-users small-box-icon"></i>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="small-box text-bg-warning">
                        <div class="inner">
                            <h3><?= $total_active_cases; ?></h3>
                            <p>Active Patients</p>
                        </div>
                        <i class="fas fa-user-check small-box-icon"></i>
                    </div>
                </div>
            </div>

            <!-- Filters Form -->
            <form method="GET">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Filters & Search</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Date Range:</label>
                                <input type="date" name="startDate" class="form-control" value="<?= $startDate ?>">
                                <input type="date" name="endDate" class="form-control mt-2" value="<?= $endDate ?>">
                            </div>

                            <div class="col-md-3">
                                <label>Doctor:</label>
                                <select name="doctorId" class="form-control">
                                    <option value="">All Doctors</option>
                                    <?php
                                    $doctors = $conn->query("SELECT id, name FROM doctors");
                                    while ($doc = $doctors->fetch_assoc()) {
                                        $selected = ($doctorId == $doc['id']) ? 'selected' : '';
                                        echo "<option value='{$doc['id']}' $selected>{$doc['name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label>Search:</label>
                                <input type="text" name="searchText" class="form-control" placeholder="Search by Name..." value="<?= $searchText ?>">
                            </div>

                            <div class="col-md-3 mt-4">
                                <button type="submit" class="btn btn-primary">Apply Filters</button>
                                <a href="report.php" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Reports Table -->
            <div class="card my-4">
                <div class="card-header">
                    <h3 class="card-title">Reports Data</h3>
                </div>
                <div class="card-body">
                    <table id="reportsTable" class="table table-hover table-bordered align-middle text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>Patient Name</th>
                                <th>Doctor</th>
                                <th>Medication</th>
                                <th>Medication Status</th>
                                <th>Created Date</th>
                                <th>Past Document</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0) : ?>
                                <?php while ($row = $result->fetch_assoc()) : ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($row['patient_name']); ?></strong></td>
                                        <td><?= htmlspecialchars($row['doctor_name']); ?></td>
                                        <td><span class="text-primary"><?= htmlspecialchars($row['medication_name']); ?></span></td>
                                        <td><span class="badge status-badge"><?= htmlspecialchars($row['status']); ?></span></td>
                                        <td><span class="text-danger"><?= date("d M Y", strtotime($row['created_at'])); ?></span></td>
                                        <td>
                                            <img src="uploads/<?= !empty($row['image']) ? $row['image'] : 'default1.png' ?>"
                                                alt="Patient Image"
                                                style="width:100px; height:100px; object-fit:cover;">
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="6" class="text-center">No records found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="card-header ps-0 mb-3">
                        <h3 class="card-title">Demography</h3>
                    </div>
                    <!-- Charts -->
                    <div class="row">
                        <!-- Patients by City -->
                        <div class="col-col md-12 w-50">
                            <div class="card">
                                <div class="card-header">Patients by City</div>
                                <div class="card-body">
                                    <canvas id="patientsByCity"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Patients by Status -->
                        <div class=" mt-4 col col-md-6 w-25">
                            <div class="card">
                                <div class="card-header">Patients by Status</div>
                                <div class="card-body">
                                    <canvas id="patientsByStatus"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Doctors vs. Patients -->
                        <div class=" mt-4 col col-md-6  w-25">
                            <div class="card">
                                <div class="card-header">Doctors vs. Patients</div>
                                <div class="card-body">
                                    <canvas id="doctorsVsPatients"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include('Include/footer.php'); ?>
<!-- Charts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        // Patients by City (Bar Chart)
        new Chart(document.getElementById("patientsByCity"), {
            type: "bar",
            data: {
                labels: <?= json_encode($cities); ?>,
                datasets: [{
                    label: "Patients",
                    data: <?= json_encode($city_counts); ?>,
                    backgroundColor: "rgba(75, 192, 192, 0.5)"
                }]
            }
        });

        // Patients by Status (Pie Chart)
        new Chart(document.getElementById("patientsByStatus"), {
            type: "pie",
            data: {
                labels: <?= json_encode($statuses); ?>,
                datasets: [{
                    data: <?= json_encode($status_counts); ?>,
                    backgroundColor: ["#28a745", "#dc3545", "#ffc107"]
                }]
            }
        });

        // Doctors vs. Patients (Doughnut Chart)
        new Chart(document.getElementById("doctorsVsPatients"), {
            type: "doughnut",
            data: {
                labels: ["Doctors", "Patients"],
                datasets: [{
                    data: [<?= $total_doctors; ?>, <?= $total_patients; ?>],
                    backgroundColor: ["#007bff", "#ff6384"]
                }]
            }
        });

    });
    // Active Or Inactive Badge Color
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".status-badge").forEach(function(badge) {
            if (badge.textContent.trim() === "active") {
                badge.classList.add("bg-success");
            } else {
                badge.classList.add("bg-danger");
            }
        });
    });
</script>
<!-- jQuery & DataTables JS -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- Initialize DataTable -->
<script>
    $(document).ready(function() {
        $('#reportsTable').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": false, // disables search box
            "ordering": true,
            "info": true,
            "autoWidth": false
        });
    });
</script>