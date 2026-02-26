<?php
include('config.php'); // Database connection
$page_title = "Dashboard"; // Set page title

// Check if user is logged in as Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Admin') {
    // Redirect to login page if not logged in as Admin
    header("Location: login.php");
    exit(); // Ensure no further code is executed
}

// Fetch Total Counts from the database
$total_patients = $conn->query("SELECT COUNT(*) AS total FROM patients")->fetch_assoc()['total'];
$total_active_cases = $conn->query("SELECT COUNT(*) AS total FROM patients WHERE status='active'")->fetch_assoc()['total'];
$total_inactive_cases = $conn->query("SELECT COUNT(*) AS total FROM patients WHERE status='inactive'")->fetch_assoc()['total'];
$total_medications = $conn->query("SELECT COUNT(*) AS total FROM medications")->fetch_assoc()['total'];

// Include header, navbar, and sidebar
include('Include/header.php');
include('Include/navbar.php');
include('Include/sidebar.php');

// Show modal only once per login session
$show_terms_modal = false;
if (!isset($_SESSION['admin_terms_shown'])) {
    $_SESSION['admin_terms_shown'] = true; // Mark that terms modal has been shown
    $show_terms_modal = true; // Flag to display modal
}
?>

<!-- Terms & Conditions Modal for Admin -->
<div class="modal fade" id="adminTermsModal" tabindex="-1" aria-labelledby="adminTermsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="adminTermsLabel">Admin Terms & Conditions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Welcome Admin!</strong></p>
                <p>By accessing the Admin Dashboard, you agree to the following terms:</p>
                <ul>
                    <li>You are responsible for managing patient and medication data securely.</li>
                    <li>Ensure no unauthorized person gets access to this panel.</li>
                    <li>All actions are logged and monitored for compliance.</li>
                    <li>Misuse of the system can lead to disciplinary actions.</li>
                </ul>
                <p class="text-muted small">For more details, contact the system administrator.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">I Understand</button>
            </div>
        </div>
    </div>
</div>

<main class="app-main">
    <!-- breadCrumb -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Dashboard</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <!-- Total Patients -->
                <div class="col-6 col-md-3">
                    <div class="small-box text-bg-primary">
                        <div class="inner">
                            <h3><?= $total_patients; ?></h3>
                            <p>Total Patients</p>
                        </div>
                        <i class="fas fa-user-injured small-box-icon"></i>
                    </div>
                </div>

                <!-- Active Cases -->
                <div class="col-6 col-md-3">
                    <div class="small-box text-bg-success">
                        <div class="inner">
                            <h3><?= $total_active_cases; ?></h3>
                            <p>Active Cases</p>
                        </div>
                        <i class="fas fa-procedures small-box-icon"></i>
                    </div>
                </div>

                <!-- Inactive Case -->
                <div class="col-6 col-md-3">
                    <div class="small-box text-bg-danger">
                        <div class="inner">
                            <h3><?= $total_inactive_cases; ?></h3>
                            <p>Inactive Case</p>
                        </div>
                        <i class="fas fa-users small-box-icon"></i>
                    </div>
                </div>

                <!-- Medications Overview -->
                <div class="col-6 col-md-3">
                    <div class="small-box text-bg-warning">
                        <div class="inner">
                            <h3><?= $total_medications; ?></h3>
                            <p>Medications Overview</p>
                        </div>
                        <i class="fas fa-pills small-box-icon"></i>
                    </div>
                </div>
            </div>

            <!-- Recent Patients Table -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                            <path d="M11.998 2.5A9.503 9.503 0 0 0 3.378 8H5.75a.75.75 0 0 1 0 1.5H2a1 1 0 0 1-1-1V4.75a.75.75 0 0 1 1.5 0v1.697A10.997 10.997 0 0 1 11.998 1C18.074 1 23 5.925 23 12s-4.926 11-11.002 11C6.014 23 1.146 18.223 1 12.275a.75.75 0 0 1 1.5-.037 9.5 9.5 0 0 0 9.498 9.262c5.248 0 9.502-4.253 9.502-9.5s-4.254-9.5-9.502-9.5Z"></path>
                            <path d="M12.5 7.25a.75.75 0 0 0-1.5 0v5.5c0 .27.144.518.378.651l3.5 2a.75.75 0 0 0 .744-1.302L12.5 12.315V7.25Z"></path>
                        </svg> Recent Patients <span class="text-danger fw-bolder"><sup>(5)</sup></span></h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-default shadow btn-sm" data-lte-toggle="card-collapse">
                            <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                            <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                        </button>
                    </div>
                </div>
                <!-- Collapsible Content -->
                <div id="recentPatients" class="collapse show">
                    <div class="card-body">
                        <table class="table table-hover table-bordered align-middle text-center">
                            <thead class="table-dark">
                                <tr>
                                    <th>Name</th>
                                    <th>Age</th>
                                    <th>Gender</th>
                                    <th>Medical History</th>
                                    <th>Address/City</th>
                                    <th>Phone Number</th>
                                    <th>Checkup By</th>
                                    <th>Patient Status</th>
                                    <th>Admitted On</th>
                                    <th>Past Document</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // SQL query to fetch patient details along with doctor information
                                $sql = "
                SELECT patients.name, patients.age, patients.gender, patients.medical_history, location, contact_number, patients.status, patients.created_at, doctors.name AS doctor_name 
                FROM patients 
                LEFT JOIN medications ON medications.patient_id = patients.id 
                LEFT JOIN doctors ON medications.prescribed_by = doctors.id 
                ORDER BY patients.created_at DESC LIMIT 5
            ";
                                // Execute query
                                $patients = $conn->query($sql);

                                // Check if query failed
                                if (!$patients) {
                                    die("SQL Error: " . $conn->error);
                                }

                                // Check if there are any results
                                if ($patients->num_rows > 0) {
                                    while ($patient = $patients->fetch_assoc()):
                                ?>
                                        <tr>
                                            <td><?= $patient['name'] ?></td>
                                            <td><?= $patient['age'] ?></td>
                                            <td><?= $patient['gender'] ?></td>
                                            <td><?= $patient['medical_history'] ?></td>
                                            <td><?= $patient['location'] ?></td>
                                            <td><?= $patient['contact_number'] ?></td>
                                            <td class="<?= empty($patient['doctor_name']) || $patient['doctor_name'] == 'No Check Up' ? 'text-danger fw-bold' : '' ?>">
                                                <?= !empty($patient['doctor_name']) ? $patient['doctor_name'] : "No Check Up" ?>
                                            </td>
                                            <td>
                                                <span class="badge status-badge"><?= ucfirst($patient['status']) ?></span>
                                            </td>
                                            <td><?= date("d M Y", strtotime($patient['created_at'])); ?></td>
                                            <td>
                                                <!-- Displaying patient's past document or default image -->
                                                <img src="uploads/<?= !empty($patient['image']) ? $patient['image'] : 'default1.png' ?>" alt="Patient Image" style="width:100px; height:100px;">
                                            </td>
                                        </tr>
                                <?php
                                    endwhile;
                                } else {
                                    // Message when no records are found
                                    echo "<tr><td colspan='10' class='text-center'>No records found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Patient Growth Chart -->
            <div class="card mt-4 collapse show">
                <div class="card-header">
                    <h3 class="card-title"><i class="fa-solid fa-chart-simple"></i> Patients Demography</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-default shadow btn-sm" data-lte-toggle="card-collapse">
                            <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                            <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Stacked Bar/Line Chart -->
                        <div class="col-4 col-md-4 col-sm-12 col-lg-4">
                            <canvas id="stackedChart"></canvas>
                        </div>

                        <!-- Floating Bar Chart -->
                        <div class="col-4 col-md-4 col-sm-12 col-lg-4">
                            <canvas id="floatingBarChart"></canvas>
                        </div>

                        <!-- Line Chart (drawTime) -->
                        <div class="col-4 col-md-4 col-sm-12 col-lg-4">
                            <canvas id="lineChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Chart.js Graph Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- PHP code for chart js -->
<?php
// Total patients
$total_patients = $conn->query("SELECT COUNT(*) AS total FROM patients")->fetch_assoc()['total'];

// Active & Inactive patients
$active_patients = $conn->query("SELECT COUNT(*) AS total FROM patients WHERE status='Active'")->fetch_assoc()['total'];
$inactive_patients = $conn->query("SELECT COUNT(*) AS total FROM patients WHERE status='Inactive'")->fetch_assoc()['total'];

// Medications
$total_medications = $conn->query("SELECT COUNT(*) AS total FROM medications")->fetch_assoc()['total'];
$active_medications = $conn->query("SELECT COUNT(*) AS total FROM medications WHERE status='Active'")->fetch_assoc()['total'];
$inactive_medications = $conn->query("SELECT COUNT(*) AS total FROM medications WHERE status='Inactive'")->fetch_assoc()['total'];
?>

<script src="js/charts.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Data from PHP
        var totalPatients = <?= $total_patients; ?>;
        var activePatients = <?= $active_patients; ?>;
        var inactivePatients = <?= $inactive_patients; ?>;

        var totalMedications = <?= $total_medications; ?>;
        var activeMedications = <?= $active_medications; ?>;
        var inactiveMedications = <?= $inactive_medications; ?>;

        // Stacked Bar/Line Chart
        new Chart(document.getElementById("stackedChart"), {
            type: "bar",
            data: {
                labels: ["Total", "Active", "Inactive"],
                datasets: [{
                        label: "Patients",
                        data: [totalPatients, activePatients, inactivePatients],
                        backgroundColor: ["#007bff", "#28a745", "#dc3545"]
                    },
                    {
                        label: "Medications",
                        data: [totalMedications, activeMedications, inactiveMedications],
                        backgroundColor: ["#6f42c1", "#ffc107", "#17a2b8"]
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        stacked: true
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: "📊 Patients & Medications Overview",
                        font: {
                            size: 18
                        },
                        color: "#333"
                    }
                }
            }
        });

        // Floating Bar Chart
        new Chart(document.getElementById("floatingBarChart"), {
            type: "bar",
            data: {
                labels: ["Total Patients", "Total Medications"],
                datasets: [{
                    label: "Count",
                    data: [totalPatients, totalMedications],
                    backgroundColor: ["#ff5733", "#33ff57"]
                }]
            },
            options: {
                responsive: true,
                indexAxis: "y",
                plugins: {
                    title: {
                        display: true,
                        text: "📊 Total Patients vs Medications",
                        font: {
                            size: 18
                        },
                        color: "#333"
                    }
                }
            }
        });

        // 📊 Line Chart with drawTime
        new Chart(document.getElementById("lineChart"), {
            type: "line",
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
                datasets: [{
                    label: "New Patients",
                    data: [10, 25, 18, 30, 40, 35], // Sample data
                    backgroundColor: "rgba(54, 162, 235, 0.2)",
                    borderColor: "rgba(54, 162, 235, 1)",
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                animation: {
                    duration: 1000,
                    easing: "easeInOutQuart"
                },
                plugins: {
                    title: {
                        display: true,
                        text: "📈 New Patients Growth Over Time",
                        font: {
                            size: 18
                        },
                        color: "#333"
                    }
                }
            }
        });
    });

    // Active Or Inactive Badge Color
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".status-badge").forEach(function(badge) {
            if (badge.textContent.trim() === "Active") {
                badge.classList.add("bg-success");
            } else {
                badge.classList.add("bg-danger");
            }
        });
    });

    // Modal for admin
    document.addEventListener('DOMContentLoaded', function() {
        <?php if ($show_terms_modal): ?>
            var myModal = new bootstrap.Modal(document.getElementById('adminTermsModal'));
            myModal.show();
        <?php endif; ?>
    });
</script>

<?php include('Include/footer.php'); ?>