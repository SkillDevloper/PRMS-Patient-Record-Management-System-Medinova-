<?php
// Set page title
$page_title = "Doctor List";

// Include configuration and layout files
include 'config.php';
include('Include/header.php');
include('Include/navbar.php');
include('Include/sidebar.php');

// ===============================
// Fetch All Doctors from Database
// ===============================
$doctors = $conn->query("SELECT * FROM doctors ORDER BY created_at DESC");

// ===============================
// Add New Doctor
// ===============================
if (isset($_POST['add_doctor'])) {
    // Prepare insert statement
    $stmt = $conn->prepare("
        INSERT INTO doctors 
        (name, specialty, experience, hospital_name, contact_number_dr, email, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    // Bind form data to query
    $stmt->bind_param(
        "ssissss",
        $_POST['name'],
        $_POST['specialty'],
        $_POST['experience'],
        $_POST['hospital_name'],
        $_POST['contact_number_dr'],
        $_POST['email'],
        $_POST['status']
    );

    // Execute and check result
    if ($stmt->execute()) {
        echo "<script>alert('Doctor added successfully!'); window.location='doctor_list.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
}

// ===============================
// Update Existing Doctor
// ===============================
if (isset($_POST['update_doctor'])) {
    // Prepare update statement
    $stmt = $conn->prepare("
        UPDATE doctors SET 
            name = ?, 
            specialty = ?, 
            experience = ?, 
            hospital_name = ?, 
            contact_number_dr = ?, 
            email = ?, 
            status = ? 
        WHERE id = ?
    ");

    // Bind form data to query
    $stmt->bind_param(
        "ssissssi",
        $_POST['name'],
        $_POST['specialty'],
        $_POST['experience'],
        $_POST['hospital_name'],
        $_POST['contact_number_dr'],
        $_POST['email'],
        $_POST['status'],
        $_POST['id']
    );

    // Execute and check result
    if ($stmt->execute()) {
        echo "<script>alert('Doctor updated successfully!'); window.location='doctor_list.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
}

// ===============================
// Delete Doctor
// ===============================
if (isset($_POST['delete_doctor'])) {
    // Prepare delete statement
    $stmt = $conn->prepare("DELETE FROM doctors WHERE id = ?");
    $stmt->bind_param("i", $_POST['id']);

    // Execute and check result
    if ($stmt->execute()) {
        echo "<script>alert('Doctor deleted successfully!'); window.location='doctor_list.php';</script>";
    } else {
        echo "<script>alert('Error deleting doctor!');</script>";
    }
}
?>

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Add New Doctors</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Doctos List</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="d-flex justify-content-between w-100 flex-column">
                                <h3 class="card-title">Doctors List</h3>
                                <div class="div">
                                    <input type="text" id="searchPatient" class="form-control my-3" placeholder="Search by Name...">
                                    <!-- Add Doctors Button -->
                                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#addDoctorModal">
                                        <i class="fa-solid fa-square-plus"></i> Add Doctor
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="patientTable" class="table table-hover table-bordered align-middle text-center">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Name</th>
                                        <th>Specialty</th>
                                        <th>Experience</th>
                                        <th>Hospital/Clinic</th>
                                        <th>Contact</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php if ($doctors->num_rows > 0): ?>
                                        <?php while ($row = $doctors->fetch_assoc()): ?>
                                            <tr>
                                                <td><strong><?= $row['name']; ?></strong></td>
                                                <td><?= $row['specialty']; ?></td>
                                                <td><span class="text-danger"><?= $row['experience']; ?> years</span></td>
                                                <td><?= $row['hospital_name']; ?></td>
                                                <td><span class="text-primary"><?= $row['contact_number_dr']; ?></span></td>
                                                <td><?= $row['email']; ?></td>
                                                <td>
                                                    <span class="badge status-badge" data-status="<?= $row['status']; ?>">
                                                        <?= ucfirst($row['status']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <!-- Edit Button -->
                                                    <button class="btn btn-outline-warning btn-sm edit-btn mx-2"  data-bs-toggle="modal" data-bs-target="#editDoctorModal"
                                                        data-id="<?= $row['id']; ?>"
                                                        data-name="<?= $row['name']; ?>"
                                                        data-specialty="<?= $row['specialty']; ?>"
                                                        data-experience="<?= $row['experience']; ?>"
                                                        data-hospital="<?= $row['hospital_name']; ?>"
                                                        data-contact="<?= $row['contact_number_dr']; ?>"
                                                        data-email="<?= $row['email']; ?>"
                                                        data-status="<?= $row['status']; ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </button>

                                                    <!-- Delete Form -->
                                                    <form method="POST" style="display:inline;">
                                                        <input type="hidden" name="delete_doctor" value="1">
                                                        <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <!-- If no doctor records found -->
                                        <tr>
                                            <td colspan="8" class="text-center">No records found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Add Doctor Modal -->
    <div class="modal fade" id="addDoctorModal" tabindex="-1" aria-labelledby="addDoctorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDoctorModalLabel">Add Doctor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="mb-2">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label>Specialty</label>
                            <input type="text" name="specialty" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label>Experience (years)</label>
                            <input type="number" name="experience" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label>Hospital/Clinic Name</label>
                            <input type="text" name="hospital_name" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label>Contact Number</label>
                            <input type="text" name="contact_number_dr" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="" disabled selected>Select Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <button type="submit" name="add_doctor" class="btn btn-primary w-100">Add Doctor</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Doctor Modal -->
    <div class="modal fade" id="editDoctorModal" tabindex="-1" aria-labelledby="editDoctorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDoctorModalLabel">Edit Doctor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <input type="hidden" name="id" id="editDoctorId">
                        <div class="mb-2">
                            <label>Name</label>
                            <input type="text" name="name" id="editDoctorName" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label>Specialty</label>
                            <input type="text" name="specialty" id="editDoctorSpecialty" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label>Experience (years)</label>
                            <input type="number" name="experience" id="editDoctorExperience" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label>Hospital/Clinic Name</label>
                            <input type="text" name="hospital_name" id="editDoctorHospital" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label>Contact Number</label>
                            <input type="text" name="contact_number_dr" id="editDoctorContact" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label>Email</label>
                            <input type="email" name="email" id="editDoctorEmail" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label>Status</label>
                            <select name="status" id="editDoctorStatus" class="form-control">
                                <option value="" disabled selected>Select Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <button type="submit" name="update_doctor" class="btn btn-success w-100">Update Doctor</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</main>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".editDoctorBtn").forEach(button => {
            button.addEventListener("click", function() {
                console.log("Edit button clicked!"); // Debugging

                // Set Form Values
                document.getElementById("editDoctorId").value = this.getAttribute("data-id");
                document.getElementById("editDoctorName").value = this.getAttribute("data-name");
                document.getElementById("editDoctorSpecialty").value = this.getAttribute("data-specialty");
                document.getElementById("editDoctorExperience").value = this.getAttribute("data-experience");
                document.getElementById("editDoctorHospital").value = this.getAttribute("data-hospital");
                document.getElementById("editDoctorContact").value = this.getAttribute("data-contact");
                document.getElementById("editDoctorEmail").value = this.getAttribute("data-email");
                // document.getElementById("editDoctorStatus").value = this.getAttribute("data-status");

                // Open Edit Modal
                let myModal = new bootstrap.Modal(document.getElementById("editDoctorModal"));
                myModal.show();
            });
        });
    });
    // Bages Active/inactive
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".status-badge").forEach(function(badge) {
            let status = badge.getAttribute("data-status").toLowerCase();

            if (status === "active") {
                badge.classList.add("bg-success");
            } else {
                badge.classList.add("bg-danger");
            }
        });
    });

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
<?php
include('Include/footer.php');
?>