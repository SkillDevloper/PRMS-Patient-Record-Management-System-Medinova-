<?php
$page_title = "Add New Patient";

// Include required files
include('config.php');
include('Include/header.php');
include('Include/navbar.php');
include('Include/sidebar.php');

// ============================================
// Fetch Summary Statistics
// ============================================

$total_patients = $conn->query("SELECT COUNT(*) AS total FROM patients")->fetch_assoc()['total'];
$total_active_cases = $conn->query("SELECT COUNT(*) AS total FROM patients WHERE status='active'")->fetch_assoc()['total'];
$total_inactive_cases = $conn->query("SELECT COUNT(*) AS total FROM patients WHERE status='inactive'")->fetch_assoc()['total'];
$total_doctors = $conn->query("SELECT COUNT(*) AS total FROM doctors")->fetch_assoc()['total'];
$total_medications = $conn->query("SELECT COUNT(*) AS total FROM medications")->fetch_assoc()['total'];

// ============================================
// Update Existing Patient Record
// ============================================

if (isset($_POST['update_patient'])) {
    $id = $_POST['id'];

    $sql = "UPDATE patients SET ";
    $updateFields = [];

    if (!empty($_POST['name'])) $updateFields[] = "name='" . $conn->real_escape_string($_POST['name']) . "'";
    if (!empty($_POST['age'])) $updateFields[] = "age='" . (int)$_POST['age'] . "'";
    if (!empty($_POST['gender'])) $updateFields[] = "gender='" . $conn->real_escape_string($_POST['gender']) . "'";
    if (!empty($_POST['medical_history'])) $updateFields[] = "medical_history='" . $conn->real_escape_string($_POST['medical_history']) . "'";
    if (!empty($_POST['status'])) $updateFields[] = "status='" . $conn->real_escape_string($_POST['status']) . "'";
    if (!empty($_POST['location'])) $updateFields[] = "location='" . $conn->real_escape_string($_POST['location']) . "'";
    if (!empty($_POST['contact_number'])) $updateFields[] = "contact_number='" . $conn->real_escape_string($_POST['contact_number']) . "'";
    if (!empty($_POST['created_at'])) $updateFields[] = "created_at='" . $conn->real_escape_string($_POST['created_at']) . "'";

    // Handle image upload
    if (isset($_FILES['past_doc']) && $_FILES['past_doc']['error'] === 0) {
        $upload_dir = "uploads/";
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

        $image_name = basename($_FILES["past_doc"]["name"]);
        $target_path = $upload_dir . $image_name;

        if (move_uploaded_file($_FILES["past_doc"]["tmp_name"], $target_path)) {
            $updateFields[] = "image='" . $conn->real_escape_string($image_name) . "'";
        } else {
            echo "<script>alert('Image upload failed.');</script>";
        }
    }

    if (!empty($updateFields)) {
        $sql .= implode(", ", $updateFields) . " WHERE id='$id'";

        if ($conn->query($sql)) {
            echo "<script>alert('Patient updated successfully!'); window.location = 'patient_list_add.php';</script>";
        } else {
            echo "<script>alert('Error updating patient: " . $conn->error . "');</script>";
        }
    }
}

// ============================================
// Delete Patient
// ============================================

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM patients WHERE id='$id'");
    echo "<script>alert('Patient deleted successfully!'); window.location = 'patient_list_add.php';</script>";
}

// ============================================
// Fetch All Patients with Latest Doctor
// ============================================

$patients = $conn->query("
    SELECT 
        patients.*, 
        COALESCE(latest_doctor.doctor_name, 'No Check Up') AS doctor_name 
    FROM patients 
    LEFT JOIN (
        SELECT medications.patient_id, doctors.name AS doctor_name
        FROM medications 
        JOIN doctors ON medications.prescribed_by = doctors.id 
        ORDER BY medications.created_at DESC
    ) AS latest_doctor ON patients.id = latest_doctor.patient_id 
    ORDER BY patients.created_at DESC
");

// ============================================
// Add New Patient
// ============================================

if ($_SERVER["REQUEST_METHOD"] === "POST" && !isset($_POST['update_patient'])) {
    $image_name = '';

    // Handle image upload
    if (isset($_FILES['past_doc']) && $_FILES['past_doc']['error'] === 0) {
        $upload_dir = "uploads/";
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

        $image_name = basename($_FILES["past_doc"]["name"]);
        $target_path = $upload_dir . $image_name;

        if (!move_uploaded_file($_FILES["past_doc"]["tmp_name"], $target_path)) {
            echo "<script>alert('Image upload failed.');</script>";
            $image_name = '';
        }
    }

    // Insert new patient record
    $stmt = $conn->prepare("
        INSERT INTO patients 
        (name, age, gender, medical_history, location, contact_number, status, image) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param(
        "sissssss",
        $_POST['name'],
        $_POST['age'],
        $_POST['gender'],
        $_POST['medical_history'],
        $_POST['location'],
        $_POST['contact_number'],
        $_POST['status'],
        $image_name
    );

    if ($stmt->execute()) {
        echo "<script>alert('Patient added successfully!'); window.location.href='patient_list_add.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
}
?>
<style>
    .modal-content {
        width: 120%;
    }

    div#patientTable_length {
        margin-bottom: 15px !important;
    }
</style>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<main class="app-main">
    <!-- breadCrumb -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Add New Patients</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Add Patients</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <!-- Total Patients -->
                <div class="col-12 col-md col-sm-6">
                    <div class="small-box text-bg-primary">
                        <div class="inner">
                            <h3><?= $total_patients; ?></h3>
                            <p>Total Patients</p>
                        </div>
                        <i class="fas fa-user-injured small-box-icon"></i>
                    </div>
                </div>

                <!-- Active Cases -->
                <div class="col-12 col-md col-sm-6">
                    <div class="small-box text-bg-success">
                        <div class="inner">
                            <h3><?= $total_active_cases; ?></h3>
                            <p>Active Cases</p>
                        </div>
                        <i class="fas fa-procedures small-box-icon"></i>
                    </div>
                </div>

                <!-- Inactive Case -->
                <div class="col-12 col-md col-sm-6">
                    <div class="small-box text-bg-warning">
                        <div class="inner">
                            <h3><?= $total_inactive_cases; ?></h3>
                            <p>Inactive Case</p>
                        </div>
                        <i class="fas fa-users small-box-icon"></i>
                    </div>
                </div>

                <!-- Medications Overview -->
                <div class="col-12 col-md col-sm-6">
                    <div class="small-box text-bg-danger">
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
                    <h3 class="card-title"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                            <path d="M11.998 2.5A9.503 9.503 0 0 0 3.378 8H5.75a.75.75 0 0 1 0 1.5H2a1 1 0 0 1-1-1V4.75a.75.75 0 0 1 1.5 0v1.697A10.997 10.997 0 0 1 11.998 1C18.074 1 23 5.925 23 12s-4.926 11-11.002 11C6.014 23 1.146 18.223 1 12.275a.75.75 0 0 1 1.5-.037 9.5 9.5 0 0 0 9.498 9.262c5.248 0 9.502-4.253 9.502-9.5s-4.254-9.5-9.502-9.5Z"></path>
                            <path d="M12.5 7.25a.75.75 0 0 0-1.5 0v5.5c0 .27.144.518.378.651l3.5 2a.75.75 0 0 0 .744-1.302L12.5 12.315V7.25Z"></path>
                        </svg> Total Resent Patient list</h3>
                </div>
                <div class="card-body">
                    <!-- Search Filter & Add Button -->
                    <div class="row mb-3">
                        <div class="col-md-9">
                            <input type="text" id="searchPatient" class="form-control" placeholder="Search by Name...">
                        </div>
                        <div class="col-md-3 text-end">
                            <!-- Add Patient Button -->
                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#AddPatientModal"><i class="fa-solid fa-square-plus"></i> Add New Patient</button>
                        </div>
                    </div>
                    <!-- Patients Table -->
                    <table class="table table-hover table-bordered align-middle text-center" id="patientTable">
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
                                <th>Patient Document</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch patients with doctor info
                            $sql = "
            SELECT 
                patients.id, 
                patients.name, 
                patients.age, 
                patients.gender, 
                patients.medical_history,
                patients.location,
                patients.contact_number, 
                patients.status, 
                patients.created_at, 
                patients.image,
                doctors.name AS doctor_name 
            FROM patients 
            LEFT JOIN medications ON medications.patient_id = patients.id 
            LEFT JOIN doctors ON medications.prescribed_by = doctors.id 
            ORDER BY patients.created_at DESC
        ";

                            $patients = $conn->query($sql);

                            if (!$patients) {
                                die("SQL Error: " . $conn->error);
                            }

                            if ($patients->num_rows > 0):
                                while ($patient = $patients->fetch_assoc()):
                            ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($patient['name']) ?></strong></td>
                                        <td><?= (int)$patient['age'] ?></td>
                                        <td><?= htmlspecialchars($patient['gender']) ?></td>
                                        <td><?= htmlspecialchars($patient['medical_history']) ?></td>
                                        <td><?= htmlspecialchars($patient['location']) ?></td>
                                        <td><span class="text-danger"><?= htmlspecialchars($patient['contact_number']) ?></span></td>
                                        <td class="<?= empty($patient['doctor_name']) || $patient['doctor_name'] == 'No Check Up' ? 'text-danger fw-bold' : '' ?>">
                                            <?= !empty($patient['doctor_name']) ? htmlspecialchars($patient['doctor_name']) : "No Check Up" ?>
                                        </td>
                                        <td>
                                            <span class="badge status-badge"><?= ucfirst(htmlspecialchars($patient['status'])) ?></span>
                                        </td>
                                        <td><span class="text-primary"><?= date("d M Y", strtotime($patient['created_at'])) ?></span></td>
                                        <td>
                                            <img src="uploads/<?= !empty($patient['image']) ? htmlspecialchars($patient['image']) : 'default1.png' ?>"
                                                alt="Patient Image"
                                                style="width:100px; height:100px; object-fit:cover;">
                                        </td>
                                        <td class="d-flex justify-content-center align-items-center">

                                            <!-- Edit Button with data attributes for modal/form -->
                                            <button
                                                class="btn btn-outline-warning btn-sm editbtn mx-2"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editModal"
                                                data-id="<?= $patient['id'] ?>"
                                                data-name="<?= $patient['name'] ?>"
                                                data-age="<?= $patient['age'] ?>"
                                                data-gender="<?= $patient['gender'] ?>"
                                                data-medical_history="<?= $patient['medical_history'] ?>"
                                                data-status="<?= $patient['status'] ?>"
                                                data-contact_number="<?= $patient['contact_number'] ?>"
                                                data-location="<?= $patient['location'] ?>">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>

                                            <!-- Delete Link with confirmation -->
                                            <a href="patient_list_add.php?delete=<?= $patient['id'] ?>"
                                                class="btn btn-outline-danger btn-sm"
                                                onclick="return confirm('Are you sure you want to delete this patient?')">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile;
                            else: ?>
                                <tr>
                                    <td colspan="11" class="text-center">No records found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Add Patient Modal -->
<div class="modal fade" id="AddPatientModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Patient</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 col-sm-6 col-md-6">
                            <div class="mb-3">
                                <label>Patient Name:</label>
                                <input type="text" class="form-control" name="name" placeholder="Your Name" required>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-md-6">
                            <div class="mb-3">
                                <label>Age:</label>
                                <input type="number" class="form-control" name="age" placeholder="22" required>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-md-6">
                            <div class="mb-3">
                                <label>Gender:</label>
                                <select class="form-control" name="gender" required>
                                    <option value="" selected disabled>Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <!-- <option value="Other">Other</option> -->
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-md-6">
                            <div class="mb-3">
                                <label>Medical History:</label>
                                <textarea class="form-control" name="medical_history" required placeholder="Your Disease"></textarea>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-md-6">
                            <div class="mb-3">
                                <label>Patient Status:</label>
                                <select class="form-control" name="status" required>
                                    <option value="" selected disabled>Select Status</option>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-md-6">
                            <div class="mb-3">
                                <label>Address/City:</label>
                                <input type="text" class="form-control" name="location" required placeholder="Address">
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-md-6">
                            <div class="mb-3">
                                <label>Phone Number:</label>
                                <input type="number" class="form-control" name="contact_number" required placeholder="03001234567">
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-md-6">
                            <div class="mb-3">
                                <label>Upload Past Documets:</label>
                                <input type="file" name="past_doc" class="form-control" multiple="multiple">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Patient</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Patient Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Patient</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" enctype="multipart/form-data">
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

                        <div class="col-12 col-md-6 col-sm-12 col-lg-6">
                            <div class="mb-3">
                                <label>Edit Document</label>
                                <input type="file" name="past_doc" class="form-control" multiple="multiple">
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
    // eeeeee
    $('.editbtn').on('click', function() {
        var button = $(this);

        // Fetching the data from the button
        $('#editModal #edit_id').val(button.data('id')); // <-- Set the patient ID
        $('#editModal input[name="name"]').val(button.data('name'));
        $('#editModal input[name="age"]').val(button.data('age'));
        $('#editModal input[name="gender"]').val(button.data('gender'));
        $('#editModal textarea[name="medical_history"]').val(button.data('medical_history'));
        $('#editModal input[name="status"]').val(button.data('status'));
        $('#editModal input[name="contact_number"]').val(button.data('contact_number'));
        $('#editModal input[name="location"]').val(button.data('location'));
    });
</script>


<script src="js/index.js"></script>
<?php include('Include/footer.php'); ?>