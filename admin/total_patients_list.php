<?php
// Page title for the Patients List page
$page_title = "Patients List";

// Include the configuration file for database connection
include('config.php');

// Include necessary layout files (header, navbar, sidebar)
include('Include/header.php');
include('Include/navbar.php');
include('Include/sidebar.php');

// Fetch Total Counts for the Dashboard
// Total number of patients in the system
$total_patients = $conn->query("SELECT COUNT(*) AS total FROM patients")->fetch_assoc()['total'];

// Total active patients (patients with 'active' status)
$total_active_cases = $conn->query("SELECT COUNT(*) AS total FROM patients WHERE status='active'")->fetch_assoc()['total'];

// Total inactive patients (patients with 'inactive' status)
$total_inactive_cases = $conn->query("SELECT COUNT(*) AS total FROM patients WHERE status='inactive'")->fetch_assoc()['total'];

// Total number of doctors in the system
$total_doctors = $conn->query("SELECT COUNT(*) AS total FROM doctors")->fetch_assoc()['total'];

// Total number of medications recorded in the system
$total_medications = $conn->query("SELECT COUNT(*) AS total FROM medications")->fetch_assoc()['total'];

?>
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<style>
    div#patientTable_length {
        margin-bottom: 15px !important;
    }
</style>
<main class="app-main">
    <!-- breadCrumb -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Total Patients List</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Total Patients List</li>
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
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                            <path d="M11.998 2.5A9.503 9.503 0 0 0 3.378 8H5.75a.75.75 0 0 1 0 1.5H2a1 1 0 0 1-1-1V4.75a.75.75 0 0 1 1.5 0v1.697A10.997 10.997 0 0 1 11.998 1C18.074 1 23 5.925 23 12s-4.926 11-11.002 11C6.014 23 1.146 18.223 1 12.275a.75.75 0 0 1 1.5-.037 9.5 9.5 0 0 0 9.498 9.262c5.248 0 9.502-4.253 9.502-9.5s-4.254-9.5-9.502-9.5Z"></path>
                            <path d="M12.5 7.25a.75.75 0 0 0-1.5 0v5.5c0 .27.144.518.378.651l3.5 2a.75.75 0 0 0 .744-1.302L12.5 12.315V7.25Z"></path>
                        </svg> Total Patient list</h3>
                </div>
                <div class="card-body">
                    <!-- Search Filter & Add Button -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <input type="text" id="searchPatient" class="form-control" placeholder="Search by Name...">
                        </div>
                    </div>
                    <!-- Patients Table -->
                    <table class="table table-hover table-bordered align-middle text-center" id="patientTable">
                        <!-- Table Header -->
                        <thead class="table-dark">
                            <tr>
                                <th>Patient Name</th>
                                <th>Age</th>
                                <th>Gender</th>
                                <th>Medical History</th>
                                <th>Address/City</th>
                                <th>Phone Number</th>
                                <th>Checkup By</th>
                                <th>Patient Status</th>
                                <th>Admitted On</th>
                                <th>Past Reports</th>
                                <!-- <th>Actions</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Query to fetch patient details and related information
                            $sql = "
            SELECT patients.id, patients.name, patients.age, patients.gender, 
                   patients.medical_history, location, contact_number, 
                   patients.status, patients.created_at, doctors.name AS doctor_name 
            FROM patients 
            LEFT JOIN medications ON medications.patient_id = patients.id 
            LEFT JOIN doctors ON medications.prescribed_by = doctors.id 
            ORDER BY patients.created_at DESC
        ";

                            // Execute the query and get the result
                            $patients = $conn->query($sql);

                            // Check if the query was successful
                            if (!$patients) {
                                die("SQL Error: " . $conn->error);
                            }

                            // If there are patients, display their details
                            if ($patients->num_rows > 0) {
                                while ($patient = $patients->fetch_assoc()):
                            ?>
                                    <tr>
                                        <!-- Display patient details in table rows -->
                                        <td><strong><?= htmlspecialchars($patient['name']) ?></strong></td>
                                        <td><?= htmlspecialchars($patient['age']) ?></td>
                                        <td><?= htmlspecialchars($patient['gender']) ?></td>
                                        <td><?= htmlspecialchars($patient['medical_history']) ?></td>
                                        <td><?= htmlspecialchars($patient['location']) ?></td>
                                        <td><span class="text-danger"><?= htmlspecialchars($patient['contact_number']) ?></span></td>
                                        <td class="<?= empty($patient['doctor_name']) || $patient['doctor_name'] == 'No Check Up' ? 'text-danger fw-bold' : '' ?>">
                                            <?= !empty($patient['doctor_name']) ? htmlspecialchars($patient['doctor_name']) : "No Check Up" ?>
                                        </td>
                                        <td>
                                            <!-- Badge for patient status (active/inactive) -->
                                            <span class="badge status-badge"><?= ucfirst(htmlspecialchars($patient['status'])) ?></span>
                                        </td>
                                        <td><span class="text-primary"><?= date("d M Y", strtotime($patient['created_at'])); ?></span></td>
                                        <!-- Display patient's past reports (image) -->
                                        <td>
                                            <img src="uploads/<?= !empty($patient['image']) ? htmlspecialchars($patient['image']) : 'default1.png' ?>"
                                                alt="Patient Image" style="width:100px; height:100px;">
                                        </td>
                                    </tr>
                            <?php
                                endwhile;
                            } else {
                                // If no patient records found
                                echo "<tr><td colspan='10' class='text-center'>No records found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Edit Patient Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Patient</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="row">
                        <div class="col-12 col-md-6 col-sm-12 col-lg-6">
                            <div class="mb-3">
                                <label>Name:</label>
                                <input type="text" class="form-control" name="name" id="edit_name" required>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-sm-12 col-lg-6">
                            <div class="mb-3">
                                <label>Age:</label>
                                <input type="number" class="form-control" name="age" id="edit_age" required>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-sm-12 col-lg-6">
                            <div class="mb-3">
                                <label>Gender:</label>
                                <select class="form-control" name="gender" id="edit_gender">
                                    <option value="" disabled>Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-sm-12 col-lg-6">
                            <div class="mb-3">
                                <label>Medical History:</label>
                                <textarea class="form-control" name="medical_history" id="edit_medical_history"></textarea>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-sm-12 col-lg-6 d-none">
                            <div class="mb-3">
                                <label>Assigned Doctor:</label>
                                <select class="form-control" name="assigned_doctor" id="edit_assigned_doctor">
                                    <option value="">Select Doctor</option>
                                    <?php
                                    $doctors = $conn->query("SELECT * FROM doctors");
                                    while ($doctor = $doctors->fetch_assoc()): ?>
                                        <option value="<?= $doctor['id'] ?>">
                                            <?= $doctor['id'] ?> - <?= $doctor['name'] ?> (<?= $doctor['specialty'] ?>)
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-sm-12 col-lg-6">
                            <div class="mb-3">
                                <label>Patient Status:</label>
                                <select class="form-control" name="status" id="edit_status">
                                    <option value="" disabled>Select Gender</option>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-sm-12 col-lg-6">
                            <div class="mb-3">
                                <label>Address/City:</label>
                                <input type="text" class="form-control" name="location" placeholder="Address">
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-sm-12 col-lg-6">
                            <div class="mb-3">
                                <label>Phone Number:</label>
                                <input type="number" class="form-control" name="contact_number" placeholder="03001234567">
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-sm-12 col-lg-6">
                            <div class="mb-3">
                                <label>Admitted On</label>
                                <input type="date" name="created_at" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="update_patient" class="btn btn-success">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="js/index.js"></script>
<?php include('Include/footer.php'); ?>

<!-- jQuery & DataTables JS -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- Initialize DataTable -->
<script>
    $(document).ready(function() {
        $('#patientTable').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": false, // disables search box
            "ordering": true,
            "info": true,
            "autoWidth": false
        });
    });
</script>