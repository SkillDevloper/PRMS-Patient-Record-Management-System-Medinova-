<?php
$page_title = "Patients Medications";
include 'config.php';
include('Include/header.php');
include('Include/navbar.php');
include('Include/sidebar.php');

// Fetch Patients List (Patients with no medications)
$patients = $conn->query("
    SELECT id, name 
    FROM patients 
    WHERE id NOT IN (SELECT DISTINCT patient_id FROM medications) 
    ORDER BY name ASC
");

// Fetch Doctors List
$doctors = $conn->query("SELECT id, name FROM doctors ORDER BY name ASC");

// Fetch Total Counts for patients, doctors, and medications
$total_patients = $conn->query("SELECT COUNT(*) AS total FROM patients")->fetch_assoc()['total'];
$total_active_cases = $conn->query("SELECT COUNT(*) AS total FROM patients WHERE status='active'")->fetch_assoc()['total'];
$total_inactive_cases = $conn->query("SELECT COUNT(*) AS total FROM patients WHERE status='inactive'")->fetch_assoc()['total'];
$total_doctors = $conn->query("SELECT COUNT(*) AS total FROM doctors")->fetch_assoc()['total'];
$total_medications = $conn->query("SELECT COUNT(*) AS total FROM medications")->fetch_assoc()['total'];

// Fetch Medications with Patient & Doctor Names
$medications = $conn->query("
    SELECT medications.*, 
           patients.name AS patient_name, 
           doctors.name AS doctor_name 
    FROM medications 
    JOIN patients ON medications.patient_id = patients.id
    JOIN doctors ON medications.prescribed_by = doctors.id
    ORDER BY medications.created_at DESC
");

// Add Medication logic
if (isset($_POST['add_medication'])) {
    $patient_id = $conn->real_escape_string($_POST['patient_id']);
    $doctor_id = $conn->real_escape_string($_POST['prescribed_by']);
    $medication_name = $conn->real_escape_string($_POST['medication_name']);
    $dosage = $conn->real_escape_string($_POST['dosage']);
    $frequency = $conn->real_escape_string($_POST['frequency']);
    $start_date = !empty($_POST['start_date']) ? $_POST['start_date'] : NULL; // NULL handling
    $end_date = !empty($_POST['end_date']) ? $_POST['end_date'] : NULL;
    $status = isset($_POST['status']) ? strtolower($_POST['status']) : 'active'; // Ensure lowercase

    // Insert Medication Query
    $query = "INSERT INTO medications 
              (patient_id, medication_name, dosage, frequency, prescribed_by, start_date, end_date, status, created_at) 
              VALUES ('$patient_id', '$medication_name', '$dosage', '$frequency', '$doctor_id', 
                      " . ($start_date ? "'$start_date'" : "NULL") . ", 
                      " . ($end_date ? "'$end_date'" : "NULL") . ", 
                      '$status', NOW())";

    if ($conn->query($query) === TRUE) {
        echo "<script>alert('Medication Added Successfully!'); window.location.href='medication.php';</script>";
    } else {
        echo "Error: " . $conn->error; // Debugging for errors
    }
}

// Update Medication logic
if (isset($_POST['update_medication'])) {
    $id = $conn->real_escape_string($_POST['medication_id']);
    $medication_name = $conn->real_escape_string($_POST['medication_name']);
    $dosage = $conn->real_escape_string($_POST['dosage']);
    $frequency = $conn->real_escape_string($_POST['frequency']);
    $start_date = !empty($_POST['start_date']) ? $_POST['start_date'] : NULL;
    $end_date = !empty($_POST['end_date']) ? $_POST['end_date'] : NULL;
    $status = isset($_POST['status']) ? strtolower($_POST['status']) : 'active';

    // Update Medication Query
    $query = "UPDATE medications 
              SET medication_name='$medication_name', 
                  dosage='$dosage', 
                  frequency='$frequency', 
                  start_date=" . ($start_date ? "'$start_date'" : "NULL") . ", 
                  end_date=" . ($end_date ? "'$end_date'" : "NULL") . ", 
                  status='$status' 
              WHERE id='$id'";

    if ($conn->query($query) === TRUE) {
        echo "<script>alert('Medication Updated Successfully!'); window.location.href='medication.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Delete Medication logic
if (isset($_GET['delete_id'])) {
    $delete_id = $conn->real_escape_string($_GET['delete_id']);
    $conn->query("DELETE FROM medications WHERE id='$delete_id'");
    echo "<script>alert('Medication Deleted Successfully!'); window.location.href='medication.php';</script>";
}

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
                    <h3 class="mb-0">Medications Overview</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Medications</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <!-- Active Cases -->
                <div class="col-12 col-md col-sm-6">
                    <div class="small-box text-bg-primary">
                        <div class="inner">
                            <h3><?= $total_active_cases; ?></h3>
                            <p>Active Cases</p>
                        </div>
                        <i class="fas fa-procedures small-box-icon"></i>
                    </div>
                </div>

                <!-- Inactive Case -->
                <div class="col-12 col-md col-sm-6">
                    <div class="small-box text-bg-danger">
                        <div class="inner">
                            <h3><?= $total_inactive_cases; ?></h3>
                            <p>Inactive Case</p>
                        </div>
                        <i class="fas fa-users small-box-icon"></i>
                    </div>
                </div>

                <!-- Total Doctors -->
                <div class="col-12 col-md col-sm-6">
                    <div class="small-box text-bg-success">
                        <div class="inner">
                            <h3><?= $total_doctors; ?></h3>
                            <p>Doctors Available</p>
                        </div>
                        <i class="fa-solid fa-stethoscope small-box-icon"></i>
                    </div>
                </div>

                <!-- Medications Overview -->
                <div class="col-12 col-md col-sm-6">
                    <div class="small-box text-bg-warning">
                        <div class="inner">
                            <h3><?= $total_medications; ?></h3>
                            <p>Medications</p>
                        </div>
                        <i class="fas fa-pills small-box-icon"></i>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Medication List</h3>
                </div>
                <div class="card-body">
                    <!-- Search  Filter & Add Button -->
                    <div class="row mb-0">
                        <div class="col-md-9">
                            <input type="text" id="searchPatient" class="form-control" placeholder="Search by Name...">
                        </div>
                        <div class="col-md-3 text-end">
                            <!-- Add Medication Button -->
                            <button class="btn btn-danger shadow mb-3" data-bs-toggle="modal" data-bs-target="#addMedicationModal"><i class="fa-solid fa-square-plus"></i> Add New Medication</button>
                        </div>
                    </div>
                    <!-- Patients Table -->
                    <table class="table table-hover table-bordered align-middle text-center" id="patientTable">
                        <thead class="table-dark">
                            <tr>
                                <th>Patient</th>
                                <th>Medication</th>
                                <th>Dosage (mg)</th>
                                <th>Frequency</th>
                                <th>Doctor</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Status</th>
                                <th>Document</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($medications->num_rows > 0): ?>
                                <?php while ($med = $medications->fetch_assoc()): ?>
                                    <tr>
                                        <td><strong><?= ucfirst($med['patient_name']) ?></strong></td>
                                        <td><?= ucfirst($med['medication_name']) ?></td>
                                        <td><span class="badge bg-info"><?= $med['dosage'] ?></span></td>
                                        <td><?= $med['frequency'] ?></td>
                                        <td><?= ucfirst($med['doctor_name']) ?></td>
                                        <td><span class="text-primary"><?= date("d M Y", strtotime($med['start_date'])) ?></span></td>
                                        <td><span class="text-danger"><?= date("d M Y", strtotime($med['end_date'])) ?></span></td>
                                        <td>
                                            <span class="badge bg-<?= strtolower($med['status']) === 'active' ? 'success' : 'danger' ?>">
                                                <?= ucfirst($med['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php
                                            $patient_img = !empty($patient['image']) ? $patient['image'] : 'default1.png';
                                            ?>
                                            <img src="uploads/<?= $patient_img ?>" class="img-thumbnail rounded" style="width: 80px; height: 80px; object-fit: cover;" alt="Patient Image">
                                        </td>
                                        <td>
                                            <!-- Edit Button -->
                                            <button class="btn btn-outline-warning btn-sm edit-btn mx-2"
                                                data-id="<?= $med['id'] ?>"
                                                data-patient_id="<?= $med['patient_id'] ?>"
                                                data-medication_name="<?= $med['medication_name'] ?>"
                                                data-dosage="<?= $med['dosage'] ?>"
                                                data-frequency="<?= $med['frequency'] ?>"
                                                data-prescribed_by="<?= $med['prescribed_by'] ?>"
                                                data-start_date="<?= $med['start_date'] ?>"
                                                data-end_date="<?= $med['end_date'] ?>"
                                                data-status="<?= $med['status'] ?>"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editMedicationModal">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>

                                            <!-- Delete Button -->
                                            <?php if ($role == 'admin'): ?>
                                                <a href="medication.php?delete_id=<?= $med['id'] ?>"
                                                    class="btn btn-outline-danger btn-sm"
                                                    onclick="return confirm('Are you sure?')">
                                                    <i class="fa-solid fa-trash"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="10" class="text-center text-muted">No records found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Medication Modal -->
    <div class="modal fade" id="addMedicationModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Medication</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label>Patient Name</label>
                                    <select name="patient_id" class="form-control" required>
                                        <option value="" disabled selected>-- Select Patient --</option>
                                        <?php while ($patient = $patients->fetch_assoc()): ?>
                                            <option value="<?= $patient['id'] ?>"><?= $patient['name'] ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label>Medision Name</label>
                                    <input type="text" name="medication_name" class="form-control" placeholder="Medi Name" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label>Dosage (mg)</label>
                                    <input type="text" name="dosage" class="form-control" placeholder="10mg" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label>Frequency</label>
                                    <input type="text" name="frequency" class="form-control" placeholder="Once a day" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label>Prescribed By (Doctor)</label>
                                    <select name="prescribed_by" class="form-control" required>
                                        <option value="" disabled selected>-- Select Doctor --</option>
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
                            <div class="col-6">
                                <div class="mb-3">
                                    <label>Medision Status</label>
                                    <select class="form-control" name="status" id="edit_status" required>
                                        <option value="" disabled selected>-- Select Status --</option>
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label>Start Date</label>
                                    <input type="date" name="start_date" class="form-control">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label>End Date</label>
                                    <input type="date" name="end_date" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="add_medication" class="btn btn-primary">Add Medication</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Medication Modal -->
    <div class="modal fade" id="editMedicationModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Medication</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="medication_id" id="edit_medication_id">

                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label>Patient Name</label>
                                    <select name="patient_id" id="edit_patient_id" class="form-control" required>
                                        <option value="" disabled>-- Select Patient --</option>
                                        <?php
                                        $patientList = $conn->query("SELECT id, name FROM patients ORDER BY name ASC");
                                        while ($pat = $patientList->fetch_assoc()): ?>
                                            <option value="<?= $pat['id'] ?>"><?= $pat['name'] ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="mb-3">
                                    <label>Medication Name</label>
                                    <input type="text" name="medication_name" id="edit_medication_name" class="form-control" required>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="mb-3">
                                    <label>Dosage</label>
                                    <input type="text" name="dosage" id="edit_dosage" class="form-control" required>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="mb-3">
                                    <label>Frequency</label>
                                    <input type="text" name="frequency" id="edit_frequency" class="form-control" required>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="mb-3">
                                    <label>Prescribed By (Doctor)</label>
                                    <select name="prescribed_by" id="edit_prescribed_by" class="form-control" required>
                                        <option value="" disabled>-- Select Doctor --</option>
                                        <?php
                                        $doctorList = $conn->query("SELECT id, name FROM doctors ORDER BY name ASC");
                                        while ($doc = $doctorList->fetch_assoc()): ?>
                                            <option value="<?= $doc['id'] ?>"><?= $doc['name'] ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="mb-3">
                                    <label>Status</label>
                                    <select class="form-control" name="status" required>
                                        <option value="" disabled selected>Select Status</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="mb-3">
                                    <label>Start Date</label>
                                    <input type="date" name="start_date" id="edit_start_date" class="form-control">
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="mb-3">
                                    <label>End Date</label>
                                    <input type="date" name="end_date" id="edit_end_date" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="update_medication" class="btn btn-success">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
<!-- Search -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let searchInput = document.getElementById("searchPatient");

        searchInput.addEventListener("keyup", function() {
            let value = searchInput.value.toLowerCase();
            let rows = document.querySelectorAll("#patientTable tbody tr");

            rows.forEach((row) => {
                let nameCell = row.querySelector("td:first-child"); // First column (Name)
                if (nameCell) {
                    let name = nameCell.textContent.toLowerCase();
                    row.style.display = name.includes(value) ? "" : "none";
                }
            });
        });
    });
</script>

<?php include('include/footer.php'); ?>

<!-- Active Or Inactive Badge Color -->
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
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".edit-btn").forEach(button => {
            button.addEventListener("click", function() {
                document.getElementById("edit_medication_id").value = this.dataset.id;
                document.getElementById("edit_patient_id").value = this.dataset.patient_id;
                document.getElementById("edit_medication_name").value = this.dataset.medication_name;
                document.getElementById("edit_dosage").value = this.dataset.dosage;
                document.getElementById("edit_frequency").value = this.dataset.frequency;
                document.getElementById("edit_prescribed_by").value = this.dataset.prescribed_by;
                document.getElementById("edit_start_date").value = this.dataset.start_date;
                document.getElementById("edit_end_date").value = this.dataset.end_date;
                document.getElementById("edit_status").value = this.dataset.status;
            });
        });
    });
</script>
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