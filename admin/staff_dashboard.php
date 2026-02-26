<?php
// Include configuration file to connect to the database
include('config.php');

// Check if the user is logged in and has the role 'Receptionist'
// If not, redirect to the login page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Receptionist') {
    header("Location: login.php");
    exit();
}

// Fetch list of doctors from the database
// This list will be used to assign a doctor to the patient
$doctors = $conn->query("SELECT id, name FROM users WHERE role='doctor'");

// Check if the form has been submitted via POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $medical_history = $_POST['medical_history'];
    $assigned_doctor = $_POST['assigned_doctor']; // Doctor ID from form
    $status = $_POST['status']; // Status of the patient (active/inactive)

    // Generate an auto-increment patient ID based on the last patient ID in the database
    $last_id_query = $conn->query("SELECT id FROM patients ORDER BY id DESC LIMIT 1");
    $last_id = ($last_id_query->num_rows > 0) ? (int) $last_id_query->fetch_assoc()['id'] + 1 : 1;
    $patient_id = "P-" . str_pad($last_id, 4, "0", STR_PAD_LEFT); // Format the patient ID as P-XXXX

    // SQL query to insert the new patient record into the database
    $sql = "INSERT INTO patients (name, age, gender, medical_history, assigned_doctor, created_at, status) 
            VALUES ('$name', '$age', '$gender', '$medical_history', '$assigned_doctor', NOW(), '$status')";

    // Execute the query and check if the insertion is successful
    if ($conn->query($sql)) {
        // Redirect to the add_patient page with a success message
        header("Location: add_patient.php?success=Patient added successfully");
        exit();
    } else {
        // If there is an error, display the error message
        die("Error inserting patient: " . $conn->error);
    }
}

// Include the header, navbar, and sidebar for the layout
include('Include/header.php');
include('Include/navbar.php');
include('Include/sidebar.php');

// Fetch total patient counts for the dashboard
$total_patients = $conn->query("SELECT COUNT(*) AS total FROM patients")->fetch_assoc()['total'];
$total_active_cases = $conn->query("SELECT COUNT(*) AS total FROM patients WHERE status='active'")->fetch_assoc()['total'];
$total_inactive_cases = $conn->query("SELECT COUNT(*) AS total FROM patients WHERE status='inactive'")->fetch_assoc()['total'];
$total_medications = $conn->query("SELECT COUNT(*) AS total FROM medications")->fetch_assoc()['total'];
?>

<main class="app-main">
    <div class="container-fluid">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">
                            Receptionist </h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item active">
                                Dashboard</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

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

        <h3 class="mb-3">Recent Patients</h3>

        <!-- Recent Patients Table -->
        <table class="table table-hover table-bordered align-middle text-center">
            <thead class="table-danger">
                <tr>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Status</th>
                    <th>Assigned Doctor</th>
                    <th>Admitted On</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $patients = $conn->query(" SELECT patients.name, patients.age, patients.gender, patients.medical_history, patients.status, patients.created_at, doctors.name AS doctor_name 
                                    FROM patients 
                                    LEFT JOIN medications ON medications.patient_id = patients.id 
                                    LEFT JOIN doctors ON medications.prescribed_by = doctors.id 
                                    ORDER BY patients.created_at DESC");
                while ($patient = $patients->fetch_assoc()):
                ?>
                    <tr>
                        <td><strong><?= $patient['name'] ?></strong></td>
                        <td><?= $patient['age'] ?></td>
                        <td><?= $patient['gender'] ?></td>
                        <td>
                            <span class="badge status-badge">
                                <?= ucfirst($patient['status']) ?>
                            </span>
                        </td>
                        <td><?= $patient['doctor_name'] ? $patient['doctor_name'] : "Not Assigned" ?></td>
                        <td><span class="text-primary"><?= date("d M Y", strtotime($patient['created_at'])); ?></span></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>

<script src="js/index.js"></script>
<?php include('Include/footer.php'); ?>

<script>
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
</script>